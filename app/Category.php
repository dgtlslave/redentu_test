<?php

namespace App;
use App\SlaveRubric;
use App\Product;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slave_rubric_id', 'name'];

    public function slave_category() {
        return $this->belongsTo(\SlaveRubric::class, 'id', 'slave_rubric_id');
    }

    public function product_cat() {
        return $this->hasMany(\Product::class, 'id', 'category_id');
    }
}
