<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ProductSaved implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        $this->product->notify(new ProductsImport());
    }

    public $tries = 3;
    public $timeout = 10;
}
