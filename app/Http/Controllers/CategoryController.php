<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(){
        $data = Category::all();
       return view('category.index',compact('data'));
      }

      public function info(){
         $brand=Brand::select('brand','id')->where('status',1)->get();
         return view('category.info',compact('brand'));
      }

      public function edit($id){
         $data=Category::find($id);
         $brand=Brand::select('brand','id')->where('status',1)->get();
         return view('category.edit',compact('data','brand'));
      }

      public function save(Request $req){
         if (Category::where('name', $req->name)->exists()) {
            return back()->with('error', 'Category Name is already exists.');
        }
         $data=new Category();
         $data->brand_id=$req->brand_id;
         $data->name=$req->name;
         if($req->hasfile('image'))
        {
            $file = $req->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/', $filename);
            $data->image = $filename;
        }
         $data->description=$req->description;
         $data->status=$req->status;
         $userId = Auth::user()->user_id;
      $data->user_id=$userId;
         if ($data->save()) {
            return redirect('category')->with('success', 'Category Saved Successfully');
         } else {
            return back()->with('error', 'Category not Saved');
         }
      }

      public function edit1(Request $req,$id){
         if (Category::where('id', '!=', $id)->where('name', $req->name)->exists()) {
            return back()->with('error', 'Category Name is already exists.');
        }
         $data=Category::find($id);
         $data->brand_id=$req->brand_id;
         $data->name=$req->name;
         if($req->hasfile('image'))
        {
         $oldImage = public_path( "../uploads/" . $data->image);
         if(file_exists($oldImage)){
              unlink($oldImage);
         }
            $file = $req->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move('uploads/', $filename);
            $data->image = $filename;
        }else {
         $data->image = $req->img;
        }
         $data->description=$req->description;
         $data->status=$req->status;
         $userId = Auth::user()->user_id;
      $data->user_id=$userId;
         if ($data->update()) {
            return redirect('category')->with('success', 'Category Updated Successfully');
         } else {
            return back()->with('error', 'Category not Updated');
         }
      }

     public function delete($id) {
    $data = Category::find($id);
    $product = Product::where('category_id', $id)->get();
    $path = public_path('../uploads/' . $data->image);
    if ($product->isEmpty()) {
        if(file_exists($path)){
            unlink($path);
            $userId = Auth::user()->user_id;
            $data->user_id = $userId;
            $data->delete();
            return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
        }else{
            $userId = Auth::user()->user_id;
            $data->user_id = $userId;
            $data->delete();
            return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
        }
        } else {
            return response()->json(['success' => false, 'message' => "You can't delete this Category."]);
    } 
        
    
}

      public function status($id){
         $data=Category::find($id);
         if ($data->status==1) {
            $data->status=0;
         }else {
            $data->status=1;
         }
           if ($data->update()) {
            return redirect('category')->with('success', 'Status Updated Successfully');
         } else {
            return back()->with('error', 'Status not Updated');
         }
      }
      
}