<?php

namespace App\Http\Controllers;

use App\Product;
use App\Rules\FileSize;
use App\Traits\IsSavedTrait;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required', 'file', 'mimes:xls,xlsx', new FileSize
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'error'], 200);
        }

        $path = $request->file('import_file');
        $import = new ProductsImport;
        // $data = Excel::queueImport($import, $path);
        $data = Excel::import($import, $path);
        $array = $import->toArray($path);
        $saved = Session::get('saved');
        $empty_cell = Session::get('empty_cell');
        Session::forget('saved');
        Session::forget('empty_cell');
        Session::flush();

        if(empty($empty_cell)) {
            return response()->json(['message' => 'ok', 'saved' => $saved, 'count' => count($array[0]) - 1], 200);
        } else {
            return response()->json(['message' => 'break', 'empty_cell' => $empty_cell[0]], 200);
        }
    }
}

