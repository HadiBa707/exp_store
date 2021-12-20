<?php

namespace Database\Seeders;

use App\Jobs\ProductJob;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Product::truncate();
        Category::truncate();
        Price::truncate();
        DB::table('category_product')->truncate();

        $usersQuantity = 100;
        $productsQuantity = 200;
        $categoriesQuantity = 30;
//        $pricesQuantity = 300;

        User::factory()->count($usersQuantity)->create();
        Category::factory()->count($categoriesQuantity)->create();
        Product::factory()->count($productsQuantity)->create()->each(
            function ($product){
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
                $product->prices()->saveMany(Price::factory()->count(3)->make());
//                ProductJob::dispatch($product);
//                $prices = Price::all()->random(3)->pluck('id');
//                $product->prices()->attach($prices);
            });

//        Price::factory()->count($pricesQuantity)->create();
    }
}
