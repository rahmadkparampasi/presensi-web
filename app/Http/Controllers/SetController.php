<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOSetrmr',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN',
            'PageTitle' => 'Pengaturan',
            'BasePage' => 'set',
        ];
    }

    public function index()
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setAddData';
        $this->data['UrlForm'] = 'set';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('set.index', $this->data);
    }
}
