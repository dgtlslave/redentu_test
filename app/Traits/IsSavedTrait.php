<?php

namespace App\Traits;
// namespace App\Imports;

use App\Imports\ProductsImport;
// use App\ProductsImport;


trait IsSavedTrait {

    // public $s = null;

    public function getSaved()
    {
        return $this->saved;
    }



    // public function saved(ProductsImport $productsImport)
    // {
    //     $this->s = $productsImport;
    //     return $this;
    // }
}


?>
