<?php

namespace App\Http\Controllers;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Subsku;
use App\Models\Customers;
use Illuminate\Http\Request;
use App\Imports\CustomersImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CustomersController extends Controller
{
   public function index(){
     $data = customers::all();
    return view('customers.index',compact('data'));
   }

   
   public function info(Request $request){
      if (Customers::where('name', $request->name)->exists()) {
         return back()->with('error', 'Customer name already exists');
     }
      $data = new Customers();
      $data->name=$request->name;
      $data->contact=$request->contact;
      $data->address=$request->address;
      $data->status=$request->status;
      $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      if ($data->save()) {
         return back()->with('success', 'Customer Saved Successfully');
      } else {
         return back()->with('error', 'Customer not Saved');
      }
   }

   public function customersorder($id){
      $data = Sales::where('customer_id',$id)->get();
      return view('customers.orders',compact('data'));
   }

   public function status($id){
      $data = Customers::find($id);
      if ($data->status==1) {
         $data->status=0;
      } else {
         $data->status=1;
      }
      if ($data->update()) {
         return back()->with('success', 'Status Updated Successfully');
      } else {
         return back()->with('error', 'Status not Updated');
      }
   }

   public function data(Request $request){
     $customer = Customers::find($request->id);
     return $customer;
   }


   public function update(Request $request){
      if (Customers::where('id', '!=', $request->id)->where('name', $request->name)->exists()) {
         return back()->with('error', 'Customer name already exists');
     }
      $data = Customers::find($request->id);
      $data->name=$request->name;
      $data->contact=$request->contact;
      $data->address=$request->address;
      $data->status=$request->status;
      $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      if ($data->update()) {
         return back()->with('success', 'Customer Updated Successfully');
      } else {
         return back()->with('error', 'Customer not Updated');
      }
   }

   public function delete($id){
      $data = Customers::find($id);
      $sales = Sales:: where('customer_id', $id)->get();
      $userId = Auth::user()->user_id;
      if($sales->isEmpty()){
      $data->user_id=$userId;
      $data->delete();
      return response()->json(['success'=>true, 'tr'=>'tr_'.$id]);
      } else{
          return response()
          ->json([
              'success'=>false,
              'message'=>"You can't delete this Customer."
              ]);
      }
   }
   
   public function customerdatainfo(Request $req){
    //   $data = Sales::all();
      $data = sales::where('customer_id',$req->id)->get();
      return $data;
   }
   public function product(Request $req) {
    try {
        $data = Sales::where('order_no', $req->id)->first('product_id');

        $response = ['status' => 201];

        if ($data) {
            $response['status'] = 200;
            $product_ids = unserialize($data->product_id) ?? [];

            $products = [];
            foreach ($product_ids as $product_id) {
                $product = Product::select('sku')->where('id', $product_id['sku'])->first();
                $subsku = Subsku::select('sku')->where('id', $product_id['sub_sku'])->first();

                $products[] = [
                    'sku' => $product->sku ?? null,
                    'sub_sku' => $subsku->sku ?? null,
                    'qty' => $product_id['qty'] ?? null,
                ];
            }

            $response['data'] = $products;
        }

        return response()->json($response);
    } catch (\Exception $e) {
        \Log::error('Error fetching product data: ' . $e->getMessage());
        return response()->json(['status' => 500, 'message' => 'Internal Server Error']);
    }
}

}
