<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

  public function getDetail(Request $request, $id)
  {
    // $customer = Customer::where('id', $id)->where('user_id', auth()->user()->id)->first();
    // $invoiceList = $customer->invoices;
    // $balance = 0;
    // foreach( $invoiceList as $_inv){
    //   $balance += $_inv->balance;
    // }
    // return response()->json([
    // 	'status' => $customer ? 'success' : 'failed',
    // 	'customer' => $customer,
    //   'balance' => $balance
    // ]);
  }
  public function saveCategory(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json([
        'status' => 'failed'
      ]);
    } else {
      $catData = $request->all();
      unset($catData['api_token']);
      if(isset($catData['id'])){
        $id = $catData['id'];
        unset($catData['id']);

        Category::where('id', $id)->update([
          'name' => $catData['name'],
          'parent_id' => $catData['parent_id']
        ]);

        return response()->json([
          'status' => 'success',
          'category' => $id
        ]);
      } else {
        $category = Category::create($catData);
        
        return response()->json([
          'status' => 'success',
          'category' => $category->id,
        ]);
      }
    }
  }

  public function getCategories()
  {
    $cats = Category::all();
    // for($i=0; $i < count($cats); $i++) {
    //   $cats[$i]['subcats'] = $cats[$i]->subCategories;
    // }
    return response()->json([
      'status' => 'success',
      'categories' => $cats
    ]);
  }

  public function removeCategory (Request $request, $id)
  {
    Category::where('id', $id)->delete();
    Category::where('parent_id', $id)->update(['parent_id' => null]);
    Product::where('category_id', $id)->delete();
    return response()->json([
			'status' => 'success',
		]);
  }
}
