<?php

namespace App\Http\Controllers;

use App\Models\Subsku;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SupplierController extends Controller
{
    public function index()
    {
        $data = Supplier::all();
        return view('suppliers.index', compact('data'));
    }

    public function info(Request $request)
    {
        $data = new Supplier();
        $data->invoice_no = $request->invoice_no;
        $data->name = $request->name;
        $data->contact = $request->contact;
        $data->description = $request->description;
        $data->status = $request->status;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;
        if ($data->save()) {
            return back()->with('success', 'Supplier Saved Successfully');
        } else {
            return back()->with('error', 'Supplier not Saved');
        }
    }

    public function data(Request $request)
    {
        $sup = Supplier::find($request->id);
        return $sup;
    }

    public function update(Request $request)
    {
        if (Supplier::where('id', '!=', $request->id)->where('name', $request->name)->exists()) {
            return back()->with('error', 'Supplier name already exists');
        }

        $data = Supplier::find($request->id);
        $data->invoice_no = $request->invoice_no;
        $data->name = $request->name;
        $data->contact = $request->contact;
        $data->description = $request->description;
        $data->status = $request->status;
        $userId = Auth::user()->user_id;
        $data->user_id = $userId;
        if ($data->update()) {
            return back()->with('success', 'Supplier Updated Successfully');
        } else {
            return back()->with('error', 'Supplier not Updated');
        }
    }

    public function delete($id)
    {
        $data = Supplier::find($id);
        $supIdProduct = Product :: where('sup_id', $id)->get();
        $supIdSubsku = Subsku :: where('sup_id', $id)->get();
        $userId = Auth::user()->user_id;
        if($supIdProduct->isEmpty() && $supIdSubsku->isEmpty()){
        $data->user_id = $userId;
        $data->delete();
        return response()
        ->json([
            'success'=>true,
            'tr'=>'tr_'.$id
            ]);
        } else{
            return response()->json([
                'success'=>false,
                'message'=>"You can't delete this Supplier."
                ]);
        }
    }

    public function status($id)
    {
        
        $data = Supplier::find($id);
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
    public function sup_products(Request $req)
    {
        $data = DB::table('products')
            ->join('subskus', 'subskus.product_id', '=', 'products.id')
            ->select('products.sku as psku', 'subskus.*')
            ->where("subskus.sup_id", $req->id)
            ->get();
        // $data = Subsku::where("sup_id", $req->id)
        //         ->select('*')
        //         ->without(['product_id', 'id'])
        //         ->get();

        return $data;
    }
}
