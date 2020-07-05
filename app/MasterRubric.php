<?php

namespace App;
use App\SlaveRubric;

use Illuminate\Database\Eloquent\Model;

class MasterRubric extends Model
{
    protected $fillable = ['name'];

    public function slave() {
        return $this->hasMany(\SlaveRubric::class, 'master_rubric_id', 'id');
    }
}
