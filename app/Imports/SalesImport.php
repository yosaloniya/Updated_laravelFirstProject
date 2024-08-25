<?php

namespace App\Imports;

use Exception;
use App\Models\Sales;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Customers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $orders = [];
    protected $subSkuSizes = [];

    public function collection(Collection $rows)
    {
        $userId = Auth::user()->user_id;

        // Validate all rows first
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because Excel row numbers are 1-based and we have a heading row
            $orderNo = $row['order_no'];

            // Validate order number
            if (Sales::where('order_no', $orderNo)->exists()) {
                $this->errors[] = "Row {$rowNumber}: Order No. " . $orderNo . " already exists.";
                continue;
            }

            // Validate main SKU
            $product = Product::where('sku', $row['main_sku'])->first();
            if (!$product) {
                $this->errors[] = "Row {$rowNumber}: " . $row['main_sku'] . " Product does not exist.";
                continue;
            }

            $product_id = $product->id;
            // Validate sub SKU
            $subSku = Subsku::where(['product_id' => $product_id, 'sku' => $row['sub_sku']])->first();
            if (!$subSku) {
                $this->errors[] = "Row {$rowNumber}: " . $row['sub_sku'] . " SubSku does not exist.";
                continue;
            }

             // Validate and ensure qty is numeric
            $qty = floatval($subSku->qty);
           

            // Validate customer
            $customer = Customers::where('name', $row['customer'])->first();
            if (!$customer) {
                $this->errors[] = "Row {$rowNumber}: " . $row['customer'] . " Customer does not exist.";
                continue;
            }

            // Validate date
            try {
                $date = Carbon::createFromFormat('d-m-Y', $row['date_d_m_y'])->format('Y-m-d');
            } catch (Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $row['date_d_m_y'] . " is an invalid date format.";
                continue;
            }

            if (!isset($this->orders[$orderNo])) {
                $this->orders[$orderNo] = [
                    'order_no' => $row['order_no'],
                    'date' => $date,
                    'customer_id' => $customer->id,
                    'description' => $row['description'],
                    'products' => [],
                    'user_id' => $userId
                ];
            }

              // Validate size
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

            if ($qty < $size) {
                $this->errors[] = "Row {$rowNumber}: " . $row['main_sku'] . " - " . $row['sub_sku'] . " This Product Size not available.";
                continue;
            }

            $productData = [
                'sku' => $product_id,
                'sub_sku' => $subSku->id,
                'p_id' => $subSku->id,
                'qty' => $size,
            ];

            $this->orders[$orderNo]['products'][] = $productData;

            // Aggregate sizes for subSku
            $key = $row['main_sku'] . '-' . $row['sub_sku'];
            if (!isset($this->subSkuSizes[$key])) {
                $this->subSkuSizes[$key] = 0;
            }
            $this->subSkuSizes[$key] += $size;
        }

        // If there are errors, return them to the user and do not import any data
        if (!empty($this->errors)) {
            return redirect()->back()->withErrors($this->errors);
        }

        // If no errors, proceed with data insertion
        \DB::transaction(function() {
            foreach ($this->orders as $order) {
                Sales::create([
                    'date' => $order['date'],
                    'order_no' => $order['order_no'],
                    'product_id' => serialize($order['products']),
                    'customer_id' => $order['customer_id'],
                    'user_id' => $order['user_id'],
                    'description' => $order['description'],
                ]);
            }

            // Update subSku quantities based on aggregated sizes
            foreach ($this->subSkuSizes as $key => $totalSize) {
                list($mainSku, $subSku) = explode('-', $key);
                $product = Product::where('sku', $mainSku)->first();
                $subSkuModel = Subsku::where(['product_id' => $product->id, 'sku' => $subSku])->first();
                if ($subSkuModel) {
                    $totalSize = floatval($totalSize);
                    $newQty = floatval($subSkuModel->qty) - $totalSize;
                    if ($newQty < 0) {
                        throw new Exception("Total size for {$mainSku} - {$subSku} exceeds available quantity.");
                    } else {
                        $subSkuModel->update(['qty' => $newQty]);
                    }
                }
            }
        });

        // Return success message
        return redirect()->back()->with('success', 'Data Imported Successfully');
    }
}
