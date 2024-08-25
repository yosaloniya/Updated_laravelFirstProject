<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Supplierproduct;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierproductsExport;
use App\Imports\SupplierproductsImport;
// use Illuminate\Support\Facades\Session;

class SupplierproductController extends Controller
{

  public function index(Request $req)
  {
    $suppliers = Supplier::select('name', 'id')->where('status', 1)->get();
    $start_date = $req['start_date'];
    $end_date = $req['end_date'];
    $startDate = $req['start_date'];
    $endDate = $req['end_date'];
    $selected = ['start_date' => '', 'end_date' => ''];

    if (isset($start_date) && isset($end_date) && !empty($start_date) && !empty($end_date)) {
      $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
      $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
      $start_date = $start_date->format('Y-m-d');
      $end_date = $end_date->format('Y-m-d');
      $data = Supplierproduct::whereBetween('date', [$start_date, $end_date]);
      $selected['start_date'] = $startDate;
      $selected['end_date'] = $endDate;
    } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
      $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
      $start_date = $start_date->format('Y-m-d');
      $data = Supplierproduct::whereDate('date', '>=', $start_date);
      $selected['start_date'] = $startDate;
    } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
      $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
      $end_date = $end_date->format('Y-m-d');
      $data = Supplierproduct::whereDate('date', '<=', $end_date);
      $selected['end_date'] = $endDate;
    }

    if (empty($selected['start_date']) && empty($selected['end_date'])) {
      $data = Supplierproduct::all();
    } else {
      $data = $data->get();
    }
    return view('supplierproduct.index', compact('data', 'selected', 'suppliers'));
  }
  public function info()
  {
    $product = Product::select('name', 'sku', 'id')->where('status', 1)->get();
    $product1 = Subsku::select('sku', 'id')->where('status', 1)->get();
    $supplier = Supplier::select('name', 'id')->where('status', 1)->get();
    return view('supplierproduct.info', compact('product', 'supplier', 'product1'));
  }
  
  public function subskuInactive(Request $req)
    {
        $id = $req->id;
        $subSku = Subsku::select('*')->where('product_id', $id)->get();
        if (count($subSku) != 0) {
            return $subSku;
        }
        return 201;
    }
  
  
  public function save(Request $req)
{
    try {
        // Create a new Supplierproduct instance and set its attributes
        $product = Subsku::where("sku", $req->sub_sku)
                         ->where("product_id", $req->id)
                         ->first();
                         if($req->location){
                             $location = $req->location;
                         } else{
                             $location = $product->location;
                         }
                         
                         if($req->status){
                             $status = $req->status;
                         } else{
                             $status = $product->status;
                         }
        $data = new Supplierproduct();
        $data->product_id = $req->id;
        $data->sup_id = $req->sup_id;
        $data->sku = $req->sku;
        $data->sub_sku = $req->sub_sku;
        $data->date = $req->date;
        $data->qty = $req->qty;
        $data->location = $location;
        $data->description = $req->description;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;

        // Find the Subsku record matching both sku and product_id
                         
        // Check if the product exists
        if ($product) {
            $qty = $product->qty;
            $qty += $req->qty;

            // Save the new Supplierproduct record
            if ($data->save()) {
                // Update the Subsku record
                Subsku::where("sku", $req->sub_sku)
                        ->where("product_id", $req->id)
                         ->update([
                                   "qty" => $qty,
                                   "location" => $location, // Add more columns here
                                   "status" => $status
      ]);

                return "success";
            } else {
                return "error";
            }
        } else {
            return ["php" => 123, "message" => "Product not found"];
        }
    } catch (\Throwable $th) {
        return ["php" => 123, "message" => $th->getMessage()];
    }
}


  public function importExcelData(Request $request)
  {
    $request->validate([
      'import_file' => 'required|file',
    ]);
    try {
      Excel::import(new SupplierproductsImport, $request->file('import_file'));

      // If import is successful, redirect with success message
      return redirect()->back(); // 3000 milliseconds = 3 seconds timeout for alert
    } catch (Exception $e) {
      // If import fails, redirect back with error message and a timeout alert
      return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
    }
  }

  public function exportDataExcelFile(Request $request)
  {
    $request->validate([
      'start_date' => 'nullable|date',
      'end_date' => 'nullable|date',
      'sup_id' => 'nullable|integer'
    ]);
    $supId = $request->input('sup_id');
    $startDateTime = $request->start_date ? Carbon::parse($request->start_date) : null;
    $endDateTime = $request->end_date ? Carbon::parse($request->end_date) : null;

    try {
      $fileName = 'supplierproducts_' . ($startDateTime ? $startDateTime->format('d-m-Y') : 'all') . '-' . ($endDateTime ? $endDateTime->format('d-m-Y') : 'all') . '.xlsx';
      return Excel::download(new SupplierproductsExport($supId, $startDateTime, $endDateTime), $fileName);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
    }
  }
}
