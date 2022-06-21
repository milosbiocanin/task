<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\History;

class ProductController extends Controller
{

  public function saveProduct(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'price' => 'required'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'failed'
      ]);
    } else {
      $productData = $request->all();
      if(isset($productData['id'])){
        $id = $productData['id'];
        Product::where('id', $id)->update(array(
          'name'=>$productData['name'],
          'category_id' => $productData['category_id'],
          'count' => $productData['count'],
          'price' => $productData['price'],
        ));

        return response()->json([
          'status' => 'success',
          'product' => $id
        ]);
      } else {
        $product = Product::create($productData);

        return response()->json([
          'status' => 'success',
          'product' => $product->id
        ]);
      }
    }
  }
  
  public function getProducts()
  {
    $products = Product::all();
    return response()->json([
      'status' => 'success',
      'products' => $products
    ]);
  }

  public function removeProduct (Request $request, $id)
  {
    Product::where('id', $id)->delete();
    return response()->json([
      'status' => 'success',
    ]);
  }
}
