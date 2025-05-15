<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function setDisplay($methodForm, $dis = '')
    {
        $MethodForm1 = substr($methodForm, 0, 10);
        if ($MethodForm1=="updateData") {
            if ($dis=='') {
                return 'display: flex;';
            }else{
                return 'display: '.$dis.';';
            }
        }else{
            return 'display: none;';
        }
    }

    public function getUser()
    {
        $user = Auth::user(); 

        return $user;
    }
}
