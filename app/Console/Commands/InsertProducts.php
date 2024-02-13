<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsertProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //After getting the products
        $products = [];
        foreach($products as $product){
            DB::beginTransaction();
            try{
                $image = $product['image'];
                unset($product['image']);
                $product = Product::create($product);
                $product->refresh();
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $image 
                ]);
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
                Log::error($e->getMessage());
            }
        }
    }
}
