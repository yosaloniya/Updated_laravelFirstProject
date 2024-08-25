<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    protected $supId;
    protected $startDateTime;
    protected $endDateTime;

    public function __construct($supId='', ?Carbon $startDateTime, ?Carbon $endDateTime)
    {
        $this->supId = $supId;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
    }

    public function collection()
    {
        $query = Product::select([
            'products.date',
            'categories.name as category_name',
            'products.name',
            'products.sku',
            'products.image',
            'products.description',
            'suppliers.name as supplier_name',
            'products.status',
        ])
        ->join('suppliers', 'products.sup_id', '=', 'suppliers.id')
        ->join('categories', 'products.category_id', '=', 'categories.id');
        if ($this->supId) {
            $query->where('products.sup_id', $this->supId);
        }
        if ($this->startDateTime && $this->endDateTime) {
            $query->whereBetween('date', [$this->startDateTime, $this->endDateTime]);
        } elseif ($this->startDateTime) {
            $query->where('date', '>=', $this->startDateTime);
        } elseif ($this->endDateTime) {
            $query->where('date', '<=', $this->endDateTime);
        }

        $productData = $query->get();
        return $productData;
    }

    public function headings(): array
    {
        return [
            'Date_d_m_y',
            'Category',
            'Name',
            'SKU',
            'Image',
            'Description',
            'Supplier',
            'Status',
        ];
    }

    public function map($product): array
    {
        return [
            Carbon::parse($product->date)->format('d-m-Y'),
            $product->category_name,
            $product->name,
            $product->sku,
            $product->image,
            $product->description,
            $product->supplier_name,
            $product->status == 1 ? 'Active' : 'Inactive',
        ];
    }
}
