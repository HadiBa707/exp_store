<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = Auth::user();
        $products = $user->products;

        return $this->showAll($products);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        //TODO does the image required? 'image' => 'required|image'
        $rules = [
            'name' => 'required',
            'expiration_date' => 'required',
            'contact_info' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|min:1',
            'image' => 'image', //TODO is it Required?
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',

            'discount1' => 'between:1,99|required',
            'date1start' => 'date|required',
            'date1end' => 'date|required',

            'discount2' => 'between:1,99|required',
            'date2start' => 'date|required',
            'date2end' => 'date|required',

            'discount3' => 'between:1,99|required',
            'date3start' => 'date|required',
            'date3end' => 'date|required',

        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['image'] = $request->image->store('');

        $product = new Product($data);
        $user->products()->saveMany([$product]);
        $product->categories()->attach($data['categories']);

        $price1 = new Price([
            'discount' => $data['discount1'],
            'date_start' => $data['date1start'],
            'date_end' => $data['date1end']
        ]);
        $price2 = new Price([
            'discount' => $data['discount2'],
            'date_start' => $data['date2start'],
            'date_end' => $data['date2end']
        ]);

        $price3 = new Price([
            'discount' => $data['discount3'],
            'date_start' => $data['date3start'],
            'date_end' => $data['date3end']
        ]);

        $product->prices()->saveMany([$price1, $price2, $price3]);

        //TODO try to return the new product
        return response()->json('done');
    }


    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        //TODO does the image required? 'image' => 'required|image'
        $rules = [
            'quantity' => 'integer|min:1',
            'price' => 'min:1',
            'image' => 'image',
        ];

        $this->validate($request, $rules);
        $this->checkUser($user, $product);

        $product->fill($request->only([
            'name',
            'contact_info',
            'quantity',
            'price',
        ]));

        if ($request->hasFile('image')) {
            Storage::delete($product->image);

            $product->image = $request->image->store('');
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to specify different values to update', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();

        $this->checkUser($user, $product);

        Storage::delete($product->image);
        $product->delete();

        return $this->showOne($product);
    }

    protected function checkUser(User $user, Product $product) {
        if ($user->id != $product->user_id) {
            throw new HttpException(422, 'The Authenticated User is not the owner of the product');
        }
    }
}
