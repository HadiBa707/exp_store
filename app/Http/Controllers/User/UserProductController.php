<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
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

        $rules = [
            'name' => 'required',
            'expiration_date' => 'required',
            'contact_info' => 'required',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|min:1',
//            'image' => 'image',
//            'categories' => 'array',
//            'categories.*' => 'exists:categories,id',

            'discount1' => 'between:1,99|required',
            'date1start' => 'required',
            'date1end' => 'required',

            'discount2' => 'between:1,99|required',
            'date2start' => 'required',
            'date2end' => 'required',

            'discount3' => 'between:1,99|required',
            'date3start' => 'required',
            'date3end' => 'required',

        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['categories'] = explode(',',$data['categories']);

        $data['image'] = $request->image->store('/public');
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
        return $this->showOne($product);
    }

//TODO check update tomorrow
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        $rules = [
            'quantity' => 'integer|min:1',
            'price' => 'min:1',
//            'image' => 'image',
//            'categories' => 'array',
//            'categories.*' => 'exists:categories,id',
        ];

        $this->validate($request, $rules);
        $this->checkUser($user, $product);

        $data = $request->all();

        $data['categories'] = explode(',',$data['categories']);
        $product->categories()->attach($data['categories']);

        $product->prices()->delete();
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

        $product->update($data);

        if ($request->hasFile('image')) {
            Storage::delete($product->image);

            $product->image = $request->image->store('/public');
        }

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
