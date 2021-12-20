<?php

namespace App\Jobs;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    protected $product;
//
//    /**
//     * Create a new job instance.
//     *
//     * @return void
//     */
//    public function __construct(Product $product)
//    {
//        $this->product = $product->withoutRelations();
//    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Product::where('expiration_date', '<', Carbon::now())->delete();
    }
}
