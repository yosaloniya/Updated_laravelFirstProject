<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Models\Supplierproduct;
// use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierproductsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Supplierproduct::select([
            'supplierproducts.date',
            'supplierproducts.sku',
            'supplierproducts.sub_sku',
            'supplierproducts.qty',
            'suppliers.name',
            'supplierproducts.description',
        ])->join('suppliers', 'supplierproducts.sup_id', '=', 'suppliers.id');
        
        if ($this->supId) {
            $query->where('supplierproducts.sup_id', $this->supId);
        }
        if ($this->startDateTime && $this->endDateTime) {
            $query->whereBetween('date', [$this->startDateTime, $this->endDateTime]);
        } elseif ($this->startDateTime) {
            $query->where('date', '>=', $this->startDateTime);
        } elseif ($this->endDateTime) {
            $query->where('date', '<=', $this->endDateTime);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date_d_m_y',
            'Main_sku',
            'Sub_sku',
            'Size',
            'Supplier',
            'Description',
            'Location',
            'Status'
        ];
    }

    public function map($supplierproducts): array
    {
        return [
            Carbon::parse($supplierproducts->date)->format('d-m-Y'),
            $supplierproducts->sku,
            $supplierproducts->sub_sku,
            $supplierproducts->qty,
            $supplierproducts->name,
            $supplierproducts->description,
        ];
    }
}
