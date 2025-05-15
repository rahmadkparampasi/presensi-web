<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
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

            'WebTitle' => 'PENGATURAN PENGGUNA',
            'PageTitle' => 'Pengaturan Pengguna',
            'BasePage' => 'user',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        $this->data['url'] = route('user.index');
        $this->data['tipe'] = 'A';
        
        if ($request->ajax()) {
            return UserController::loadData($this->data['Pgn'], $this->data, 0, 'A');
        }
        return view('users.index', $this->data);
    }

    public function guru(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        $this->data['url'] = route('user.guru');
        $this->data['tipe'] = 'G';
        if ($request->ajax()) {
            return UserController::loadData($this->data['Pgn'], $this->data, 0, 'G');
        }
        return view('users.index', $this->data);
    }

    public function siswa(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        $this->data['url'] = route('user.siswa');
        $this->data['tipe'] = 'M';
        if ($request->ajax()) {
            return UserController::loadData($this->data['Pgn'], $this->data, 0, 'M');
        }
        return view('users.index', $this->data);
    }

    public function pegawai(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        $this->data['url'] = route('user.pegawai');
        $this->data['tipe'] = 'P';
        if ($request->ajax()) {
            return UserController::loadData($this->data['Pgn'], $this->data, 0, 'P');
        }
        return view('users.index', $this->data);
    }

    public function nonaktif(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);
        
        $this->data['url'] = route('user.na');
        $this->data['tipe'] = 'M';
        if ($request->ajax()) {
            return UserController::loadData($this->data['Pgn'], $this->data, 0, 'M', '0');
        }
        return view('users.index', $this->data);
    }

    static function loadData($Pgn, $formData,  $limit = 0, $users_tipe, $users_act = '')
    {
        DB::statement(DB::raw('set @rownum=0'));
        $User = User::leftJoin('sisp', 'users.users_sisp', '=', 'sisp.sisp_id');
        if ($users_act==''||$users_act=='1') {
            $User = $User->where('users_tipe', $users_tipe);
            $User = $User->where('users_act', '1');
        }else{
            $User = $User->where('users_act', '0');
        }
        $User = $User->select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'users_id', 'users_nm', 'username', 'users_tipe', 'users_act'])->orderBy('users_tipe', 'asc')->orderBy('users_ord', 'desc');
        if ($limit!=0) {
            $User = $User->limit($limit)->get();
        }else{
            $User = $User->get();
        }
        $formData['users_tipe'] = $users_tipe;

        $User = UserController::setData($User, $formData);
        return datatables()->of($User)->addColumn('aksiSandi', function ($User) use ($Pgn) {
            $button = '';
            if($Pgn->users_id==$User->users_id){
                $button .= '<button class="btn btn-warning mx-1" data-toggle="modal" data-target="#modalChangePwd" onclick="resetForm(\'modalChangePwdF\'); addFill(\'users_nmPwd\', \''.$User->users_nm.'\'); addFill(\'users_idPwd\', \''.$User->users_id.'\'); addFill(\'tipePwd\', \'D\');"><i class="fa fa-sync"></i> Ubah Password</button>';
            }
            $button .= '<button class="btn btn-warning mx-1" data-toggle="modal" data-target="#modalChangeReset" onclick="resetForm(\'modalChangeResetF\'); addFill(\'users_nmReset\', \''.$User->users_nm.'\'); addFill(\'users_idReset\', \''.$User->users_id.'\'); addFill(\'tipeReset\', \'D\'); "><i class="fa fa-sync"></i> Reset Password</button>';
            return $button;
        })->addColumn('aksiStatus', function ($User) use ($Pgn) {
            $button = '';
            if($Pgn->users_id!=$User->users_id){
                $button .= $User->users_actAltBu;
            }
            return $button;
        })->addColumn('aksiEdit', function ($User) use ($Pgn, $formData, $users_act, $users_tipe) {
            $button = '';
            $pesan = UserController::sugestionMessageDelete($User->users_nm);
            if($Pgn->users_id!=$User->users_id){
                $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\''.$formData['IdForm'].'card\', \'block\'); cActForm(\''.$formData['IdForm'].'\', \''.route('user.update').'\'); addFill(\'users_id\', \''.$User->users_id.'\'); addFill(\'users_nm\', \''.$User->users_nm.'\'); addFill(\'username\', \''.$User->username.'\'); showFormUsersUpdate()"><i class="fas fa-pen"></i></button>';
                    
                $button .= '<button type="button" class="btn btn-danger mx-1" onclick="callOtherTWLoad(\''.$pesan.'\',\''.url('user/delete/'.$User->users_id).'\', \''.route('user.load', [$users_act, $users_tipe]).'\', \''.$formData['IdForm'].'\', \''.$formData['IdForm'].'data\', \''.$formData['IdForm'].'card\')"><i class="fas fa-trash"></i></button>';
            }
            return $button;
        })
        ->rawColumns(['aksiSandi', 'aksiStatus', 'aksiEdit'])->make(true);
    }

    public function load($act = '', $tipe = '')
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'usersAddData';
        $this->data['UrlForm'] = 'user';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        if($act == ''){
            $this->data['url'] = route('user.index');
        }elseif ($act=='0') {
            $this->data['url'] = route('user.na');
        }else{
            if ($tipe=='A') {
                $this->data['url'] = route('user.index');
            }elseif($tipe=='M') {
                $this->data['url'] = route('user.siswa');
            }elseif($tipe=='G') {
                $this->data['url'] = route('user.guru');
            }
        }

        return view('users.data', $this->data);
    }

    public function detailSisp($users_sisp, $tipe = '')
    {
        $formData['Pgn'] = $this->getUser();
        $formData['tipe'] = $tipe;
        $formData['users_sisp'] = $users_sisp;
        $formData['User'] = DB::table('users')->where('users_sisp', $users_sisp)->select(['users_id', 'users_sisp', 'users_nm', 'username', 'users_act', 'users_tipe'])->get()->first();
        if ($formData['User']!=null) {
            $formData['User'] = UserController::setData($formData['User'], $tipe);
        }

        return view('users.detailProfil', $formData);
    }

    public function generateSisp($users_sisp, $tipe)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $Sisp = DB::table('sisp')->where('sisp_id', $users_sisp)->select(['sisp_nm', 'sisp_idsp'])->get()->first();
        if ($Sisp!=null) {
            try {
                $this->insertDataU($Sisp->sisp_nm, $users_sisp, $Sisp->sisp_idsp, $Sisp->sisp_idsp, $tipe);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pengguna Berhasil Dibuat"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat Dibuat, '.$e->getMessage()];
            }
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat Disimpan'];
        }
        return response()->json($data, $data['response']['status']);

    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'users_nm' => 'required',
            'username' => 'required|min:6|max:20|alpha_num|unique:users,username',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            "password_confirmation" => "required",
        ];
        $attributes = [
            'users_nm' => 'Nama Pengguna',
            'username' => 'Username',
            'password' => 'Password',
            'password_confirmation' => 'Ulangi Password',
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
            $options = [
                'cost' => 10,
            ];
    
            $UsersModel = new User();
            
            $UsersModel->users_nm = $request->users_nm;
            $UsersModel->username = $request->username;
            $UsersModel->password = $request->password;
    
            $UsersModel->users_tipe = 'A';
            $UsersModel->users_act = '1';
            // $UsersModel->users_ucreate = $this->data['Pgn']->users_id;
            // $UsersModel->users_uupdate = $this->data['Pgn']->users_id;
            $password_baru = password_hash($UsersModel->password, PASSWORD_BCRYPT, $options);
    
            $UsersModel->password = $password_baru;
            $save = $UsersModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pengguna Berhasil Disimpan"
                ];
            }else{
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    static function insertDataU($users_nm, $users_sisp, $username, $password, $users_tipe = 'M')
    {
        $options = [
            'cost' => 10,
        ];
        
        $password_baru = password_hash($password, PASSWORD_BCRYPT, $options);

        $save = DB::table('users')->insertOrIgnore([
            'users_nm' => $users_nm,
            'username' => $username,
            'password' => $password_baru,
            'users_sisp' => $users_sisp,
            'users_tipe' => $users_tipe
        ]);
        if ($save) {
            return true;
        }else{
            return false;
        }
    }

    static function insertDataUN($users_nm, $users_sisp, $username, $password, $users_tipe = 'M')
    {
        $options = [
            'cost' => 10,
        ];
        
        $password_baru = password_hash($password, PASSWORD_BCRYPT, $options);

        $save = DB::table('users')->insertOrIgnore([
            'users_nm' => $users_nm,
            'username' => $username,
            'password' => $password,
            'users_sisp' => $users_sisp,
            'users_tipe' => $users_tipe
        ]);
        if ($save) {
            return true;
        }else{
            return false;
        }
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'users_nm' => 'required',
            'username' => 'required|min:6|max:20|alpha_num|unique:users,username,'.$request->users_id.',users_id',
        ];
        $attributes = [
            'users_nm' => 'Nama Pengguna',
            'username' => 'Username',
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
                $update = DB::table('users')->where('users_id', $request->users_id)->update([
                    'users_nm' => addslashes($request->users_nm),
                    'username' => $request->username,
                    'users_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Pengguna Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateDataPWD(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            "password_confirmation" => "required",
            'password_old' => 'required',
        ];
        
        $attributes = [
            'password' => 'Password Baru',
            'password_confirmation' => 'Ulangi Password Baru',
            'password_old' => 'Password Lama',
        ];

        if (Hash::check($request->password_old, $this->data['Pgn']->password)) {
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
                    $options = [
                        'cost' => 10,
                    ];
                    $password_baru = password_hash($request->password, PASSWORD_BCRYPT, $options);
                    $update = DB::table('users')->where('users_id', $request->users_id)->update([
                        'password' => $password_baru,
                        'users_uupdate' => $this->data['Pgn']->users_id
                    ]);
                    if ($request->users_id == $request->users_id_session) {
                        $request->session()->flush();
                    }
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => "Data Password Pengguna Diubah"
                    ];
                } catch (\Exception $e) {
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Password Pengguna Tidak Dapat Disimpan, '.$e->getMessage()];
                }
            }
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Password Lama Tidak Sesuai'];
        }
        return response()->json($data, $data['response']['status']);
    }
    
    public function updateDataPWDAPI(Request $request)
    {
        $rules = [
            'password' => ['required', 'confirmed'],
            "password_confirmation" => "required",
            'password_old' => 'required',
        ];
        
        $attributes = [
            'password' => 'Password Baru',
            'password_confirmation' => 'Ulangi Password Baru',
            'password_old' => 'Password Lama',
        ];

        if (Hash::check($request->password_old, Auth::user()->password)) {
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
                    $options = [
                        'cost' => 10,
                    ];
                    $password_baru = password_hash($request->password, PASSWORD_BCRYPT, $options);
                    $update = DB::table('users')->where('users_id', Auth::user()->users_id)->update([
                        'password' => $password_baru,
                        'users_uupdate' => Auth::user()->users_id
                    ]);
                    
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => "Data Password Pengguna Diubah"
                    ];
                } catch (\Exception $e) {
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Password Pengguna Tidak Dapat Disimpan, '.$e->getMessage()];
                }
            }
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Password Lama Tidak Sesuai'];
        }
        return response()->json($data['response'], $data['response']['status']);
    }

    public function updateDataReset(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            "password_confirmation" => "required",
        ];
        
        $attributes = [
            'password' => 'Password Baru',
            'password_confirmation' => 'Ulangi Password Baru',
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
                $options = [
                    'cost' => 10,
                ];
                $password_baru = password_hash($request->password, PASSWORD_BCRYPT, $options);
                $update = DB::table('users')->where('users_id', $request->users_id)->update([
                    'password' => $password_baru,
                    // 'users_uupdate' => $this->data['Pgn']->users_id
                ]);
                if ($request->users_id == $request->users_id_session) {
                    $request->session()->flush();
                }
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Password Pengguna Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Password Pengguna Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($users_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $UsersModel = new User();

        $delete = $UsersModel::where('users_id', $users_id)->delete([]);
        if ($delete) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Pengguna Berhasil Dihapus"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat Dihapus'];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function deleteDataBySisp($users_sisp)
    {
        $UsersModel = new User();

        return $UsersModel::where('users_sisp', $users_sisp)->delete([]);
    }

    static function setActBySisp($users_act, $users_sisp)
    {
        $UsersModel = new User();

        return  $UsersModel::where('users_sisp', $users_sisp)->update([
            'users_act' => $users_act
        ]);        
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('flat')]);
    }

    public function setAct($users_act, $users_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $UsersModel = new User();

        $message = "Dinonaktifkan";
        if ($users_act=="1") {
            $message = "Diaktifkan";
        }

        $update = $UsersModel::where('users_id', $users_id)->update([
            'users_act' => $users_act
        ]);
        if ($update) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Pengguna Berhasil ".$message
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Pengguna Tidak Dapat '.$message];
        }
        return response()->json($data, $data['response']['status']);
    }

    static function setData($data, $formData = [])
    {
        if (is_countable($data)) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]['users_tipeAltT'] = "Administrator";
                if ($data[$i]['users_tipe']=='G'||$data[$i]['users_tipe']=='P') {
                    $data[$i]['users_tipeAltT'] = "Pegawai";
                }elseif ($data[$i]['users_tipe']=='M') {
                    $data[$i]['users_tipeAltT'] = "Murid";
                }

                $data[$i]['users_actAltT'] = "Aktif";
                $data[$i]['users_actAltBa'] = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
                $data[$i]['users_actAltBu'] = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengguna\", \"".url('user/setAct/0/'.$data[$i]['users_id'])."\", \"".route('user.load', ['1', $formData['users_tipe']])."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\", \"form\", \"Nonaktfikan\", \"#6c757d\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
                if ($data[$i]['users_act']=="0") {
                    $data[$i]['users_actAltBa'] = "<span class='badge badge-secondary font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['users_actAltBu'] = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengguna\", \"".url('user/setAct/1/'.$data[$i]['users_id'])."\", \"".route('user.load', ['0'])."\", \"".$formData['IdForm']."\", \"".$formData['IdForm']."data\", \"".$formData['IdForm']."card\", \"form\", \"Aktifkan\")' role='button' class='btn btn-secondary font-weight-bold'>TIDAK AKTIF</span>";

                    $data[$i]['users_actAltT'] = "Tidak Aktif";                
                }
            }
        }else{
            if (isset($data->users_tipe)){
                $data->users_tipeAltT = "Administrator";
                if ($data->users_tipe=='G'||$data->users_tipe=='P') {
                    $data->users_tipeAltT = "Pegawai";
                }elseif ($data->users_tipe=='M') {
                    $data->users_tipeAltT = "Murid";
                }
            }

            $data->users_actAltT = "Aktif";
            $data->users_actAltBa = "<span class='badge badge-success font-weight-bold'>AKTIF</span>";
            $data->users_actAltBu = "<span onclick='callOtherTWLoad(\"Menonaktifkan Status Pengguna\", \"".url('user/setAct/0/'.$data->users_id)."\", \"".route('user.detailProfil', [$data->users_sisp, $formData])."\", \"\", \"userDetailProfil\", \"\", \"\", \"Nonaktfikan\", \"#6c757d\")' role='button' class='btn btn-success font-weight-bold'>AKTIF</span>";
            if ($data->users_act=="0") {
                $data->users_actAltBa = "<span class='badge badge-secondary font-weight-bold'>TIDAK AKTIF</span>";

                $data->users_actAltBu = "<span onclick='callOtherTWLoad(\"Mengaktifkan Status Pengguna\", \"".url('user/setAct/1/'.$data->users_id)."\", \"".route('user.detailProfil', [$data->users_sisp, $formData])."\", \"\", \"userDetailProfil\", \"\", \"\", \"Aktifkan\")' role='button' class='btn btn-secondary font-weight-bold'>TIDAK AKTIF</span>";

                $data->users_actAltT = "Tidak Aktif";                
            }

        }
        return $data;
    }

    static function sugestionMessageDelete($nama){
        return 'Penghapusan data ini akan menyebabkan pengguna terkait tidak dapat lagi mengakses data mereka. Apakah Anda yakin ingin melanjutkan menghapus pengguna A.N. '.$nama.'? Jika tidak sepenuhnya yakin, sebaiknya nonaktifkan pengguna ini terlebih dahulu untuk menghindari dampak yang tidak diinginkan.';
    }
}
