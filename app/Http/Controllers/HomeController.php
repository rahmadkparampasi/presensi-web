<?php

namespace App\Http\Controllers;

use App\Models\IidiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    protected $data;

    public function __construct()
    {

        $this->data = [
            'mOp' => 'mOHome',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'BERANDA',
            'PageTitle' => 'Beranda',
            'BasePage' => '/',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['IdForm'] = 'homeAddData';
        $this->data['UrlForm'] = 'home';

        if ($this->data['Pgn']->users_tipe=="M") {
            return redirect()->intended('siswa/detail/'.$this->data['Pgn']->users_sisp);
        }
        
        if ($this->data['Pgn']->users_tipe=="P") {
            return redirect()->intended('sisp/detail/'.$this->data['Pgn']->users_sisp);
        }
        $this->data['Agent'] = new Agent;
        
        //Edit: Perbaiki Data Keterlambatan
        // $this->data['Siswa'] = SiswaController::setCountChart();
        // $this->data['Guru'] = GuruController::setCountChart();
        if ($request->ajax()) {
            return SiswaController::loadData($this->data['Pgn'], $this->data, 5);
        }

        return view('home.indexA', $this->data);
    }
}
