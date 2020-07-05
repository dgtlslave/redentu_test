<?php

namespace App;
use App\Manufacturer;
use App\Category;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'manufacturer_id', 'name', 'model_code', 'description', 'price', 'warranty', 'availability'];

    public function man_product() {
        return $this->belongsTo(\Manufacturer::class, 'id', 'manufacturer_id');
    }

    public function cat_product() {
        return $this->belongsTo(\Category::class, 'id', 'category_id');
    }
}
