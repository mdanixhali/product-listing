<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    protected $perPage = 5;
    public function index(){
        $userId = auth()->user()->id;
        $products = Product::with('image:id,product_id,image')->where('user_id', $userId)->paginate($this->perPage);
        return response()->json($products);
    }

    public function store(ProductRequest $request){
        DB::beginTransaction();
        try{
            $product = Product::create($request->validated()->except('image'));
            $product->refresh();
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $request->image 
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Saved.']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]); //Or other message as per need
        }
    }

    public function update($id, ProductRequest $request){
        DB::beginTransaction();
        try{
            $product = Product::find($id);
            $product->update($request->validated());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Saved.']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]); //Or other message as per need
        }
    }
}
