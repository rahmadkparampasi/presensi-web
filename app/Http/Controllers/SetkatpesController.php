<?php

namespace App\Http\Controllers;

use App\Models\SetkatpesModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetkatpesController extends Controller
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

            'WebTitle' => 'PENGATURAN KATEGORI PESERTA & JADWAL',
            'PageTitle' => 'Pengaturan Kategori Peserta & Jadwal',
            'BasePage' => 'setkatpes',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkatpesAddData';
        $this->data['UrlForm'] = 'setkatpes';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        
        if ($request->ajax()) {
            return SetkatpesController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setkatpes.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setkatpesAddData';
        $this->data['UrlForm'] = 'setkatpes';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setkatpes.data', $this->data);
    }   

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setkatpes = SetkatpesModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setkatpes_id', 'setkatpes_nm', 'setkatpes_act', 'setkatpes_ps', 'setkatpes_pg', 'setkatpes_pu', 'setkatpes_sh'])->orderBy('setkatpes_ord', 'desc')->get();
        $Setkatpes = SetkatpesController::setData($Setkatpes, $formData);
        return datatables()->of($Setkatpes)->addColumn('aksiStatus', function ($Setkatpes) use ($Pgn) {
            $button = '';
            $button .= $Setkatpes->setkatpes_actAltBu;
            return $button;
        })->addColumn('aksiShift', function ($Setkatpes) use ($Pgn) {
            $button = '';
            $button .= $Setkatpes->setkatpes_shAltBu;
            return $button;
        })->addColumn('aksiJamKerja', function ($Setkatpes) use ($Pgn) {
            $button = '';
            $button .= $Setkatpes->setkatpesjAltBu;
            return $button;
        })->addColumn('aksiEdit', function ($Setkatpes) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('setkatpes.update').'\'); addFill(\'setkatpes_id\', \''.$Setkatpes->setkatpes_id.'\'); addFill(\'setkatpes_nm\', \''.$Setkatpes->setkatpes_nm.'\'); addFill(\'setkatpesj_masukKrj\', \''.$Setkatpes->setkatpesj_masuk.'\');addFill(\'setkatpesj_keluarKrj\', \''.$Setkatpes->setkatpesj_keluar.'\');"><i class="fas fa-pen"></i></button>';
                
            // $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\'Menghapus Data Kategori Peserta\',\''.url('setkatpes/delete/'.$Setkatpes->setkatpes_id).'\', \''.url('setkatpes/load').'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            
            return $button;
        })
        ->rawColumns(['aksiStatus', 'aksiEdit', 'aksiPilihan', 'aksiJamKerja', 'aksiShift'])->make(true);
    }

    static function getNama($setkatpes_id)
    {
        $Setkatpes = SetkatpesModel::where('setkatpes_id', $setkatpes_id)->select(['setkatpes_id', 'setkatpes_nm',])->orderBy('setkatpes_ord', 'asc')->get()->first();
        return $Setkatpes->setkatpes_nm;
    }

    static function getDataActStat()
    {
        return SetkatpesModel::where('setkatpes_act', '1')->select(['setkatpes_id', 'setkatpes_nm',])->orderBy('setkatpes_ord', 'asc')->get();
    }

    static function getDataPsStat()
    {
        return SetkatpesModel::where('setkatpes_ps', '1')->select(['setkatpes_id', 'setkatpes_nm',])->get()->first();
    }

    static function getDataPgStat()
    {
        return SetkatpesModel::where('setkatpes_pg', '1')->select(['setkatpes_id', 'setkatpes_nm',])->get()->first();
    }

    static function getDataPuStat()
    {
        return SetkatpesModel::where('setkatpes_pu', '1')->select(['setkatpes_id', 'setkatpes_nm',])->orderBy('setkatpes_ord', 'asc')->get();
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkatpes_nm' => 'required',
            'setkatpesj_masuk' => 'required',
            'setkatpesj_keluar' => 'required',
        ];
        $attributes = [
            'setkatpes_nm' => 'Kategori Peserta',
            'setkatpesj_masuk' => 'Jam Masuk Otommatis',
            'setkatpesj_keluar' => 'Jam Keluar Otommatis',
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
            $SetkatpesModel = new SetkatpesModel();
            
            $SetkatpesModel->setkatpes_nm = addslashes($request->setkatpes_nm);
            $save = $SetkatpesModel->save();
            if ($save) {
                $Setkatpes = SetkatpesModel::where('setkatpes_ord', $SetkatpesModel->setkatpes_id)->select(['setkatpes_id'])->get()->first();
                $Setkatpesj = SetkatpesjController::insertDataPes($Setkatpes->setkatpes_id, $request->setkatpesj_masuk, $request->setkatpesj_keluar);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Kategori Peserta Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setkatpes_nm' => 'required',
            'setkatpesj_masuk' => 'required',
            'setkatpesj_keluar' => 'required',
        ];
        $attributes = [
            'setkatpes_nm' => 'Kategori Peserta',
            'setkatpesj_masuk' => 'Jam Masuk Otommatis',
            'setkatpesj_keluar' => 'Jam Keluar Otommatis',
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
                $update = DB::table('setkatpes')->where('setkatpes_id', $request->setkatpes_id)->update([
                    'setkatpes_nm' => addslashes($request->setkatpes_nm),
                    'setkatpes_uupdate' => $this->data['Pgn']->users_id
                ]);
                $Setkatpesj = SetkatpesjController::updateDataPes($this->data['Pgn'], $request->setkatpes_id, $request->setkatpesj_masuk, $request->setkatpesj_keluar);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Kategori Peserta Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setAct($setkatpes_act, $setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $message = "Dinonaktifkan";
        if ($setkatpes_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->update([
            'setkatpes_act' => $setkatpes_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setPU($setkatpes_pu, $setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $message = "Dinonaktifkan Sebagai Pilihan Umum";
        if ($setkatpes_pu=="1") {
            $message = "Diaktifkan Sebagai Pilihan Umum";
        }

        $update = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->update([
            'setkatpes_pu' => $setkatpes_pu
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setSH($setkatpes_sh, $setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $message = "Dinonaktifkan Sebagai Sistem Shift";
        if ($setkatpes_sh=="1") {
            $message = "Diaktifkan Sebagai Sistem Shift";
        }

        $update = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->update([
            'setkatpes_sh' => $setkatpes_sh
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setPG($setkatpes_pg, $setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $message = "Dinonaktifkan Sebagai Pilihan Pegawai";
        if ($setkatpes_pg=="1") {
            $message = "Diaktifkan Sebagai Pilihan Pegawai";
        }

        $update = DB::table('setkatpes')->update([
            'setkatpes_pg' => '0'
        ]);
        $update = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->update([
            'setkatpes_pg' => $setkatpes_pg
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function setPS($setkatpes_ps, $setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $message = "Dinonaktifkan Sebagai Pilihan Siswa";
        if ($setkatpes_ps=="1") {
            $message = "Diaktifkan Sebagai Pilihan Siswa";
        }

        $update = DB::table('setkatpes')->update([
            'setkatpes_ps' => '0'
        ]);
        $update = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->update([
            'setkatpes_ps' => $setkatpes_ps
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($setkatpes_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $SetkatpesModel = new SetkatpesModel();

        $delete = $SetkatpesModel::where('setkatpes_id', $setkatpes_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Kategori Peserta Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Kategori Peserta Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function setData($data, $formData)
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                
                $data[$i]['setkatpes_actAltT'] = "Aktif";
                $data[$i]['setkatpes_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";

                $data[$i]['setkatpes_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Kategori Peserta\", \"".url('setkatpes/setAct/0/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-success font-weight-bold'>AKTIF</span>";

                if ($data[$i]['setkatpes_act']=="0") {
                    $data[$i]['setkatpes_actAltBa'] = "<span class='badge badge-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkatpes_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Kategori Peserta\", \"".url('setkatpes/setAct/1/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-danger font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['setkatpes_actAltT'] = "Tidak Aktif";                
                }
                $data[$i]['setkatpes_puAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Umum' onclick='callOtherTWLoad(\"Menonaktifkan Status Kategori Peserta Dalam Pilihan Umum\", \"".url('setkatpes/setPU/0/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-sm btn-success font-weight-bold'><i class='fa fa-check'></i></span>";
                
                if ($data[$i]['setkatpes_pu']=="0") {
                    $data[$i]['setkatpes_puAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Umum' onclick='callOtherTWLoad(\"Mengaktifkan Status Kategori Peserta Dalam Pilihan Umum\", \"".url('setkatpes/setPU/1/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='btn btn-sm btn-secondary font-weight-bold'><i class='fa fa-check'></i></span>";
                }

                $data[$i]['setkatpes_pgAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Pegawai' onclick='callOtherTWLoad(\"Menonaktifkan Status Kategori Peserta Dalam Pilihan Pegawai\", \"".url('setkatpes/setPG/0/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-sm btn-success font-weight-bold'><i class='fa fa-user-tie'></i></span>";
                
                if ($data[$i]['setkatpes_pg']=="0") {
                    $data[$i]['setkatpes_pgAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Pegawai' onclick='callOtherTWLoad(\"Mengaktifkan Status Kategori Peserta Dalam Pilihan Pegawai\", \"".url('setkatpes/setPG/1/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-sm btn-secondary font-weight-bold'><i class='fa fa-user-tie'></i></span>";
                }

                $data[$i]['setkatpes_psAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Siswa' onclick='callOtherTWLoad(\"Menonaktifkan Status Kategori Peserta Dalam Pilihan Siswa\", \"".url('setkatpes/setPS/0/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-sm btn-success font-weight-bold'><i class='fa fa-graduation-cap'></i></span>";
                
                if ($data[$i]['setkatpes_ps']=="0") {
                    $data[$i]['setkatpes_psAltBu'] = "<span title='Status Kategori Peserta Dalam Pilihan Siswa' onclick='callOtherTWLoad(\"Mengaktifkan Status Kategori Peserta Dalam Pilihan Siswa\", \"".url('setkatpes/setPS/1/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-sm btn-secondary font-weight-bold'><i class='fa fa-graduation-cap'></i></span>";
                }
                
                $data[$i]['setkatpes_shAltBu'] = "<span title='Status Kategori Peserta Dalam Sistem Shift' onclick='callOtherTWLoad(\"Menonaktifkan Status Kategori Peserta Dalam Sistem Shift\", \"".url('setkatpes/setSH/0/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-success font-weight-bold'>Ya</span>";
                
                if ($data[$i]['setkatpes_sh']=="0") {
                    $data[$i]['setkatpes_shAltBu'] = "<span title='Status Kategori Peserta Dalam Sistem Shift' onclick='callOtherTWLoad(\"Mengaktifkan Status Kategori Peserta Dalam Sistem Shift\", \"".url('setkatpes/setSH/1/'.$data[$i]['setkatpes_id'])."\", \"".url('setkatpes/load')."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\")' role='button' class='mx-1 btn btn-danger font-weight-bold'>Tidak</span>";
                }
                
                $data[$i]['setkatpesjAltBu'] = '<button class="btn btn-primary mx-1 btn-sm" data-target="#modalAddJ" data-toggle="modal" onclick="$(\'#setkatpesj_setkatpes\').val(\''.$data[$i]['setkatpes_id'].'\')"><i class="fa fa-plus"></i> TAMBAH</button><br/><br/>';
                $data[$i]['setkatpesjAltBu'] .= SetkatpesjController::getListButton($data[$i]['setkatpes_id'], $formData);
                $data[$i]['setkatpesj_masuk'] = '';
                $data[$i]['setkatpesj_keluar'] = '';
                $data[$i]['jam'] = SetkatpesjController::getOtomatis($data[$i]['setkatpes_id']);
                if ($data[$i]['jam']!=null) {
                    $data[$i]['setkatpesj_masuk'] = $data[$i]['jam']['setkatpesj_masuk'];
                    $data[$i]['setkatpesj_keluar'] = $data[$i]['jam']['setkatpesj_keluar'];
                }

            }
        }
        return $data;
    }
}
