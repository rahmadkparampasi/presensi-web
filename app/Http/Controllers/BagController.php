<?php

namespace App\Http\Controllers;

use App\Http\Resources\BagResource;
use App\Models\BagModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BagController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [
            'mOp' => 'mOBag',
            'pAct' => '',
            'cAct' => '',
            'cmAct' => '',
            'scAct' => '',

            'WebTitle' => 'PENGATURAN BAGIAN',
            'PageTitle' => 'Pengaturan Bagian',
            'BasePage' => 'bag',
        ];
    }

    public function index()
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe!="A") {
            return redirect()->intended();
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagAddData';
        $this->data['UrlForm'] = 'bag';
 
        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('bag.index', $this->data);
    }

    public function getDataJsonKelas($bag_prnt)
    {
        $this->data['Kelas'] = BagModel::where('bag_prnt', $bag_prnt)->select('bag_id', 'bag_nm')->orderBy('bag_ord', 'ASC')->get();

        $this->data['KelasN'] = [];
        for ($i=0; $i < count($this->data['Kelas']); $i++) { 
            $this->data['KelasN'][$i]['optValue'] = '';
            $this->data['KelasN'][$i]['optValue'] = $this->data['Kelas'][$i]['bag_id'];

            $this->data['KelasN'][$i]['optText'] = '';
            $this->data['KelasN'][$i]['optText'] = $this->data['Kelas'][$i]['bag_nm'];
        }
        return $this->data['KelasN'];
    }
    
    static function getDataJsonBagStat($bag_nm)
    {
        $Bag = BagModel::where('bag_thn', '1')->where('bag_nm','LIKE', '%'.$bag_nm.'%')->select('bag_id', 'bag_nm')->orderBy('bag_nm', 'ASC')->get();
        return $Bag;
    }

    public function viewDataButton($bag_id = '')
    {
        $this->data['bag_id'] = $bag_id;
        $this->data['Pgn'] = $this->getUser();
        $this->data['Bag'] = BagModel::where('bag_id', $bag_id)->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get()->first();

        return view('bag.dataButton', $this->data);
    }

    public function viewDataForm($bag_id = '')
    {
        $this->data['bag_id'] = $bag_id;
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagformAddData';
        $this->data['UrlForm'] = 'bagform';

        $this->data['Bag'] = BagModel::where('bag_id', $bag_id)->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get()->first();

        $this->data['Baginm'] = BaginmModel::where('baginm_bag', $bag_id)->select(['baginm_inm',])->get()->first();
        if ($this->data['Baginm']!=null) {
            $this->data['Baginm'] = explode(',', $this->data['Baginm']->baginm_inm);
        }else{
            $this->data['Baginm'] = [];
        }

        return view('bag.dataForm', $this->data);
    }

    public function viewDataFormUser($bag_id)
    {
        $this->data['bag_id'] = $bag_id;
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagformUserAddData';
        $this->data['UrlForm'] = 'bagformUser';

        $this->data['Bag'] = BagModel::where('bag_id', $bag_id)->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get()->first();

        $this->data['User'] = User::where('users_bag', $bag_id)->select(['users_id', 'users_nm', 'username', 'users_bag', 'users_tipe', 'users_act', 'users_tipe'])->orderBy('users_ord', 'asc')->get();
        $this->data['User'] = UserController::setDataStat($this->data['User']);

        return view('bag.dataFormUser', $this->data);
    }

    static function getDataBag($bag = '')
    {
        return BagModel::where('bag_id', $bag)->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get()->first();
    }

    static function getDataStat()
    {
        return BagModel::where('bag_str', '2')->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get();
    }

    public function getDataSatker()
    {
        $Bag = BagModel::where('bag_str', '2')->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get();
        return BagResource::collection($Bag);
    }

    public function getDataPpk($bag_prnt)
    {
        $Bag = BagModel::where('bag_prnt', $bag_prnt)->select(['bag_id', 'bag_nm'])->orderBy('bag_ord', 'asc')->get();
        return BagResource::collection($Bag);
    }

    static function getDataNodeBag($bag = '')
    {
        $Bag = BagModel::where('bag_prnt', $bag)->select(['bag_id'])->orderBy('bag_ord', 'asc')->get();
        $NewBag = [];
        for ($i=0; $i < count($Bag); $i++) {
            array_push($NewBag, $Bag[$i]->bag_id);
        }
        array_push($NewBag, $bag);

        return $NewBag;
    }

    public function getParentByJson()
    {
        $parent_category_id = "";
        $dataByParent = BagModel::select(['bag_id', 'bag_nm', 'bag_prnt', 'bag_act', 'bag_str'])->orderBy('bag_ord', 'desc')->get();
        $data = [];
        
        foreach ($dataByParent as $val) {
            $data = $this->get_node_data($parent_category_id);
        }
        return response()->json($data, 200);
    }

    public function get_node_data($bag_prnt = '')
    {
        $dataByParent = BagModel::where('bag_prnt', $bag_prnt)->select(['bag_id', 'bag_nm', 'bag_prnt', 'bag_act', 'bag_str'])->orderBy('bag_ord', 'desc')->get();
        // $dataByParent = $this->setDB('getByParent', $rs_rmr_prnt);
        $output = array();
        foreach ($dataByParent as $row) {
            $sub_array = array();
            
            $sub_array['text'] = $row['bag_nm'];
            $sub_array['textAlt'] = $row['bag_nm'];
            $sub_array['sts'] = $row['bag_act'];
            $sub_array['idEx'] = $row['bag_id'];
            $sub_array['str'] = $row['bag_str'];


            $sub_array['nodes'] = array_values($this->get_node_data($row['bag_id']));
            $sub_array['tags'] = [(string)count($sub_array['nodes'])];
            if (empty($sub_array['nodes'])) {
                unset($sub_array['nodes']);
            }
            $output[] = $sub_array;
        }
        return $output;
    }

    public function insertData(Request $request)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");         

        $BagModel = new BagModel();

        $this->data['Pgn'] = $this->getUser();
        
        $BagModel->bag_nm = addslashes($request->bag_nm);
        $BagModel->bag_prnt = $request->bag_prnt;
        $BagModel->bag_thn = $request->bag_thn;
        $BagModel->bag_desk = addslashes($request->bag_desk);
        $bag_str = (int)$request->bag_str + 1; 
        $BagModel->bag_str = $bag_str;
        
        //Edit: Perbaiki Data User
        // $BagModel->bag_ucreate = $this->data['Pgn']->users_id;
        // $BagModel->bag_uupdate = $this->data['Pgn']->users_id;
        
        $save = $BagModel->save();
        if ($save) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Bagian Berhasil Disimpan"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Bagian Tidak Dapat Disimpan'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function insertDataBaru(Request $request)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");         

        $BagModel = new BagModel();

        $this->data['Pgn'] = $this->getUser();
        
        $BagModel->bag_nm = addslashes($request->bag_nmRs);
        $BagModel->bag_prnt = '';
        $BagModel->bag_str = 1;
        
        //Edit: Perbaiki Data User
        // $BagModel->bag_ucreate = $this->data['Pgn']->users_id;
        // $BagModel->bag_uupdate = $this->data['Pgn']->users_id;
        
        $save = $BagModel->save();
        if ($save) {
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Unit Berhasil Disimpan"
            ];
        }else{
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Unit Tidak Dapat Disimpan'];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");         

        $this->data['Pgn'] = $this->getUser();

        try {
            $update = DB::table('bag')->where('bag_id', $request->bag_id)->update([
                'bag_nm' => addslashes($request->bag_nm),
                //Edit: Perbaiki Data User
                // 'bag_uupdate' => $this->data['Pgn']->users_id
            ]);
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Bagian Berhasil Diubah"
            ];
        } catch (\Exception $e) {
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Bagian Tidak Dapat Diubah, '.$e->getMessage()];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($bag_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        try {
            DB::table('bag')->where('bag_id', $bag_id)->delete();
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Bagian Berhasil Dihapus"
            ];
        } catch (\Exception $e) {
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Bagian Tidak Dapat Dihapus, '.$e->getMessage()];
        }
        return response()->json($data, $data['response']['status']);
    }
}
