<?php

use Illuminate\Support\Facades\Route;

use App\Models\MyItems ;

use Illuminate\Http\Request;
Route::get('/', function () {
    $products = MyItems::all();
    return view('main');
});

Route::get('productajaxCRUD', function () {
    $products = MyItems::all();
    return view('index')->with('products',$products);
});
Route::get('productajaxCRUD/{product_id?}',function($product_id){
    $product = MyItems::find($product_id);
    return response()->json($product);
});
Route::post('productajaxCRUD',function(Request $request){   
    $product = MyItems::create($request->input());
    $contact = new MyItems;

  
    return response()->json($product);
});
Route::put('productajaxCRUD/{product_id?}',function(Request $request,$product_id){
    $product = MyItems::find($product_id);
    $product->name = $request->name;
    $product->ingredients = $request->ingredients;
    $product->directions = $request->directions;
    $product->save();
    return response()->json($product);
});
Route::delete('productajaxCRUD/{product_id?}',function($product_id){
    $product = MyItems::destroy($product_id);
    return response()->json($product);
});



