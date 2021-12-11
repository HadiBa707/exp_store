<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed', //password_confirmation required
        ];

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if (! $user->isDirty()) {
            return $this->errorResponse('You need to specify different value to update', 422);
        }

        $user->save();

        return $this->showOne($user);
    }
}
