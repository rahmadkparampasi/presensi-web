<?php

namespace App\Http\Controllers;

use App\Models\SurveiqaModel;
use App\Models\SurveiqModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SurveiqController extends Controller
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

            'WebTitle' => 'PERTANYAAN SURVEI',
            'PageTitle' => 'Pertanyaan Survei',
            'BasePage' => 'surveiq',
        ];
    }

    public function detailSurvei($surveiq_survei)
    {
        $formData['Pgn'] = $this->getUser();
        $formData['surveiq_survei'] = $surveiq_survei;
        $formData['Surveiq'] = DB::table('surveiq')->where('surveiq_survei', $surveiq_survei)->select(['surveiq_id', 'surveiq_survei', 'surveiq_lbl', 'surveiq_desk'])->get();
        $formData['Surveiq'] = SurveiqController::setData($formData['Surveiq']);
        $formData['Survei'] = DB::table('survei')->where('survei_id', $surveiq_survei)->select(['survei_kuis'])->get()->first();

        return view('surveiq.detailSurvei', $formData);
    }

    static function setData($data, $sisp = '')
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) {
                if ($sisp == '') {
                    $data[$i]->a = DB::table('surveiqa')->where('surveiqa_surveiq', $data[$i]->surveiq_id)->select(['surveiqa_id', 'surveiqa_a', 'surveiqa_v'])->orderBy('surveiqa_ord')->get();
                }else{
                    $data[$i]->a = DB::table('surveiqa')->join('surveisa', 'surveisa.surveisa_surveiqa', '=', 'surveiqa.surveiqa_id')->where('surveiqa_surveiq', $data[$i]->surveiq_id)->where('surveisa_sisp', $sisp)->select(['surveiqa_id', 'surveiqa_a', 'surveiqa_v'])->orderBy('surveiqa_ord')->get();
                }
            }
        }else{
            if ($sisp == '') {
                $data->a = DB::table('surveiqa')->where('surveiqa_surveiq', $data->surveiq_id)->select(['surveiqa_id', 'surveiqa_a', 'surveiqa_v'])->orderBy('surveiqa_ord')->get();
            }else{
                $data->a = DB::table('surveiqa')->join('surveisa', 'surveisa.surveisa_surveiqa', '=', 'surveiqa.surveiqa_id')->where('surveiqa_surveiq', $data->surveiq_id)->where('surveisa_sisp', $sisp)->select(['surveiqa_id', 'surveiqa_a', 'surveiqa_v'])->orderBy('surveiqa_ord')->get();
            }
        }
        return $data;
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'surveiq_lbl' => 'required',
            'surveiqa_v.*' => 'required',
            'surveiqa_a.*' => 'required',
            
        ];
        $attributes = [
            'surveiq_lbl' => 'Judul Pertanyaan',
            'surveiqa_v.*' => 'Nilai Jawaban',
            'surveiqa_a.*' => 'Jawaban',
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
            $SurveiqModel = new SurveiqModel();

            $kuis = $request->kuis;
            
            $SurveiqModel->surveiq_survei = $request->surveiq_survei;
            $SurveiqModel->surveiq_lbl = addslashes($request->surveiq_lbl);
            $SurveiqModel->surveiq_desk = addslashes($request->surveiq_desk);
            $save = $SurveiqModel->save();
            if ($save) {
                $Surveiq = SurveiqModel::where('surveiq_ord', $SurveiqModel->surveiq_id)->select(['surveiq_id'])->get()->first();
                $input = $request->all();
                $surveiqa_a = $request->input('surveiqa_a');
                $surveiqa_v = $request->input('surveiqa_v');
                try {
                    for($i=0; $i< count($input['surveiqa_a']); $i++) {
                        $Surveiqa = new SurveiqaModel();
                        $Surveiqa->surveiqa_surveiq = $Surveiq->surveiq_id;
                        $Surveiqa->surveiqa_a = $surveiqa_a[$i];
                        if ($kuis=="0") {
                            $Surveiqa->surveiqa_v = 0;
                        }else{
                            $Surveiqa->surveiqa_v = $surveiqa_v[$i];
                        }
                        
                        $Surveiqa->save();
                    }
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => "Pertanyaan Survei Berhasil Disimpan"
                    ];
                } catch (\Throwable $e) {
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Pertanyaan Survei Tidak Dapat Disimpan, '.$e->getMessage()];
                }
                
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Pertanyaan Survei Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($surveiq_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        try {
            DB::table('surveiq')->where('surveiq_id', $surveiq_id)->delete();
            try {
                DB::table('surveiqa')->where('surveiqa_surveiq', $surveiq_id)->delete();
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pertanyaan Survei Berhasil Dihapus"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Jawaban Pertanyaan Survei Tidak Dapat Dihapus, '.$e->getMessage()];
            }
        } catch (\Exception $e) {
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pertanyaan Survei Tidak Dapat Dihapus, '.$e->getMessage()];
        }
        return response()->json($data, $data['response']['status']);
    }
}
