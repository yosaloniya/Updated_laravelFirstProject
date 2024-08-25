<?php

namespace App\Imports;

use Exception;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Returns;
use App\Models\Customers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReturnsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $validRows = [];
    protected $skuOccurrences = [];

    public function collection(Collection $rows)
    {
        try {
            $userId = Auth::user()->user_id;
        } catch (Exception $e) {
            Log::error('User not authenticated: ' . $e->getMessage());
            $this->errors[] = 'User not authenticated.';
            $this->handleErrors();
            return;
        }

        Log::info('Import started');

        foreach ($rows as $index => $row) 
        {
            $rowNumber = $index + 2; // +2 because Excel row numbers are 1-based and we have a heading row

            Log::info('Processing row: ' . $rowNumber, $row->toArray());

            try {
                $product = Product::where('sku', $row['main_sku'])->first();
                if (!$product) {
                    $this->errors[] = "Row {$rowNumber}: " . $row['main_sku'] . " Product not Found.";
                    Log::warning('Product not exists: ' . $row['main_sku']);
                    continue;
                }

                $product_id = $product->id;

                $existingSubSku = Subsku::where(['product_id' => $product_id, 'sku' => $row['sub_sku']])->first();
                if ($existingSubSku) {
                    $this->errors[] = "Row {$rowNumber}: The Sub-SKU " . $row['sub_sku'] . " for Main-SKU ". $row['main_sku']." is already exists." ;
                    Log::warning('SubSKU already exists: ' . $row['sub_sku'] . ' for Main_sku: ' . $row['main_sku']);
                    continue;
                }

                $customer = Customers::where('name', $row['customer'])->first();
                if (!$customer) {
                    $this->errors[] = "Row {$rowNumber}: " . $row['customer'] . " Customer not exists.";
                    Log::warning('Customer not exists: ' . $row['customer']);
                    continue;
                }

                try {
                    $date = Carbon::createFromFormat('d-m-Y', $row['date_d_m_y'])->format('Y-m-d');
                } catch (Exception $e) {
                    $this->errors[] = "Row {$rowNumber}: " . $row['date_d_m_y'] . " is an invalid date format.";
                    Log::error('Invalid date format: ' . $row['date_d_m_y']);
                    continue;
                }

                // Validate size
                $size = $row['size'];
                if (!filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
                    $this->errors[] = "Row {$rowNumber}: Size value '{$size}' is invalid. Only numeric values are allowed.";
                    continue;
                }

                $size = floatval($size);
                if ($size < 0) {
                    $this->errors[] = "Row {$rowNumber}: Size must be a positive number.";
                    continue;
                }

                if (isset($size) && $size == 0) {
                    $this->errors[] = "Row {$rowNumber}: " . $row['sub_sku'] . " Size is invalid.";
                    Log::warning('Invalid size: ' . $row['size']);
                } else {
                    // Track occurrences of main_sku and sub_sku
                    $mainSku = $row['main_sku'];
                    $subSku = $row['sub_sku'];
                    
                    if (!isset($this->skuOccurrences[$mainSku])) {
                        $this->skuOccurrences[$mainSku] = [];
                    }

                    if (in_array($subSku, $this->skuOccurrences[$mainSku])) {
                        $this->errors[] = "Row {$rowNumber}: The Sub-SKU {$subSku} for Main-SKU {$mainSku} is repeated in the import file.";
                        $this->validRows = []; // Clear valid rows to avoid importing any data
                        break; // Stop processing further rows
                    }

                    $this->skuOccurrences[$mainSku][] = $subSku;

                    $this->validRows[] = [
                        'return' => [
                            'date' => $date,
                            'm_sku' => $product_id,
                            's_sku' => $row['sub_sku'],
                            'size' => $row['size'],
                            'customer_id' => $customer->id,
                            'location' => $row['location'],
                            'user_id' => $userId,
                        ],
                        'subsku' => [
                            'date' => $date,
                            'product_id' => $product_id,
                            'sku' => $row['sub_sku'],
                            'sup_id' => "Not Found",
                            'description' => "Return from " . $customer->name,
                            'qty' => $row['size'],
                            'location' => $row['location'],
                            'user_id' => $userId,
                            'status' => 1,
                        ]
                    ];
                }
            } catch (Exception $e) {
                Log::error('Error processing row: ' . $e->getMessage(), ['row' => $row->toArray()]);
                $this->errors[] = "Row {$rowNumber}: Error processing row: " . json_encode($row->toArray());
            }
        }

        $this->handleErrors();
    }

    protected function handleErrors()
    {
        if (!empty($this->errors)) {
            Log::error('Errors occurred during import', ['errors' => $this->errors]);
            Session::flash('errors', $this->errors);
            
        } else {
            Log::info('Data Imported Successfully');

            foreach ($this->validRows as $row) {
                Returns::create($row['return']);
                Subsku::create($row['subsku']);
            }

            Session::flash('success', 'Data Imported Successfully');
        }

        Log::info('Session data', ['errors' => Session::get('errors'), 'success' => Session::get('success')]);
    }
}
