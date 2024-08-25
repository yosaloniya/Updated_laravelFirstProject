<?php

namespace App\Imports;

use App\Models\Subsku;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Supplierproduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierproductsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $supplierProducts = [];
    protected $subskuData = []; // Track qty, location, and status
    protected $rowMapping = []; // Map SubSKU IDs to SKUs, SubSKUs, and row numbers

    public function collection(Collection $rows)
    {
        $userId = Auth::user()->user_id;
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because Excel row numbers are 1-based and we have a heading row

            $product = Product::where('sku', $row['main_sku'])->first();
            if (!$product) {
                $this->errors[] = "Row {$rowNumber}: Main_sku '{$row['main_sku']}' does not exist.";
                continue;
            }

            $product_id = $product->id;
            $subSku = Subsku::where(['product_id' => $product_id, 'sku' => $row['sub_sku']])->first();
            if (!$subSku) {
                $this->errors[] = "Row {$rowNumber}: SubSku '{$row['sub_sku']}' does not exist.";
                continue;
            }

            $supplier = Supplier::where('name', $row['supplier'])->first();
            if (!$supplier) {
                $this->errors[] = "Row {$rowNumber}: Supplier '{$row['supplier']}' does not exist.";
                continue;
            }

            try {
                $date = Carbon::createFromFormat('d-m-Y', $row['date_d_m_y'])->format('Y-m-d');
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: Date '{$row['date_d_m_y']}' is in an invalid format.";
                continue;
            }

            $size = $row['size'];
            if (!filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
                $this->errors[] = "Row {$rowNumber}: Size value '{$size}' is invalid. Only numeric values are allowed.";
                continue;
            }

            $size = floatval($size);
            if ($size <= 0) {
                $this->errors[] = "Row {$rowNumber}: Size must be a positive number.";
                continue;
            }

            // Convert status to 1 (Active) or 0 (Inactive)
            $status = strtolower($row['status']) === 'active' ? 1 : (strtolower($row['status']) === 'inactive' ? 0 : null);
            if ($status === null) {
                $this->errors[] = "Row {$rowNumber}: Status value '{$row['status']}' is invalid. It should be either 'Active' or 'Inactive'.";
                continue;
            }

            // Store Supplierproduct entries
            $this->supplierProducts[] = [
                'date' => $date,
                'sku' => $product->sku,
                'sub_sku' => $subSku->sku,
                'size' => $size,
                'sup_id' => $supplier->id,
                'product_id' => $product_id,
                'description' => $row['description'],
                'user_id' => $userId,
            ];

            // Aggregate sizes and track location and status
            if (!isset($this->subskuData[$subSku->id])) {
                $this->subskuData[$subSku->id] = [
                    'qty' => 0,
                    'locations' => [$row['location']], // Track all locations
                    'statuses' => [$status], // Track all statuses
                ];
                // Map SubSKU ID to SKU, SubSKU, and row number
                $this->rowMapping[$subSku->id] = [
                    'sku' => $product->sku,
                    'sub_sku' => $subSku->sku,
                    'row_number' => $rowNumber
                ];
            }
            // Aggregate sizes for the same SubSKU
            $this->subskuData[$subSku->id]['qty'] += $size;
            $this->subskuData[$subSku->id]['locations'][] = $row['location'];
            $this->subskuData[$subSku->id]['statuses'][] = $status;
        }

        $this->validateData();
        $this->handleErrors();
    }

    protected function validateData()
    {
        foreach ($this->subskuData as $subSkuId => $data) {
            // Check for location consistency
            $uniqueLocations = array_unique($data['locations']);
            if (count($uniqueLocations) > 1) {
                $sku = $this->rowMapping[$subSkuId]['sku'];
                $sub_sku = $this->rowMapping[$subSkuId]['sub_sku'];
                $rowNumber = $this->rowMapping[$subSkuId]['row_number'];
                $this->errors[] = "Row {$rowNumber}: Inconsistent locations for Product '{$sku}' - '{$sub_sku}': Location should be the same for the same product.";
            }

            // Check for status consistency
            $uniqueStatuses = array_unique($data['statuses']);
            if (count($uniqueStatuses) > 1) {
                $sku = $this->rowMapping[$subSkuId]['sku'];
                $sub_sku = $this->rowMapping[$subSkuId]['sub_sku'];
                $rowNumber = $this->rowMapping[$subSkuId]['row_number'];
                $this->errors[] = "Row {$rowNumber}: Inconsistent status for Product '{$sku}' - '{$sub_sku}': Status should be the same for the same product.";
            }
        }
    }

    protected function handleErrors()
    {
        if (!empty($this->errors)) {
            // Log errors for debugging
            \Log::error('Import errors: ', $this->errors);
            session()->flash('errors', $this->errors);
            return; // Abort import
        }

        // Create Supplierproduct records
        foreach ($this->supplierProducts as $product) {
            Supplierproduct::create([
                'date' => $product['date'],
                'sku' => $product['sku'],
                'sub_sku' => $product['sub_sku'],
                'size' => $product['size'],
                'sup_id' => $product['sup_id'],
                'product_id' => $product['product_id'],
                'qty' => $product['size'],
                'description' => $product['description'],
                'user_id' => $product['user_id'],
            ]);
        }

        // Update Subsku quantities, location, and status
        foreach ($this->subskuData as $subSkuId => $data) {
            $subSku = Subsku::find($subSkuId);
            if ($subSku) {
                // Add sizes to the existing qty
                $newQty = $subSku->qty + $data['qty'];
                $subSku->update([
                    'qty' => $newQty,
                    'location' => $data['locations'][0], // Use the first location found
                    'status' => $data['statuses'][0], // Use the first status found
                ]);
            }
        }

        session()->flash('success', 'Data Imported Successfully');
    }
}
