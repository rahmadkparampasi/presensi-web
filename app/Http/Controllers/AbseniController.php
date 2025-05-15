<?php

namespace App\Http\Controllers;

use App\Models\AbseniModel;
use Illuminate\Http\Request;

class AbseniController extends Controller
{
    static function prosesIzin($abseni_absen, $abseni_sispi)
    {
       
        $AbseniModel = new AbseniModel();
        $AbseniModel->abseni_absen = $abseni_absen;
        $AbseniModel->abseni_sispi = $abseni_sispi;
        return $AbseniModel->save();
    }
}
