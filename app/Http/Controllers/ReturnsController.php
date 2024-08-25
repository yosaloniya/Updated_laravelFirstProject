<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use App\Models\Subsku;
use App\Models\Product;
use App\Models\Returns;
use App\Models\Customers;
use Illuminate\Http\Request;
use App\Exports\ReturnsExport;
use App\Imports\ReturnsImport;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // Import the DB facade

class ReturnsController extends Controller
{

  public function index(Request $req)
  {
    $sku = Product::select('*')->where('status', 1)->get();
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
      $data = Returns::whereBetween('date', [$start_date, $end_date]);
      $selected['start_date'] = $startDate;
      $selected['end_date'] = $endDate;
    } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
      $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
      $start_date = $start_date->format('Y-m-d');
      $data = Returns::whereDate('date', '>=', $start_date);
      $selected['start_date'] = $startDate;
    } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
      $end_date = DateTime::createFromFormat('d/m/Y', $end_date);
      $end_date = $end_date->format('Y-m-d');
      $data = Returns::whereDate('date', '<=', $end_date);
      $selected['end_date'] = $endDate;
    }

    if (empty($selected['start_date']) && empty($selected['end_date'])) {
      $data = Returns::all();
    } else {
      $data = $data->get();
    }

    return view('returns.index', compact('data', 'sku', 'customers', 'selected'));
  }

  public function checkexist(Request $request)
  {
    $data = Subsku::where(['product_id' => $request->main_sku, 'sku' => $request->sub_sku])->first();
    if ($data) {
      return 201;
    } else {
      $data = Returns::where(['m_sku' => $request->main_sku, 's_sku' => $request->sub_sku])->first();
      if ($data) {
        return 201;
      } else {
        return 200;
      }
    }
  }


  

public function save(Request $request)
{
    try {
        // Retrieve customer information
        $customer = Customers::select('name')->where('id', $request->customer_id)->first();

        // Check if Subsku or Returns with the same s_sku already exists
        $existingSubSku = Subsku::where('sku', $request->s_sku)->where('product_id', $request->m_sku)->first();
        $existingReturn = Returns::where('s_sku', $request->s_sku)->where('m_sku', $request->m_sku)->first();
        
        if ($existingSubSku || $existingReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Return not saved. Sub_sku already exists.'
            ]);
        } 

        // Create and save a new Return record
        $return = new Returns();
        $return->customer_id = $request->customer_id;
        $return->date = $request->date;
        $return->location = $request->location;
        $return->m_sku = $request->m_sku;
        $return->s_sku = $request->s_sku;
        $return->size = $request->qty;
        $return->user_id = Auth::user()->user_id;

        // Create and save a new Subsku record
        $subSku = new Subsku();
        $subSku->product_id = $request->m_sku;
        $subSku->sku = $request->s_sku;
        $subSku->sup_id = "Not Found";
        $subSku->qty = $request->qty;
        $subSku->date = $request->date;
        $subSku->description = "Return from " . $customer->name;
        $subSku->location = $request->location;
        $subSku->status = 1;
        $subSku->user_id = Auth::user()->user_id;
        $return->save(); // Save Return record
        $subSku->save(); // Save Subsku record

        return response()->json([
            'success' => true,
            'message' => 'Return saved successfully.'
        ]);

    } catch (\Throwable $th) {
    // Log exception details for debugging
    \Log::error('Error saving return: ' . $th->getMessage());

    // Check if the exception is a SQL exception
    if ($th instanceof \Illuminate\Database\QueryException) {
        // You can customize this message based on the SQL error
        $message = 'error occurred. Please check your input and try again.';
    } else {
        // Handle other types of errors
        $message = 'Return not saved. Error: ' . $th->getMessage();
    }

    return response()->json([
        'success' => false,
        'message' => $message
    ]);
   }
   
 }


  public function update(Request $request)
  {
      
    try {
        $customer = Customers::select('name')->where('id', $request->customer_id)->first();
        
         // Check if Subsku or Returns with the same s_sku already exists
        $existingReturn = Returns::where('id', '!=', $request->id)->where('s_sku', $request->s_sku)->where('m_sku', $request->m_sku)->first();
        
        if ($existingReturn) {
            return response()->json([
                'success' => false,
                'message' => 'Return not saved. Sub_sku already exists.'
            ]);
        } 
        
        
      $return = Returns::find($request->id);
      $return->customer_id = $request->customer_id;
      $return->date = $request->date;
      $return->location = $request->location;
      $return->m_sku = $request->m_sku;
      $return->s_sku = $request->s_sku;
      $return->size = $request->size;
      $userId = Auth::user()->user_id;
      $return->user_id = $userId;

      $subSku = Subsku::where('product_id', $request->m_sku) ->where('sku', $request->s_sku)->first();
      $subSku->product_id = $request->m_sku;
      $subSku->sku = $request->s_sku;
      $subSku->sup_id = "Not Found";
      $subSku->qty = $request->qty;
      $subSku->date = $request->date;
      $subSku->location = $request->location;
      $subSku->description = "Return from ".$customer->name;
      $subSku->status = 1;
      $userId = Auth::user()->user_id;
      $subSku->user_id = $userId;
      $return->update();
      $subSku->update();
      
      return response()->json([
            'success' => true,
            'message' => 'Return updated successfully.'
        ]);
      
    } catch (\Throwable $th) {
    // Log exception details for debugging
    \Log::error('Error updating return: ' . $th->getMessage());

    // Check if the exception is a SQL exception
    if ($th instanceof \Illuminate\Database\QueryException) {
        // You can customize this message based on the SQL error
        $message = 'error occurred. Please check your input and try again.';
    } else {
        // Handle other types of errors
        $message = 'Return not updated. Error: ' . $th->getMessage();
    }

    return response()->json([
        'success' => false,
        'message' => $message
    ]);
   }
  }
  
  public function delete($id){
    $return = Returns::find($id);
    $userId = Auth::user()->user_id;
    $return->user_id = $userId;
    $mSku = $return->m_sku;
    $sSku = $return->s_sku;
    $subSku = Subsku::where('product_id', $mSku)->where('sku', $sSku)->first();
    if($return->size == 0){
    if($subSku){
    $subSku->delete();
    $return->delete();
        return response()->json(['success'=>true, 'tr'=>'tr_'.$id]);
    }
    else{
        $return->delete();
        return response()->json(['success'=>true, 'tr'=>'tr_'.$id]);
    }
    }else{
        return response()->json([
            'success'=>false,
            'message'=>"You can't delete this Return."
            ]);
    }
  }
  


  public function edit($id)
  {
    $return = Returns::find($id);
    if ($return) {
      return response()->json($return);
    } else {
      return response()->json(['error' => 'Return not found'], 404);
    }
  }


  public function importExcelData(Request $request)
  {
    $request->validate([
      'import_file' => 'required|file',
    ]);
    try {
      Excel::import(new ReturnsImport, $request->file('import_file'));

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
    $customerId = $request->input('customer_id');
    $startDateTime = $request->start_date ? Carbon::parse($request->start_date) : null;
    $endDateTime = $request->end_date ? Carbon::parse($request->end_date) : null;

    try {
      $fileName = 'returns_' . ($startDateTime ? $startDateTime->format('d-m-Y') : 'all') . '-' . ($endDateTime ? $endDateTime->format('d-m-Y') : 'all') . '.xlsx';
      return Excel::download(new ReturnsExport($customerId, $startDateTime, $endDateTime), $fileName);
    } catch (Exception $e) {
      return redirect()->back()->with('error', 'Error exporting products: ' . $e->getMessage());
    }
  }
}
