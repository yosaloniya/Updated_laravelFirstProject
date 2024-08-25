<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Returns;
use App\Models\Sales;
use App\Models\Subsku;
use App\Models\Supplier;
use App\Models\Supplierproduct;
use App\Models\User;
use Illuminate\Http\Request;
use DateTime;

class HistoryController extends Controller
{
   public function index(Request $req)
   {
      $destination = $req['destination'];
      $type = $req['type'];
      $user = $req['user'];
      $start_date = $req['start_date'];
      $end_date = $req['end_date'];
      $selected = ["dest" => 9, 'type' => 3, 'user' => 0, 'start_date' => '', 'end_date' => ''];

      // Handle the destination and type conditions
      switch ($destination) {
         case 1:
            $data = $this->getBrandData($type);
            break;
         case 2:
            $data = $this->getCategoryData($type);
            break;
         case 3:
            $data = $this->getProductData($type);
            break;
         case 4:
            $data = $this->getReturnsData($type);
            break;
         case 5:
            $data = $this->getSalesData($type);
            break;
         case 6:
            $data = $this->getCustomersData($type);
            break;
         case 7:
            $data = $this->getSupplierProductData($type);
            break;
         case 8:
            $data = $this->getSupplierData($type);
            break;
         default:
            $data = $this->getSubskuData($type);
            break;
      }

      // Handle the date conditions
      $data = $this->handleDateConditions($data, $start_date, $end_date, $type);

      // Handle the user condition
      if (isset($user) && !empty($user)) {
         $selected['user'] = $user;
         $data = $data->where('user_id', $user)->get();
      } else {
         $data = $data->get();
      }

      // Extract the required fields
      foreach ($data as $item) {
         switch ($destination) {
            case 1:
               $item->item_detail = $item->brand;
               break;
            case 2:
               $item->item_detail = $item->name;
               break;
            case 3:
               $item->item_detail = $item->sku;
               break;
            case 4:
               $item->item_detail = $this->getProductSku($item->m_sku) . ' / ' . $item->s_sku;
               break;
            case 5:
               $item->item_detail = "Order no. ". "- ".$item->order_no;
               break;
            case 6:
               $item->item_detail = $item->name;
               break;
            case 7:
               $item->item_detail = $item->sku . ' / ' . $item->sub_sku;
               break;
            case 8:
               $item->item_detail = $item->name;
               break;
            default:
               $item->item_detail = $this->getProductSku($item->product_id) . " / " . $item->sku;
               break;
         }
      }

      // Update the selected fields
      if (isset($destination) && !empty($destination)) {
         $selected['dest'] = $destination;
      }
      if (isset($type) && !empty($type)) {
         $selected['type'] = $type;
      }

      $users = User::all();
      return view('history.index', compact('data', 'users', 'selected'));
   }
   
   private function getProductSku($productId)
   {
      $product = Product::find($productId);
      return $product ? $product->sku : null;
   }
   

   private function getBrandData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Brand::select('id', 'brand', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Brand::select('id', 'brand', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Brand::select('id', 'brand', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getCategoryData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Category::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Category::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Category::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getProductData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Product::select('id', 'sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Product::select('id', 'sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Product::select('id', 'sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getReturnsData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Returns::select('id', 'm_sku', 's_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Returns::select('id', 'm_sku', 's_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Returns::select('id', 'm_sku', 's_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getSalesData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Sales::select('id', 'order_no', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Sales::select('id', 'order_no', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Sales::select('id', 'order_no', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getCustomersData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Customers::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Customers::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Customers::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getSupplierProductData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Supplierproduct::select('id', 'sku', 'sub_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Supplierproduct::select('id', 'sku', 'sub_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Supplierproduct::select('id', 'sku', 'sub_sku', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getSupplierData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Supplier::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Supplier::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Supplier::select('id', 'name', 'user_id', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function getSubskuData($type) {
      if (isset($type) && !empty($type) && $type == 1) {
         return Subsku::select('id', 'product_id', 'user_id', 'sku', 'created_at', 'updated_at', 'deleted_at')->where('created_at', '!=', null)->withTrashed();
      } else if (isset($type) && !empty($type) && $type == 2) {
         return Subsku::select('id', 'product_id', 'user_id', 'sku', 'created_at', 'updated_at', 'deleted_at')->whereColumn('created_at', '!=', 'updated_at')->withTrashed();
      } else {
         return Subsku::select('id', 'product_id', 'user_id', 'sku', 'created_at', 'updated_at', 'deleted_at')->onlyTrashed();
      }
   }

   private function handleDateConditions($data, $start_date, $end_date, $type)
   {
      if (isset($start_date) && isset($end_date) && !empty($start_date) && !empty($end_date)) {
         $startDate = DateTime::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
         $endDate = DateTime::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
         if (isset($type) && !empty($type) && $type == 1) {
            return $data->whereBetween('created_at', [$startDate, $endDate]);
         } else if (isset($type) && !empty($type) && $type == 2) {
            return $data->whereBetween('updated_at', [$startDate, $endDate]);
         } else {
            return $data->whereBetween('deleted_at', [$startDate, $endDate]);
         }
      } else if (isset($start_date) && !empty($start_date) && empty($end_date)) {
         $startDate = DateTime::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
         if (isset($type) && !empty($type) && $type == 1) {
            return $data->whereDate('created_at', '>=', $startDate);
         } else if (isset($type) && !empty($type) && $type == 2) {
            return $data->whereDate('updated_at', '>=', $startDate);
         } else {
            return $data->whereDate('deleted_at', '>=', $startDate);
         }
      } else if (isset($end_date) && !empty($end_date) && empty($start_date)) {
         $endDate = DateTime::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');
         if (isset($type) && !empty($type) && $type == 1) {
            return $data->whereDate('created_at', '<=', $endDate);
         } else if (isset($type) && !empty($type) && $type == 2) {
            return $data->whereDate('updated_at', '<=', $endDate);
         } else {
            return $data->whereDate('deleted_at', '<=', $endDate);
         }
      }
      return $data;
   }
}
