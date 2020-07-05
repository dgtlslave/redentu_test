<?php

namespace App;
use App\MasterRubric;
use App\Category;

use Illuminate\Database\Eloquent\Model;

class SlaveRubric extends Model
{
    protected $fillable = ['master_rubric_id', 'name'];

    public function master() {
        return $this->belongsTo(\MasterRubric::class, 'id', 'master_rubric_id');
    }

    public function category_slave() {
        return $this->hasMany(\Category::class, 'slave_rubric_id', 'id');
    }
}
