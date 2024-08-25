<?php

namespace App\Imports;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $processedSkus = [];

    public function collection(Collection $rows)
    {
        $existingSkus = Product::pluck('sku')->toArray();
        $newSkus = [];
        $errors = [];
        $userId = Auth::user()->user_id;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because Excel row numbers are 1-based and we have a heading row
            $sku = $row['sku'];

            // Check if SKU has already been processed in this session
            if (in_array($sku, $this->processedSkus)) {
                $errors[] = "Row {$rowNumber}: The SKU {$sku} is repeated within the import file.";
                continue;
            }

            // Check if SKU already exists in the database
            if (in_array($sku, $existingSkus)) {
                $errors[] = "Row {$rowNumber}: The SKU {$sku} is already exists.";
                continue;
            }

            // Add SKU to the processed SKUs array
            $this->processedSkus[] = $sku;

            $status = strtolower($row['status']) === 'active' ? 1 : 0;

            try {
                $date = Carbon::createFromFormat('d-m-Y', $row['date_d_m_y'])->format('Y-m-d');
            } catch (Exception $e) {
                $errors[] = "Row {$rowNumber}: {$row['date_d_m_y']} is an invalid date format.";
                continue;
            }

            // Check if category exists
            $category = Category::where('name', $row['category'])->first();
            if (!$category) {
                $errors[] = "Row {$rowNumber}: Category {$row['category']} not found.";
                continue;
            }

            // Check if supplier exists
            $supplier = Supplier::where('name', $row['supplier'])->first();
            if (!$supplier) {
                $errors[] = "Row {$rowNumber}: Supplier {$row['supplier']} not found.";
                continue;
            }

            // Insert the product into the database
            Product::create([
                'date' => $date,
                'category_id' => $category->id,
                'name' => $row['name'],
                'sku' => $sku,
                'image' => $row['image'],
                'description' => $row['description'],
                'status' => $status,
                'sup_id' => $supplier->id,
                'user_id' => $userId
            ]);
        }

        if (!empty($errors)) {
            // If there are errors, return them to the user
            throw new Exception(json_encode($errors));
        } else {
            return redirect()->back()->with('success', 'Data Imported Successfully');
        }
    }
}
