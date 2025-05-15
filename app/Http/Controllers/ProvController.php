<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\ProvModel;
use Illuminate\Http\Request;

class ProvController extends Controller
{
    static function getData()
    {
        $Kab = ProvModel::select('id', 'nama')->orderBy('nama', 'asc')->get();
        // $Kec = KabController::setDataSt($Kec);

        return $Kab;
    }
}
