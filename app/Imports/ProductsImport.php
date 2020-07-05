<?php

namespace App\Imports;

use App\Product;
use App\Category;
use App\SlaveRubric;
use App\Manufacturer;
use App\MasterRubric;
use App\Traits\IsSavedTrait;
use Illuminate\Http\Request;
// use App\Imports\ProductsImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use App\Http\Controllers\ProductController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToCollection, WithChunkReading, WithBatchInserts
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function collection(Collection $rows)
    {

        if(!empty($rows) && $rows[0][0] == 'Рубрика') {
            unset($rows[0]);
        }
        $saved = 0; // succesfully saved rows
        $error = []; // error counter
        $mr = null; // master rubric saved object
        $sr = null; // slave rubric saved object
        $cat = null; // category saved object
        $man = null; // manufacture rubric saved object

        foreach($rows as $key => $row) {
            $validator = Validator::make($row->toArray(), [
                    '0' => 'required|min:3|max:100',
                    '1' => 'required|min:3|max:100',
                    '2' => 'required|min:3|max:100',
                    '3' => 'required|min:3|max:100',
                    '4' => 'required|min:3|max:100',
                    '5' => 'required|alpha_num|min:4|max:100',
                    '6' => 'nullable',
                    '7' => 'required|numeric|between:0,999999.99',
                    '8' => 'required|alpha_num',
                    '9' => 'required',
            ]);

            // dd($validator->fails());
            if($validator->fails() == true) {
                $error[] = $key;
                Session::put('empty_cell', $error);
                break;
            }
        }

        // dd($error);
        if(empty($error)) {
            foreach ($rows as $key => $row) {
                $mr = MasterRubric::firstOrCreate([
                    'name' => $this->strPrettify($row[0])
                ]);

                // dd($mr);
                if(!is_null($mr)) {
                    $sr = SlaveRubric::firstOrCreate([
                        'master_rubric_id' => $mr['id'],
                        'name' => $this->strPrettify($row[1])
                    ]);
                }

                if(!is_null($sr)) {
                    $cat = Category::firstOrCreate([
                        'slave_rubric_id' => $sr['id'],
                        'name' => $this->strPrettify($row[2])
                    ]);
                }

                $man = Manufacturer::firstOrCreate([
                    'name' => $this->strPrettify($row[3])
                ]);

                if(!is_null($cat)) {
                    $product = Product::firstOrCreate([
                        'category_id' => $cat['id'],
                        'manufacturer_id' => $man['id'],
                        'name' => $this->strPrettify($row[4]),
                        'model_code' => strToUpper($row[5]),
                        'description' => $this->strPrettify($row[6]),
                        'price' => abs(number_format($row[7], 2, '.', '')),
                        'warranty' => $row[8] != 'Нет' ? abs(number_format($row[8], 2, '.', '')) : 0,
                        'availability' => $row[9] == 'есть в наличие' ? true : false
                    ]);

                    if($product->wasRecentlyCreated == true) {
                        $saved++;
                    }
                }
            }
        }
        Session::put('saved', $saved);
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function strPrettify($str) { // convert string to a single pattern
        $r = trim(preg_replace('/\s+/', ' ', $str));
        $r = strtolower($r);
        $r = ucfirst($r);
        return $r;
    }
}
