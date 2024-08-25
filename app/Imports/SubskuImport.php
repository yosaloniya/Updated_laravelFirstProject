<?php

namespace App\Imports;

use Exception;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubskuImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $existingSkus = [];

    public function __construct()
    {
        // Load existing SKUs from the database
        $this->existingSkus = Subsku::pluck('product_id', 'sku')->toArray();
    }

    public function collection(Collection $rows)
    {
        $errors = [];
        $newSkus = [];
        $validRows = [];
        $userId = Auth::user()->user_id;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because Excel row numbers are 1-based and we have a heading row

            try {
                $sku = $row['sub_sku'];
                if (!$sku) {
                    $errors[] = "Row {$rowNumber}: Sub-SKU not found.";
                    continue;
                }

                $product = Product::where('sku', $row['main_sku'])->first();
                if (!$product) {
                    $errors[] = "Row {$rowNumber}: Main-SKU {$row['main_sku']} not found.";
                    continue;
                }

                $product_id = $product->id;

                // Check if SKU already exists in the database or in the new SKUs array
                if (isset($this->existingSkus[$sku]) && $this->existingSkus[$sku] == $product_id) {
                    $errors[] = "Row {$rowNumber}: The Sub-SKU {$sku} for Main-SKU {$row['main_sku']} already exists.";
                    continue;
                }

                if (isset($newSkus[$product_id]) && in_array($sku, $newSkus[$product_id])) {
                    $errors[] = "Row {$rowNumber}: The Sub-SKU '{$sku}' for Main-SKU '{$row['main_sku']}' is repeated in the import file.";
                    continue;
                }

                $newSkus[$product_id][] = $sku;

                // Validate supplier
                if ($row['supplier'] == 'Not Found') {
                    $sup_id = 'Not Found';
                } else {
                    $supplier = Supplier::where('name', $row['supplier'])->first();
                    if (!$supplier) {
                        $errors[] = "Row {$rowNumber}: Supplier {$row['supplier']} not found.";
                        continue;
                    }

                    $sup_id = $supplier->id;
                    $status = strtolower($row['status']) === 'active' ? 1 : 0;
                }

                // Validate date
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $row['date_d_m_y'])->format('Y-m-d');
                } catch (Exception $e) {
                    $errors[] = "Row {$rowNumber}: {$row['date_d_m_y']} is an invalid date format.";
                    continue;
                }

                // Validate size
                $size = $row['size'];
                if (!filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
                    $errors[] = "Row {$rowNumber}: Size value '{$size}' is invalid. Only numeric values are allowed.";
                    continue;
                }

                $size = floatval($size);
                if ($size <= 0) {
                    $errors[] = "Row {$rowNumber}: Size must be a positive number.";
                    continue;
                }

                // Prepare the valid row data
                $validRows[] = [
                    'date' => $date,
                    'product_id' => $product_id,
                    'sku' => $row['sub_sku'],
                    'sup_id' => $sup_id,
                    'qty' => $size,
                    'status' => $status,
                    'description' => $row['description'],
                    'location' => $row['location'],
                    'user_id' => $userId,
                ];

            } catch (Exception $e) {
                // Log the full exception stack trace for debugging
                Log::error("Row {$rowNumber}: Error processing row - " . $e->getMessage());
                Log::error($e->getTraceAsString());
                $errors[] = "Row {$rowNumber}: Error processing row. " . $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            // If there are errors, return them to the user and do not import any data
            return redirect()->back()->with('error', implode('###', $errors));
        } else {
            try {
                // If no errors, insert all valid rows into the database
                foreach ($validRows as $row) {
                    Subsku::create($row);
                }
                return redirect()->back()->with('success', 'Data Imported Successfully');
            } catch (Exception $e) {
                Log::error('Error inserting data: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
                return redirect()->back()->with('error', 'An error occurred while importing the data.');
            }
        }
    }
}
