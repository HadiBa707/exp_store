<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(Product $product) {
        $comments = Comment::where('product_id', $product->id)->get();

        return $this->showAll($comments);
    }

    public function addComment(Request $request, Product $product) {
        $user = Auth::user();

        $rules = [
            'content' => 'required',
        ];

        $this->validate($request, $rules);

        $comment = Comment::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'content' => $request->get('content'),
        ]);

        return $this->showOne($comment);

    }

    public function deleteComment(Product $product) {
        $user = Auth::user();

        $comment = Comment::where('user_id', $user->id)->where('product_id', $product->id)->delete();

        return response()->json(['message' => 'deleted']);
    }


}
