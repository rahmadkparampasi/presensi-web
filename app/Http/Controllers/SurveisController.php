<?php

namespace App\Http\Controllers;

use App\Models\SurveisaModel;
use App\Models\SurveisModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class SurveisController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOIzin',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'SURVEI',
            'PageTitle' => 'Survei',
            'BasePage' => 'surveis',
        ];
    }

    public function showProfil(Request $request, $sisp)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'surveisProfAddData';
        $this->data['UrlForm'] = 'surveisProf';
        $this->data['sisp'] = $sisp;

        $this->data['Agent'] = new Agent;
        $this->data['url'] = route('surveis.profil', [$sisp]);

        if ($request->ajax()) {
            if($request->get('jns')=='loadDataProfil'){
                return SurveisController::loadData($this->data['Pgn'], $this->data, $sisp);
            }
        }
        return view('surveis.detailProfil', $this->data);
    }

    static function loadData($Pgn, $formData, $sisp, $dt = true) {
        DB::statement(DB::raw('set @rownum=0'));
        
        $Survei = DB::table('surveis')->leftJoin('survei', 'surveis.surveis_survei', '=', 'survei.survei_id')->leftJoin('users', 'surveis.surveis_ucreate', '=', 'users.users_id')->leftJoin('sisp', 'users.users_sisp', '=', 'sisp.sisp_id')->where('surveis_sisp', $sisp)->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'surveis_id', 'survei_id', 'survei_thn', 'sisp_nm', 'surveis_sisp'])->orderBy('survei_thn', 'desc')->get();
        
        if ($dt) {
            return datatables()->of($Survei)->addColumn('dataThn', function($Survei){
                return 'Tahun: '.$Survei->survei_thn;
            })->addColumn('aksiLihat', function($Survei){
                $button = '';
                    
                $button .= "<span data-toggle='modal' data-target='#modalSurvei' onclick='$(\"#modalSurveiTitle\").html(\"Jawaban Survei Pegawai\"); loadSurveiFormA(\"".$Survei->survei_id."\", \"".$Survei->surveis_sisp."\"); $(\"#modalSurveiForm\").attr(\"action\", \"\");' class='btn btn-info font-weight-bold mx-1'><i class=\"fa fa-eye\"></i> LIHAT</span>";
                
                return $button;
            })->rawColumns(['dataThn', 'aksiLihat'])->make(true);
        }else{
            return $Survei;
        }
    }

    public function getSurveisBySisp($surveis_sisp)
    {
        $this->data['WebTitle'] = 'SURVEI';
        $this->data['MethodForm'] = 'Survei';
        $this->data['IdForm'] = 'surveiAddData';

        $this->data['Surveis'] = DB::table('surveis')->join('sisp', 'surveis.surveis_sisp', '=', 'sisp.sisp_id')->join('survei', 'surveis.surveis_survei', '=', 'survei.survei_id')->select(['surveis_id', 'survei_thn', 'survei_id', 'surveis_survei', 'surveis_sisp', 'surveis_ucreate', 'sisp_nm'])->where('surveis_sisp', $surveis_sisp)->orderBy('survei_thn', 'desc')->orderBy('surveis_ord', 'desc')->get();

        return view('surveis.detail', $this->data);
    }

    static function getSurveis($survei_id)
    {
        $data['WebTitle'] = 'SURVEI';
        $data['MethodForm'] = 'Survei';
        $data['IdForm'] = 'surveiAddData';

        DB::statement(DB::raw('set @rownum=0'));
        $Survei = DB::table('survei');
        
        $Survei = $Survei->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'survei_id', 'survei_thn', 'survei_kuis', 'survei_act'])->where('survei_id', $survei_id)->orderBy('survei_thn', 'desc')->get()->first();

        $Survei->q = DB::table('surveiq')->where('surveiq_survei', $Survei->survei_id)->select(['surveiq_id', 'surveiq_lbl', 'surveiq_desk', 'surveiq_survei'])->orderBy('surveiq_ord')->get();

        $Survei->q = SurveiqController::setData($Survei->q);

        return $Survei;
    }

    static function getSurveisa($survei_id, $sisp)
    {
        $data['WebTitle'] = 'SURVEI';
        $data['MethodForm'] = 'Survei';
        $data['IdForm'] = 'surveiAddData';

        DB::statement(DB::raw('set @rownum=0'));
        $Survei = DB::table('survei');
        
        $Survei = $Survei->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'survei_id', 'survei_thn', 'survei_kuis', 'survei_act'])->where('survei_id', $survei_id)->orderBy('survei_thn', 'desc')->get()->first();

        $Survei->q = DB::table('surveiq')->where('surveiq_survei', $Survei->survei_id)->select(['surveiq_id', 'surveiq_lbl', 'surveiq_desk', 'surveiq_survei'])->orderBy('surveiq_ord')->get();

        $Survei->q = SurveiqController::setData($Survei->q, $sisp);

        return $Survei;
    }

    static function getSurveiCount($surveis_sisp):int
    {
        $count = 0;
        $data['WebTitle'] = 'SURVEI';
        $data['MethodForm'] = 'Survei';
        $data['IdForm'] = 'surveiAddData';

        $Survei = SurveiController::loadData($data, 0, '1', '', false);
        foreach ($Survei as $tk) {
            if ((DB::table('surveis')->where('surveis_survei', $tk->survei_id)->where('surveis_sisp', $surveis_sisp)->select()->get()->first())==null) {
                $count += 1;
            }
        }

        return $count;
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'surveis_sisp' => 'required',
            'surveis_survei.*' => 'required',
            'surveisa_surveiqa.*' => 'required',
            
        ];
        $attributes = [
            'surveis_sisp' => 'Pegawai',
            'surveis_survei.*' => 'Survei',
            'surveisa_surveiqa.*' => 'Jawaban Survei',
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $errorString = implode(",",$validator->getMessageBag()->all());
            $data['response'] = [
                'status' =>  Response::HTTP_BAD_REQUEST,
                'response' => "danger",
                'type' => "danger",
                'message' => $errorString
            ];
        }else{
            try {
                $input = $request->all();
                $surveis_survei = $request->input('surveis_survei');
                $surveisa_surveiqa = $request->input('surveisa_surveiqa');

                for ($i=0; $i < count($input['surveis_survei']); $i++) { 
                    $SurveisModel = new SurveisModel();
                    $SurveisModel->surveis_sisp = $request->surveis_sisp;
                    $SurveisModel->surveis_ucreate = $this->data['Pgn']->users_id;
                    $SurveisModel->surveis_uupdate = $this->data['Pgn']->users_id;
                    $SurveisModel->surveis_survei = $surveis_survei[$i];

                    $SurveisModel->save();

                }

                for ($i=0; $i < count($input['surveisa_surveiqa']); $i++) { 
                    $SurveisaModel = new SurveisaModel();
                    $SurveisaModel->surveisa_sisp = $request->surveis_sisp;
                    $SurveisaModel->surveisa_surveiqa = $surveisa_surveiqa[$i];

                    $SurveisaModel->save();

                }

                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Survei Pegawai Berhasil Disimpan"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Survei Pegawai Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }
}
