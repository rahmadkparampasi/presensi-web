<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyControlller extends Controller
{
    protected $data;
    protected $session;

    public function __construct(Request $request)
    {
        $this->data = [
            'WebTitle' => 'MASUK',
            'PageTitle' => 'Masuk',
            'BasePage' => 'msk',
        ];
    }

    public function index()
    {
        
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'priAddData';
        $this->data['UrlForm'] = 'pri';

        $this->data['MethodForm1'] = substr($this->data['MethodForm'], 0, 10);

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('pri.index', $this->data);
    }
}
