<?php

namespace App\Http\Controllers;

use App\Models\AIModel;
use App\Models\BagKModel;
use App\Models\BagModel;
use App\Models\SispModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class BagKController extends Controller
{
    protected $data;

    public function viewDataPpk($bagk_bag)
    {
        $this->data['bagk_bag'] = $bagk_bag;
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagformPpkAddData';
        $this->data['UrlForm'] = 'bagformPpk';

        $this->data['Bag'] = BagModel::where('bag_id', $bagk_bag)->select(['bag_nm', 'bag_str'])->get()->first();

        $this->data['PageTitle'] = 'Data Koordinator PPK '.$this->data['Bag']->bag_nm;

        $this->data['Sisp'] = SispModel::select(['sisp_id', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_idsp'])->orderBy('sisp_nm', 'asc')->get();
        $this->data['Sisp'] = GuruController::setData($this->data['Sisp'], $this->data);

        $this->data['BagK'] = BagKModel::leftJoin('sisp', 'bagk.bagk_sisp', '=', 'sisp.sisp_id')->where('bagk_bag', $bagk_bag)->select(['bagk_id', 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'sisp_pic'])->orderBy('bagk_ord', 'DESC')->get();
        $this->data['BagK'] = GuruController::setData($this->data['BagK'], $this->data);
        
        $this->data['Agent'] = new Agent;

        return view('bagk.dataFormPpk', $this->data);
    }

    public function viewDataSatker($bagk_bag)
    {
        $this->data['bagk_bag'] = $bagk_bag;
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagformPpkAddData';
        $this->data['UrlForm'] = 'bagformPpk';

        $this->data['Bag'] = BagModel::where('bag_id', $bagk_bag)->select(['bag_nm', 'bag_str'])->get()->first();

        $this->data['PageTitle'] = 'Data Koordinator SATKER '.$this->data['Bag']->bag_nm;

        $this->data['Sisp'] = SispModel::select(['sisp_id', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_idsp'])->orderBy('sisp_nm', 'asc')->get();
        $this->data['Sisp'] = GuruController::setData($this->data['Sisp'], $this->data);

        $this->data['BagK'] = BagKModel::leftJoin('sisp', 'bagk.bagk_sisp', '=', 'sisp.sisp_id')->where('bagk_bag', $bagk_bag)->select(['bagk_id', 'sisp_id', 'sisp_idsp', 'sisp_nm', 'sisp_nmd', 'sisp_nmb', 'sisp_tmptlhr', 'sisp_tgllhr', 'sisp_jk', 'sisp_alt', 'sisp_pic'])->orderBy('bagk_ord', 'DESC')->get();
        if($this->data['BagK']!=null){
            $this->data['BagK'] = GuruController::setData($this->data['BagK'], $this->data);
        }
        $this->data['Agent'] = new Agent;

        return view('bagk.dataFormSatker', $this->data);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $BagKModel = new BagKModel();

        try {
            $tipe = 'Koordinator PPK';
            $bagk_satker = $request->bagk_satker;
            if ($bagk_satker=="1") {
                $tipe = 'Koordinator SATKER';
            }

            $Bagk = DB::table('bagk')->where('bagk_bag', $request->bagk_bag)->where('bagk_sisp', $request->bagk_sisp)->select()->get()->first();
            if ($Bagk!=null) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => $tipe.' Tidak Dapat Disimpan, Data Sudah Ada'];
            }else{
                $BagKModel->bagk_bag = $request->bagk_bag;
                $BagKModel->bagk_sisp = $request->bagk_sisp;
                $BagKModel->bagk_satker = $request->bagk_satker;
                $BagKModel->bagk_ucreate = $this->data['Pgn']->users_id;
                $BagKModel->bagk_uupdate = $this->data['Pgn']->users_id;
                $save = $BagKModel->save();
                if ($save) {
                    $data['response'] = [
                        'status' => 200,
                        'response' => "success",
                        'type' => "success",
                        'message' => $tipe." Berhasil Disimpan"
                    ];
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => $tipe.' Tidak Dapat Disimpan'];
                }
            }
        } catch (\Throwable $e) {
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Koordinator Ruangan Tidak Dapat Disimpan, '.$e->getMessage()];
        }
            
        return response()->json($data, $data['response']['status']);
    }

    public function deleteData($bagk_id)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        try {
            DB::table('bagk')->where('bagk_id', $bagk_id)->delete();
            $data['response'] = [
                'status' => 200,
                'response' => "success",
                'type' => "success",
                'message' => "Data Koordinator Berhasil Dihapus"
            ];
        } catch (\Exception $e) {
            $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Koordinator Tidak Dapat Dihapus, '.$e->getMessage()];
        }
        return response()->json($data, $data['response']['status']);
    }

    public function detailSatker($id)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';
        
        $this->data['Bagk'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bagk_id', $id)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_id'])->get()->first();

        $this->data['Bag'] = BagModel::where('bag_prnt', $this->data['Bagk']->bag_id)->select(['bag_nm', 'bag_id'])->get();

        $NamaSatker = $this->data['Bagk']->bag_nm;
        
        $this->data['Now'] = "Data Absensi Terakhir ".$NamaSatker;

        $this->data['Tahun'] = DB::table('absen')->select(DB::raw('YEAR(absen_tgl) year'))->groupBy('year')->orderBy('year', 'desc')->get();

        $this->data['url'] = route('bagk.absenSatker', [$this->data['Bagk']->bagk_bag]);
        
        $this->data['Agent'] = new Agent;
        
        $this->data['paramCtk'] = [
            'filters_satker' => $this->data['Bagk']->bagk_bag, 
            'filters_ppk' => '', 
            'filtert_tgl' => '', 
        ];
        $this->data['filtert_bagk'] = 'S';
        
        return view('bagk.satker', $this->data);
    }

    public function dataAbsenSatker(Request $request, $bag){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        if ($request->ajax()) {
            return AbsenController::loadData($this->data['Pgn'], $this->data, $bag);
        }
    }

    public function loadAbsenSatker($Bag){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';
        $this->data['Bagk'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bagk_id', $Bag)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_prnt', 'bag_id'])->get()->first();
        $this->data['Bag'] = BagModel::where('bag_prnt', $this->data['Bagk']->bag_id)->select(['bag_nm', 'bag_id'])->get();

        $NamaSatker = $this->data['Bagk']->bag_nm;

        $this->data['Now'] = "Data Absensi Terakhir ".$NamaSatker;

        $this->data['url'] = route('bagk.absenSatker', [$this->data['Bagk']->bagk_bag]);

        $this->data['Agent'] = new Agent;
        $this->data['paramCtk'] = [
            'filters_satker' => $this->data['Bagk']->bagk_bag, 
            'filters_ppk' => '', 
            'filtert_tgl' => '', 
        ];
        $this->data['filtert_bagk'] = 'S';
        return view('absen.data', $this->data);
    }

    public function detailPpk($id)
    {
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';
        
        $this->data['Bagk'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bagk_id', $id)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_prnt', 'bag_id'])->get()->first();

        $this->data['Bagksatker'] = [];
        if ($this->data['Bagk']!=null) {
            $this->data['Bagksatker'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bag_prnt', $this->data['Bagk']->bag_prnt)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_id'])->get()->first();
        }


        $NamaPpk = $this->data['Bagk']->bag_nm;

        $this->data['Now'] = "Data Absensi Terakhir ".$NamaPpk;

        $this->data['Tahun'] = DB::table('absen')->select(DB::raw('YEAR(absen_tgl) year'))->groupBy('year')->orderBy('year', 'desc')->get();

        $this->data['url'] = route('bagk.absenPpk', [$this->data['Bagk']->bagk_bag]);
        
        $this->data['Agent'] = new Agent;
        $this->data['paramCtk'] = [
            'filters_satker' => $this->data['Bagk']->bag_prnt, 
            'filters_ppk' => $this->data['Bagk']->bagk_bag,
            'filtert_tgl' => '', 
        ];
        $this->data['filtert_bagk'] = 'P';
        return view('bagk.ppk', $this->data);
    }

    public function dataAbsenPpk(Request $request, $bag){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        if ($request->ajax()) {
            return AbsenController::loadData($this->data['Pgn'], $this->data, '', $bag);
        }
    }

    public function loadAbsenPpk($Bag){
        $this->data['Pgn'] = $this->getUser();
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'bagkAddData';
        $this->data['UrlForm'] = 'bagk';
        $this->data['Bagk'] = DB::table('bagk')->join('bag', 'bagk.bagk_bag', '=', 'bag.bag_id')->where('bagk_id', $Bag)->select(['bagk_bag', 'bag_nm', 'bagk_satker', 'bagk_id', 'bag_prnt', 'bag_id'])->get()->first();

        $NamaPpk = $this->data['Bagk']->bag_nm;

        $this->data['Now'] = "Data Absensi Terakhir ".$NamaPpk;

        $this->data['url'] = route('bagk.absenPpk', [$this->data['Bagk']->bagk_bag]);

        $this->data['Agent'] = new Agent;
        $this->data['paramCtk'] = [
            'filters_satker' => $this->data['Bagk']->bag_prnt, 
            'filters_ppk' => $this->data['Bagk']->bagk_bag,
            'filtert_tgl' => '', 
        ];

        $this->data['filtert_bagk'] = 'P';
        return view('absen.data', $this->data);
    }

    
}
