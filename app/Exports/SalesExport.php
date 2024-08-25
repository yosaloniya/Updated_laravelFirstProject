<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Subsku;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $customerId;
    protected $startDate;
    protected $endDate;

    public function __construct($customerId = '', ?Carbon $startDate, ?Carbon $endDate)
    {
        $this->customerId = $customerId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Sales::select([
            'sales.date',
            'sales.order_no',
            'sales.product_id',
            'sales.customer_id',
            'customers.name as customer_name',
            'sales.description'
        ])
        ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id');

        if ($this->customerId) {
            $query->where('sales.customer_id', $this->customerId);
        }
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('sales.date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->where('sales.date', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->where('sales.date', '<=', $this->endDate);
        }

        $salesData = $query->get();

        return $salesData->map(function ($sale) {
            $products = unserialize($sale->product_id);
            $mappedProducts = [];

            foreach ($products as $product) {
                $productInfo = Product::find($product['sku']);
                $subSkuInfo = Subsku::find($product['sub_sku']);
                $mappedProducts[] = [
                    'date' => Carbon::parse($sale->date)->format('d-m-Y'),
                    'order_no' => $sale->order_no,
                    'main_sku' => $productInfo->sku ?? 'N/A',
                    'sub_sku' => $subSkuInfo->sku ?? 'N/A',
                    'size' => $product['qty'],
                    'customer' => $sale->customer_name,
                    'description' => $sale->description
                ];
            }

            return $mappedProducts;
        })->collapse();
    }

    public function headings(): array
    {
        return [
            'Date_d_m_y',
            'Order_no',
            'Main_sku',
            'Sub_sku',
            'Size',
            'Customer',
            'Description'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale['date'],
            $sale['order_no'],
            $sale['main_sku'],
            $sale['sub_sku'],
            $sale['size'],
            $sale['customer'],
            $sale['description'],
        ];
    }
}
