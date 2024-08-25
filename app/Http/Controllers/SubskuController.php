<?php

namespace App\Http\Controllers;

use Session;

use DateTime;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Sales;
use Illuminate\Http\Request;
use App\Exports\SubskuExport;
use App\Imports\SubskuImport;
use Illuminate\Support\Carbon;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SubskuController extends Controller
{
    public function info($id)
    {
        $data['id'] = $id;
        $supplier = Supplier::select('name', 'id')->where('status', 1)->get();
        $product = Product::select('sup_id')->where(['id' => $id])->first();
        $data['sup_id'] = $product->sup_id;
        return view('subsku.info', compact('supplier', 'data'));
    }
    public function save(Request $req, $id = '')
    {
        // return $req->all();
        $data = new Subsku();
        $data->product_id = $req->p_id;
        $data->sup_id = $req->sup_id;
        $data->sku = $req->sku;
        $data->qty = $req->qty;
        $data->description = $req->description;
        $data->status = $req->status;
        $data->location = $req->location;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;
        if ($req->qty <= 0) {
            return 201;
        } else {
            if ($data->save()) {
                return 200;
            } else {
                return 400;
            }
        }
    }



    public function index(Request $req, $id)
    {
        $suppliers = Supplier::select('name', 'id')->where('status', 1)->get();
        $skus = product::select('sku', 'id')->where('id', $id)->get();
        $products = Product::all();
        $data = Subsku::where('product_id', $id);

        $start_date = $req['start_date'];
        $end_date = $req['end_date'];
        $startDate = $req['start_date'];
        $endDate = $req['end_date'];
        $selected = ['start_date' => '', 'end_date' => '', 'product_id' => $id];

        if (isset($start_date) && isset($end_date) && !empty($start_date) && !empty($end_date)) {
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $start_date = $start_date->format('Y-m-d');
            $end_date = $end_date->format('Y-m-d');
            $data = $data->whereBetween('date', [$start_date, $end_date]);
            $selected['start_date'] = $startDate;
            $selected['end_date'] = $endDate;
        } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $start_date = $start_date->format('Y-m-d');
            $data = $data->whereDate('date', '>=', $start_date);
            $selected['start_date'] = $startDate;
        } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $end_date = $end_date->format('Y-m-d');
            $data = $data->whereDate('date', '<=', $end_date);
            $selected['end_date'] = $endDate;
        }

        $data = $data->get();
        return view('subsku.index', compact('data', 'id', 'products', 'skus', 'selected', 'suppliers'));
    }
public function delete($id)
{
    $data = Subsku::find($id);
    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'Subsku not found'
        ]);
    }

    $sales = Sales::all();
    $canDelete = true;

    foreach ($sales as $sale) {
        $product_ids = unserialize($sale->product_id);
        
        // Check if any deserialized product_id's p_id matches the given $id
        foreach ($product_ids as $product) {
            if (isset($product['p_id']) && $product['p_id'] == $id) {
                // Check if deleted_at is empty
                if (empty($sale->deleted_at)) {
                    $canDelete = false; // Cannot delete if deleted_at is empty
                }
                break 2; // Exit both loops
            }
        }
    }

    $userId = Auth::user()->user_id;

    if ($canDelete && $data->qty == 0) {
        $data->user_id = $userId;
        $data->delete();
        return response()->json([
            'success' => true,
            'tr' => 'tr_' . $id
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => "You can't delete this product"
        ]);
    }
}



    public function edit($id)
    {
        $data = Subsku::find($id);
        $supplier = Supplier::select('name', 'id')->where('status', 1)->get();
        return view('subsku.edit', compact('data', 'supplier'));
    }
    public function edit1(Request $req, $id)
    {
        $data = Subsku::find($id);
        $data->product_id = $req->product_id;
        $data->sup_id = $req->sup_id;
        $data->sku = $req->sku;
        $data->qty = $req->qty;
        $data->status = $req->status;
        $data->description = $req->description;
        $data->location = $req->location;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;
        if ($req->qty>=0){
            if ($data->update()) {
                return redirect('subsku/' . $data->product_id)->with('success', 'Sub-SKU Updated Successfully');
            } else {
                return back()->with('error', 'Sub-SKU not Updated');
            }
        } else{
            return back()->with('error', 'Sub-SKU not Updated');
        }
        
    }
    public function status($id)
    {
        $data = Subsku::find($id);
        if ($data->status == 1) {
            $data->status = 0;
        } else {
            $data->status = 1;
        }
        if ($data->update()) {
            return back()->with('success', 'Status Updated Successfully');
        } else {
            return back()->with('error', 'Status not Updated');
        }
    }

    public function checksame(Request $request)
    {
        $data = Subsku::where(['product_id' => $request->main_sku, 'sku' => $request->sub_sku])->first();
        if ($data) {
            return 201;
        } else {
            return 200;
        }
    }

    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file' => [
                'required',
                'file'
            ],
        ]);
        try {
            Excel::import(new SubskuImport, $request->file('import_file'));

            // If import is successful, redirect with success message
            return redirect()->back(); // 3000 milliseconds = 3 seconds timeout for alert
        } catch (Exception $e) {
            // If import fails, redirect back with error message and a timeout alert
            return redirect()->back()->with('error', explode(' ', $e->getMessage()));
        }
    }


    public function exportDataExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'product_id' => 'nullable|integer', // Adjust based on your actual requirements
            'sup_id' => 'nullable|integer', // Adjust based on your actual requirements
        ]);

        // Retrieve validated inputs
        $supId = $request->input('sup_id');
        $productId = $request->input('product_id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        try {
            // Define file name based on date range and product ID
            $fileName = 'subsku_' . ($startDate ? $startDate->format('d-m-Y') : 'all') . '-' . ($endDate ? $endDate->format('d-m-Y') : 'all') . '.xlsx';

            // Generate and return Excel download response
            return Excel::download(new SubskuExport($supId, $productId, $startDate, $endDate), $fileName);
        } catch (Exception $e) {
            // Handle any exceptions that occur during export
            return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
        }
    }

}
