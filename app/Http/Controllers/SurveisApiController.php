<?php

namespace App\Http\Controllers;

use App\Http\Resources\SurveisResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class SurveisApiController extends Controller
{
    public function profil()
    {
        $data['MethodForm'] = 'insertData';
        $data['IdForm'] = 'surveisAddData';
        $data['UrlForm'] = 'surveis';

        $data['Sisp'] = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->get()->first();

        $data['surveis_sisp'] = Auth::user()->users_sisp;
        
        $Surveis = SurveisController::loadData(Auth::user(), $data, Auth::user()->users_sisp, false);

        return new SurveisResource($Surveis);
    }

    public function profilA($idsurvei)
    {
        $MySurvei = [];
        $Survei = SurveisController::getSurveisa($idsurvei, Auth::user()->users_sisp);
        foreach ($Survei->q as $tk) {
            array_push($MySurvei, $tk);
        }

        foreach ($MySurvei as $tk) {
            if (count($tk->a)==0) {
                $tk->na = 'Tidak Ada Jawaban';
            }else{
                for ($i=0; $i < count($tk->a); $i++) { 
                    if ($i>0) {
                        continue;
                    }
                    $tk->na = $tk->a[$i]->surveiqa_a;
                }
                
            }
        }

        $N = [
            'data' => $MySurvei
        ];
        // dd($MySurvei);
        return $N;

        // return new SurveisResource($Survei);
    }
}
