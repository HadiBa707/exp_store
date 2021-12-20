<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Facade\FlareClient\Api;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function index (Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }
}
