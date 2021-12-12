<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder;

class ProductController extends ApiController
{

    public function index()
    {
        //Deleting expired products
        Product::where('expiration_date', '<', Carbon::now())->delete();

        $products = Product::query();
        //searching
        if (request()->has('name')) {
            $serachValue = request()->name;
            $products = $products->where('name', 'LIKE', "%{$serachValue}%");
        }
        if (request()->has('date')) {
            $serachValue = request()->date;
            $products = $products->where('expiration_date', $serachValue);
        }
        if (request()->has('category')) {
            $serachValue = request()->category;
            $products = $products->whereHas('categories', function ($query) use ($serachValue) {
                    $query->where('id', $serachValue);
            });
        }
        $products = $products->get();
        return $this->showAll($products);
    }


    public function show(Product $product)
    {
        return $this->showOne($product);
    }
}
