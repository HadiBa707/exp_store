<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLikeController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = Auth::user();

        //TODO should i pluck the product,or i need the like entity info
        $likes = $user->likes()->with('product')->get()->pluck('product');
        return $this->showAll($likes);
    }
}
