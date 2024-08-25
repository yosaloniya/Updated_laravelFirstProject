<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subsku;
use App\Models\Supplier;
use App\Models\Customers;
class MasterController extends Controller
{
    public static function getbrandname($id){
        $data=Brand::find($id);
        return $data->brand??'';
    }
    public static function getcategoryname($id){
        $data=Category::find($id);
        return $data->name??'';
    }
    public static function getsupplierName($id){
        $data=Supplier::find($id);
        return $data->name??'Not Found';
    }
    public static function getproductname($id){
        $data=Product::find($id);
        return $data->name??'';
    }
    public static function getproductname2($id){
        $data=Product::find($id);
        return $data->name??'';
    }
    public static function getproductname1($id){
        $data=Subsku::find($id);
        return $data->name??false;
    }
    public static function getsubskuqty($id)
{
    $data = Subsku::where("product_id", $id)->get();
    $qtys = 0;

    if (!empty($data)) {
        foreach ($data as $d) {
            // Ensure $d->qty is numeric before adding it to $qtys
            if (is_numeric($d->qty)) {
                $qtys += $d->qty;
            }
        }
    }

    return $qtys;
}
    

    public static function getproductnamemainsku($id){
        $data=Product::find($id);
        return $data->sku??'Not Found';
    }
    
    public static function getproductnamesubsku($id){
        $data=Subsku::find($id);
        return $data->sku??'Not Found';
    }
    public static function getproductnamesku($id){
        $data=Subsku::find($id);
        return $data->sku??'Not Found';
    }
    
    public static function getbrandname1($id){
        $data = Category::find($id);
        $brand = Brand::find($data->brand_id??'');
        return $brand->brand??'';
    }
    
    public static function getcustomername($id){
        $data = Customers::find($id);
        return $data->name??'';
    }
    
    public static function getcustomername1($id){
        $data = Customers::find($id);
        return ["name" => $data->name, "address" => $data->address]??'';
    }
   

}
