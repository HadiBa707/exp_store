<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $with = [
        'categories',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'image',
        'expiration_date',
        'contact_info',
        'quantity',
        'price',
        'views',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function prices() {
        return $this->hasMany(Price::class);
    }

    public function getPriceAttribute ($price) {
//        $discount = $this->prices()->where('date_start', '<=', Carbon::now())
//                                    ->where('date_end', '>=', Carbon::now())->get('discount');
        $discount = $this->prices()->whereDate('date_start', '<=', Carbon::now())
            ->whereDate('date_end', '>=', Carbon::now())->first();

        if (isset($discount)) {
            $offerPrice = $price - ($price/100) * ($discount['discount']);
            return $offerPrice;
        }
        return int($price);
    }
}
