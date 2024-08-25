<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
   public function index(){
     $data=Brand::all();
    return view('brand.index',compact('data'));
   }

   public function info(){
      return view('brand.info');
   }

   public function edit($id){
      $data=Brand::find($id);
      return view('brand.edit',compact('data'));
   }

   public function save(Request $req){
      $data=new Brand();
      $data->brand=$req->brand;
      $data->description=$req->description;
      $data->status=$req->status;
      $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      if ($data->save()) {
         return redirect('brand')->with('success', 'Brand Saved Successfully');
      } else {
         return back()->with('error', 'Brand not Saved');
      }
   }
   
   public function edit1(Request $req,$id){
      $data=Brand::find($id);
      $data->brand=$req->brand;
      $data->description=$req->description;
      $data->status=$req->status;
      $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      if ($data->update()) {
         return redirect('brand')->with('success', 'Brand Updated Successfully');
      } else {
         return back()->with('error', 'Brand not Updated');
      }
   }

   public function delete($id){
      $data=Brand::find($id);
      $categories = Category::where('brand_id', $id)->get();
      if($categories->isEmpty()){
          $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      $data->delete();
      return response()->json(['success'=>true, 'tr'=>'tr_'.$id]);
      } else{
          return response()->json(['success'=>false, 'message'=>"You can't delete this Brand."]);
      }
   }
public function status($id){
   $data=Brand::find($id);
   if ($data->status==1) {
      $data->status=0;

   }else {
      $data->status=1;
   }
     if ($data->update()) {
         return back ()->with('success', 'Status Updated Successfully');
      } else {
         return back()->with('error', 'Status not Updated');
      }
}
}
