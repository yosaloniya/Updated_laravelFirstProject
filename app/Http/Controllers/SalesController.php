<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Sales;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Customers;
use App\Exports\SalesExport;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{



    public function index(Request $req)
    {
        $customers = Customers::select('id', 'name')->where('status', 1)->get();
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
            $data = Sales::whereBetween('date', [$start_date, $end_date]);
            $selected['start_date'] = $startDate;
            $selected['end_date'] = $endDate;
        } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
            $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
            $start_date = $start_date->format('Y-m-d');
            $data = Sales::whereDate('date', '>=', $start_date);
            $selected['start_date'] = $startDate;
        } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
            $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
            $end_date = $end_date->format('Y-m-d');
            $data = Sales::whereDate('date', '<=', $end_date);
            $selected['end_date'] = $endDate;
        }

        if (empty($selected['start_date']) && empty($selected['end_date'])) {
            $data = Sales::all();
        } else {
            $data = $data->get();
        }

        foreach ($data as $product) {
            $product->product_id = unserialize($product->product_id);
        }
        return view('sales.index', compact('data', 'selected', 'customers'));
    }

    public function check(Request $request)
    {
        $data = Product::where('sku', $request->main_sku)->first();
        if ($data) {
            return 201;
        } else {
            return 200;
        }
    }

    public function info()
    {
        $customer = Customers::select('name', 'id')->where('status', 1)->get();
        $product = Product::select('name', 'id', 'sku')->where('status', 1)->get();
        return view('sales.info', compact('product', 'customer'));
    }

    public function getPrice(Request $req)
    {
        $data = Product::find($req->id);
        return $data;
    }

    public function price(Request $request)
    {
        $data = Subsku::where('id', $request->id)->where('status', 1)->get();
        return $data;
    }

    public function subsku(Request $req)
    {
        $id = $req->id;
        $subSku = Subsku::select('*')->where('product_id', $id)->where('status', 1)->get();
        if (count($subSku) != 0) {
            return $subSku;
        }
        return 201;
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
            $data = new Sales();
            $data->order_no = $req->order_no;
            $data->customer_id = $req->customer_id;
            $data->date = $req->date;
            $data->description = $req->description ?? '';
            $data->product_id = serialize($req->product);
            $userId = Auth::user()->user_id;
            $data->user_id = $userId;
            $pIds = [];
            $productArr = [];
            $pId = 0;

            foreach ($req->product as $p) {
                if (!empty($p["p_id"])) {
                    $pdata = Subsku::find($p['p_id']);
                    if ($pId == $p['p_id']) {
                        $pIds[$p['p_id']] -= $p['qty'];
                        continue;
                    }
                    $pId = $p['p_id'];
                    if ($p['qty'] <= $pdata["qty"]) {
                        $pqty = $pdata["qty"];
                        $pQty = $pqty - $p['qty'];
                        $pIds[$p['p_id']] = $pQty;
                    } else {
                        return "The Product quantity is invalid!";
                    }
                } 
            }
            

            if ($data->save()) {
                foreach ($pIds as $pId => $qty) {
                    $pData = Subsku::find($pId);
                    if ($pData) {
                        $pData->qty = $qty;
                        $pData->update();
                    }
                }
                foreach ($productArr as $sku => $qty) {
                    $pData = Product::where('sku', $sku)->first();
                    if ($pData) {
                        $pData->qty = $qty;
                        $pData->update();
                    }
                }
                return 'success';
            } else {
                return 'error';
            }
        } catch (Exception $e) {
            return ["php" => 123, "msg" => $e->getMessage()];
        }
    }

    public function edit($id)
    {
        $data = Sales::find($id);
        $product1 = unserialize($data->product_id);
        $customer = Customers::select('name', 'id')->where('status', 1)->get();
        $product = Product::select('name', 'id', 'sku')->where('status', 1)->get();
        $product2 = Subsku::select('*')->where('status', 1)->get();
        return view('sales.edit', compact('data', 'product', 'customer', 'product1', 'product2'));
    }

    public function update(Request $req)
    {
        try {
            $data = Sales::find($req->id);
            $data->order_no = $req->order_no;
            $data->customer_id = $req->customer_id;
            $data->description = $req->description ?? '';
            $data->product_id = serialize($req->product);
            $userId = Auth::user()->user_id;
            $data->user_id = $userId;
            $pIds = [];
            $productArr = [];
            foreach ($req->product as $p) {
                if (!empty($p["p_id"])) {
                    $pdata = Subsku::find($p['p_id']);
                    if (abs($p['newQty']) <= $pdata["qty"]) {
                        $pqty = $pdata["qty"];
                        $pQty = $pqty + $p['newQty'];
                        $pIds[$p['p_id']] = $pQty;
                    } elseif ($p['old_qty'] != 0 && $p['old_qty'] >= $p['qty']){
                        $pIds[$p['p_id']] = $p['newQty'];
                    } else {
                        return "Low stock for this product!";
                    }
                } else {
                    $products = Product::where('sku', $p['sku'])->first();
                    if ($products && $products["qty"] >= abs($p['newQty'])) {
                        $pqty = $products["qty"] + $p['newQty'];
                        $productArr[$p['sku']] = $pqty;
                    } else {
                        return "Low stock for this product!";
                    }
                }
            }
            $data->date = $req->date;
            if ($data->update()) {
                foreach ($pIds as $pId => $qty) {
                    $pData = Subsku::find($pId);
                    if ($pData) {
                        $pData->qty = $qty;
                        $pData->update();
                    }
                }
                foreach ($productArr as $sku => $qty) {
                    $pData = Product::where('sku', $sku)->first();
                    if ($pData) {
                        $pData->qty = $qty;
                        $pData->update();
                    }
                }
                return 'success';
            } else {
                return 'error';
            }
        } catch (Exception $e) {
            return ["php" => 123, "msg" => $e->getMessage()];
        }
    }

    public function delete($id)
    {
        try {
            $data = Sales::find($id);
            $product = unserialize($data->product_id);

            foreach ($product as $p) {
                if (!empty($p["p_id"])) {
                    $pdata = Subsku::find($p['p_id']);
                    if ($pdata) {
                        $pdata->qty += abs($p["qty"]);
                        $pdata->update();
                    }
                } else {
                    $products = Product::where('sku', $p['sku'])->first();
                    if ($products) {
                        $products->qty += abs($p["qty"]);
                        $products->update();
                    }
                }
            }
            $userId = Auth::user()->user_id;
            $data->user_id = $userId;
            $data->delete();
            return response()
            ->json([
                'success'=>true,
                'tr'=>'tr_'.$id
                ]);
        } catch (\Throwable $th) {
            return ["code" => 201, "message" => $th->getMessage()];
        }
    }

    public function pdf($id)
    {
        $data = Sales::find($id);
        $product = unserialize($data->product_id);
        return view('pdf.index-3', compact('data', 'product'));
    }
    /**
     * Import sales data from uploaded Excel file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function importExcelData(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        try {
            Excel::import(new SalesImport, $request->file('import_file'));

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
            'product_id' => 'nullable|integer', // Adjust based on your actual requirements
        ]);

        // Retrieve validated inputs
        $customerId = $request->input('customer_id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        try {
            // Define file name based on date range and product ID
            $fileName = 'orders_' . ($startDate ? $startDate->format('d-m-Y') : 'all') . '-' . ($endDate ? $endDate->format('d-m-Y') : 'all') . '.xlsx';

            // Generate and return Excel download response
            return Excel::download(new SalesExport($customerId, $startDate, $endDate), $fileName);
        } catch (Exception $e) {
            // Handle any exceptions that occur during export
            return redirect()->back()->with('error', 'Error exporting orders: ' . $e->getMessage());
        }
    }
}
