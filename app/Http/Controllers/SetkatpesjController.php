<?php

namespace App\Http\Controllers;

use App\Models\SetkatpesjModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetkatpesjController extends Controller
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

            'WebTitle' => 'PENGATURAN JAM KERJA KATEGORI PESERTA',
            'PageTitle' => 'Pengaturan Jam Kerja Kategori Peserta',
            'BasePage' => 'setkatpes',
        ];
    }

    static function getDataStat($setkatpesj_setkatpes)
    {
        return SetkatpesjModel::where('setkatpesj_setkatpes', $setkatpesj_setkatpes)->select(['setkatpesj_id', 'setkatpesj_masuk', 'setkatpesj_keluar'])->orderBy('setkatpesj_ord', 'asc')->get();
    }

    static function getDataByHr($setkatpesj_setkatpes, $setkatpesj_hr)
    {
        return SetkatpesjModel::where('setkatpesj_setkatpes', $setkatpesj_setkatpes)->where('setkatpesj_hr', $setkatpesj_hr)->select(['setkatpesj_id', 'setkatpesj_masuk', 'setkatpesj_keluar', 'setkatpesj_bts', 'setkatpesj_btsj', 'setkatpesj_tol', 'setkatpesj_tolj'])->orderBy('setkatpesj_ord', 'asc')->get()->first();
    }
    
    static function getDataByHrD($setkatpesj_hr)
    {
        return SetkatpesjModel::where('setkatpesj_hr', $setkatpesj_hr)->select(['setkatpesj_id', 'setkatpesj_masuk', 'setkatpesj_keluar', 'setkatpesj_bts', 'setkatpesj_btsj', 'setkatpesj_tol', 'setkatpesj_tolj'])->orderBy('setkatpesj_ord', 'asc')->get()->first();
    }

    static function getListButton($setkatpesj_setkatpes, $formData)
    {
        $list = '<ul class="pl-0">';
        $data = SetkatpesjModel::where('setkatpesj_setkatpes', $setkatpesj_setkatpes)->select(['setkatpesj_id', 'setkatpesj_masuk', 'setkatpesj_keluar', 'setkatpesj_bts', 'setkatpesj_btsj', 'setkatpesj_tol', 'setkatpesj_tolj', 'setkatpesj_hr'])->orderBy('setkatpesj_ord', 'asc')->get();

        $data = SetkatpesjController::setData($data);

        if ($data!=null) {
            for ($i=0; $i < count($data); $i++) { 
                $bts = 'Tidak Ada';
                if ($data[$i]['setkatpesj_bts']=='1') {
                    $bts = $data[$i]['setkatpesj_btsj'];
                }
                $tol = 'Tidak Ada';
                if ($data[$i]['setkatpesj_tol']=='1') {
                    $tol = $data[$i]['setkatpesj_tolj'];
                }
                $list.= '<li class="d-flex align-items-center my-1 border-bottom">'.$data[$i]['setkatpesj_hrAltT'].'<br/>
                Masuk : '.$data[$i]['setkatpesj_masuk'].'<br/>Keluar : '.$data[$i]['setkatpesj_keluar'].'<br/>Batas : '.$bts.'<br/>Toleransi : '.$tol;
                if ($data[$i]['setkatpesj_hr']!="O") {
                    $list.= '<button type="button" class="btn-sm btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Jam Kerja Kategori Peserta\',\''.url('setkatpesj/delete/'.$data[$i]->setkatpesj_id).'\', \''.url('setkatpes/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button></li>';
                }
                
            }
        }
        $list .= '</ul>';
        return $list;
    }

    static function getOtomatis($setkatpesj_setkatpes)
    {
        return SetkatpesjModel::where('setkatpesj_setkatpes', $setkatpesj_setkatpes)->where('setkatpesj_hr', 'O')->select(['setkatpesj_masuk', 'setkatpesj_keluar'])->orderBy('setkatpesj_ord', 'asc')->get()->first();
    }

    static function setData($data){
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]['setkatpesj_hrAltT'] = "Otomatis";
                if ($data[$i]['setkatpesj_hr']=="1") {
                    $data[$i]['setkatpesj_hrAltT'] = "Senin";            
                }elseif ($data[$i]['setkatpesj_hr']=="2") {
                    $data[$i]['setkatpesj_hrAltT'] = "Selasa";
                }elseif ($data[$i]['setkatpesj_hr']=="3") {
                    $data[$i]['setkatpesj_hrAltT'] = "Rabu";
                }elseif ($data[$i]['setkatpesj_hr']=="4") {
                    $data[$i]['setkatpesj_hrAltT'] = "Kamis";
                }elseif ($data[$i]['setkatpesj_hr']=="5") {
                    $data[$i]['setkatpesj_hrAltT'] = "Jumat";
                }elseif ($data[$i]['setkatpesj_hr']=="6") {
                    $data[$i]['setkatpesj_hrAltT'] = "Sabtu";
                }elseif ($data[$i]['setkatpesj_hr']=="7") {
                    $data[$i]['setkatpesj_hrAltT'] = "Minggu";
                }
            }
            return $data;
        }
    }

    static function insertDataPes($setkatpesj_setkatpes, $setkatpesj_masuk, $setkatpesj_keluar)
    {
        $setkatpesj_btsj = '00:00:00';
        
        $setkatpesj_tolj = '00:00:00';
        
        $SetkatpesjModel = new SetkatpesjModel();
        
        
        $SetkatpesjModel->setkatpesj_setkatpes = $setkatpesj_setkatpes;
        $SetkatpesjModel->setkatpesj_masuk = $setkatpesj_masuk;
        $SetkatpesjModel->setkatpesj_keluar = $setkatpesj_keluar;
        $SetkatpesjModel->setkatpesj_bts = '0';
        $SetkatpesjModel->setkatpesj_btsj = $setkatpesj_btsj;
        $SetkatpesjModel->setkatpesj_tol = '0';
        $SetkatpesjModel->setkatpesj_tolj = $setkatpesj_tolj;
        return $SetkatpesjModel->save();
    }

    static function updateDataPes($Pgn, $setkatpesj_setkatpes, $setkatpesj_masuk, $setkatpesj_keluar)
    {
        return DB::table('setkatpesj')->where('setkatpesj_setkatpes', $setkatpesj_setkatpes)->where('setkatpesj_hr', 'O')->update([
            'setkatpesj_masuk' => $setkatpesj_masuk,
            'setkatpesj_keluar' => $setkatpesj_keluar,
            'setkatpesj_uupdate' => $Pgn->users_id
        ]);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkatpesj_setkatpes' => 'required',
            'setkatpesj_masuk' => 'required',
            'setkatpesj_keluar' => 'required',
            'setkatpesj_bts' => 'required',
            'setkatpesj_tol' => 'required',
            'setkatpesj_hr' => 'required',
        ];
        $attributes = [
            'setkatpesj_setkatpes' => 'Kategori Peserta',
            'setkatpesj_masuk' => 'Jam Masuk Peserta',
            'setkatpesj_keluar' => 'Jam Keluar Peserta',
            'setkatpesj_bts' => 'Batas Jam Masuk',
            'setkatpesj_tol' => 'Toleransi Jam Masuk',
            'setkatpesj_hr' => 'Nama Hari',
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
            $Setkatpesj = DB::table('setkatpesj')->where('setkatpesj_setkatpes', $request->setkatpesj_setkatpes)->where('setkatpesj_hr', $request->setkatpesj_hr)->get()->first();
            if ($Setkatpesj==null) {
                $setkatpesj_btsj = '00:00:00';
                if($request->setkatpesj_btsj!=''){
                    $setkatpesj_btsj = $request->setkatpesj_btsj;
                }
                $setkatpesj_tolj = '00:00:00';
                if($request->setkatpesj_tolj!=''){
                    $setkatpesj_tolj = $request->setkatpesj_tolj;
                }
    
                $SetkatpesjModel = new SetkatpesjModel();
                
                $SetkatpesjModel->setkatpesj_hr = $request->setkatpesj_hr;
                $SetkatpesjModel->setkatpesj_setkatpes = $request->setkatpesj_setkatpes;
                $SetkatpesjModel->setkatpesj_masuk = $request->setkatpesj_masuk;
                $SetkatpesjModel->setkatpesj_keluar = $request->setkatpesj_keluar;
                $SetkatpesjModel->setkatpesj_bts = $request->setkatpesj_bts;
                $SetkatpesjModel->setkatpesj_btsj = $setkatpesj_btsj;
                $SetkatpesjModel->setkatpesj_tol = $request->setkatpesj_tol;
                $SetkatpesjModel->setkatpesj_tolj = $setkatpesj_tolj;
                $save = $SetkatpesjModel->save();
                if ($save) {
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => "Data Jam Kerja Kategori Peserta Berhasil Disimpan"
                    ];
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Jam Kerja Kategori Peserta Tidak Dapat Disimpan'];
                }
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Jam Kerja Sudah Ada'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setkatpesj_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesjModel = new SetkatpesjModel();

        $delete = $SetkatpesjModel::where('setkatpesj_id', $setkatpesj_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Jam Kerja Kategori Peserta Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Jam Kerja Kategori Peserta Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }
}
