<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Jobs\ProductJob;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder;

class ProductController extends ApiController
{

    public function index()
    {
        $products = Product::query();
        //searching
        if (request()->has('name')) {
            $searchValue = request()->name;
            $products = $products->where('name', 'LIKE', "%{$searchValue}%");
        }
        if (request()->has('date')) {
            $searchValue = request()->date;
            $products = $products->where('expiration_date', $searchValue);
        }
        if (request()->has('category')) {
            $searchValue = request()->category;
            $products = $products->whereHas('categories', function ($query) use ($searchValue) {
                $try = explode(',', $searchValue);
                $query->where('id', $try);
            });
        }
        $products = $products->get();
        return $this->showAll($products);
    }


    public function show(Product $product)
    {
        if (Auth::user()) {
            $updatedViews = $product->views + 1;
            $product->fill([
                'views' => $updatedViews,
            ]);
            $product->save();
        }

        return $this->showOne($product);
    }
}
