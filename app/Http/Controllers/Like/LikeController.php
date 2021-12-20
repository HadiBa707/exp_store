<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addLike (Product $product) {
        $user = Auth::user();

        if (Like::where('user_id', $user->id)->where('product_id', $product->id)->count() == 0) {
            Like::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return response()->json(['message' => 'Added Like']);
        }
        return $this->errorResponse('the user already liked this product', 409); //409 conflict
    }

    public function deleteLike(Product $product) {
        $user = Auth::user();

        if (Like::where('user_id', $user->id)->where('product_id', $product->id)->delete()) {
            return response()->json(['message' => 'Deleted Like']);
        }
        return response()->json(['message' => 'Couldn\'t delete the like']);
    }
}
