<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Dotenv\Exception\ValidationException;
use App\Exports\ProductExport; // Create this export class


class ProductController extends Controller
{
  public function index(Request $req)
  {
      $suppliers = Supplier::select('name', 'id')->where('status',1)->get();
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
          $data = Product::whereBetween('date', [$start_date, $end_date]);
          $selected['start_date'] = $startDate;
          $selected['end_date'] = $endDate;
      } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
          $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
          $start_date = $start_date->format('Y-m-d');
          $data = Product::whereDate('date', '>=', $start_date);
          $selected['start_date'] = $startDate;
      } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
          $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
          $end_date = $end_date->format('Y-m-d');
          $data = Product::whereDate('date', '<=', $end_date);
          $selected['end_date'] = $endDate;
      }

      if(empty($selected['start_date']) && empty($selected['end_date'])) {
          $data = Product::all();
      } else {
          $data = $data->get();
      }

      return view('product.index', compact('data', 'selected', 'suppliers'));
  }

  public function info()
  {
    $supplier = Supplier::select('id','name')->where('status',1)->get();
    $category = Category::select('name', 'id')->where('status', 1)->get();
    return view('product.info', compact('category','supplier'));
  }

  public function save_multiple(Request $req)
  {

    try {
      $data = new Product();
      $data->category_id = $req->category_id;
      $data->name = $req->name;
      $data->sku = $req->sku;
      $data->sup_id = $req->sup_id;

      if ($req->hasfile('image')) {
        $file = $req->file('image');
        $extenstion = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extenstion;
        $file->move('uploads/', $filename);
        $data->image = $filename;
      }
      $data->description = $req->description;
      $data->status = $req->status;
      $userId = Auth::user()->user_id;
      $data->user_id=$userId;
      $data->save();
      return 'success';
    } catch (\Throwable $th) {
      return json_encode(['st' => 400, 'error' => $th->getMessage()]);
    }
  }

//   public function save(Request $req)
//   {
//     $data = new Product();
//     $data->category_id = $req->category_id;
//     $data->name = $req->name;
//     $data->sku = $req->sku;
//     $data->sup_id = $req->sup_id;

//     if ($req->hasfile('image')) {
//       $file = $req->file('image');
//       $extenstion = $file->getClientOriginalExtension();
//       $filename = time() . '.' . $extenstion;
//       $file->move('uploads/', $filename);
//       $data->image = $filename;
//     }
//     $data->description = $req->description;
//     $data->status = $req->status;
//     $userId = Auth::user()->user_id;
//       $data->user_id=$userId;
//     if ($data->save()) {
//       return redirect('products')->with('success', 'Product Saved Successfully');
//     } else {
//       return back()->with('error', 'Product not Saved');
//     }
//   }

  public function edit($id)
  {
    $data = Product::find($id);
    $supplier = Supplier::select('name','id')->where('status', 1)->get();
    $category = Category::select('name', 'id')->where('status', 1)->get();
    return view('product.edit', compact('data', 'category', 'supplier'));
  }

  public function edit1(Request $req, $id)
  {
    $data = Product::find($id);
    $data->category_id = $req->category_id;
    $data->date = $data->date;
    $data->name = $req->name;
    $data->sku = $req->sku;
    $data->sup_id = $req->sup_id;

    if ($req->hasfile('image')) {
        $oldImage = public_path( "../uploads/" . $data->image);
        if(file_exists($oldImage)){
          unlink($oldImage);
        }
      $file = $req->file('image');
      $extenstion = $file->getClientOriginalExtension();
      $filename = time() . '.' . $extenstion;
      $file->move('uploads/', $filename);
      $data->image = $filename;
    } else {
      $data->image = $req->img;
    }
    $data->description = $req->description;
    $data->status = $req->status;
    $userId = Auth::user()->user_id;
      $data->user_id=$userId;
    if ($data->update()) {
      return redirect('products')->with('success', 'Product Updated Successfully');
    } else {
      return back()->with('error', 'Product not Updated');
    }
  }

  public function delete($id)
  {
    $data = Product::find($id);
    $data1 = Subsku::where('product_id', $id)->get();
    $path = public_path( "../uploads/" . $data->image);
      if ($data1->isEmpty()) {
          if(file_exists($path)){
             unlink($path); 
              $userId = Auth::user()->user_id;
             $data->user_id=$userId;
             $data->delete();
             return response()
             ->json([
                 'success'=>true,
                 'tr'=>'tr_'.$id
                  ]);
          }else{
              $userId = Auth::user()->user_id;
             $data->user_id=$userId;
             $data->delete();
             return response()
             ->json([
                 'success'=>true,
                 'tr'=>'tr_'.$id
                  ]);
          }
      } else {
        return response()
        ->json([
            'success'=>false,
            'message' => "You can't delete this product"
            ]);
        }
 
   }


  public function status($id)
  {
    $data = Product::find($id);
    if ($data->status == 1) {
      $data->status = 0;
    } else {
      $data->status = 1;
    }
    if ($data->update()) {
      return redirect('products')->with('success', 'Status Updated Successfully');
    } else {
      return back()->with('error', 'Status not Updated');
    }
  }

  public function data(Request $req)
  {
    $data = Subsku::where('product_id', $req->id)->get();
    return $data;
  }

  public function low_stock()
  {
    $data = DB::table('products')
      ->join('subskus', 'subskus.product_id', '=', 'products.id')
      ->select('products.sku as psku', 'subskus.*')
      ->where('qty', '<', 25)
      ->get();
    //   $data = Subsku::where('qty', '<', 25)
    //                 ->select('*')
    //                 ->without(['product_id', 'id'])
    //                 ->get();
    return $data;
  }
  public function importExcelData(Request $request)
{
    $request->validate([
        'import_file' => 'required|file',
    ]);

    try {
      Excel::import(new ProductsImport, $request->file('import_file'));

      // If import is successful, redirect with success message
      return redirect()->back(); // 3000 milliseconds = 3 seconds timeout for alert
  }  catch (Exception $e) {
      // If import fails, redirect back with error message and a timeout alert
      return redirect()->back()->with('error', json_decode($e->getMessage()));
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
      $fileName = 'products_' . ($startDateTime ? $startDateTime->format('d-m-Y') : 'all') . '-' . ($endDateTime ? $endDateTime->format('d-m-Y') : 'all') . '.xlsx';
      return Excel::download(new ProductExport($supId, $startDateTime, $endDateTime), $fileName);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
    }
  }
}
