<?php

namespace App\Http\Controllers;

use App\Models\SetlokModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetlokController extends Controller
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

            'WebTitle' => 'PENGATURAN LOKASI',
            'PageTitle' => 'Pengaturan Lokasi',
            'BasePage' => 'setlok',
        ];
    }

    public function index(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();
        if ($this->data['Pgn']->users_tipe != "A") {
            return redirect()->intended();
        }

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setlokAddData';
        $this->data['UrlForm'] = 'setlok';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);


        if ($request->ajax()) {
            return SetlokController::loadData($this->data['Pgn'], $this->data);
        }

        return view('setlok.index', $this->data);
    }

    public function load(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'setlokAddData';
        $this->data['UrlForm'] = 'setlok';

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('setlok.data', $this->data);
    }

    static function loadData($Pgn, $formData)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $Setlok = SetlokModel::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'setlok_id', 'setlok_long', 'setlok_lat'])->orderBy('setlok_ord', 'desc')->get();
        return datatables()->of($Setlok)->addColumn('aksiEdit', function ($Setlok) use ($Pgn, $formData) {
            $button = '';
            $button .= '<button type="button" class="btn btn-warning mx-1" onclick="showForm(\'' . $formData['IdForm'] . 'card\', \'block\'); cActForm(\'' . $formData['IdForm'] . '\', \'' . route('setlok.update') . '\'); addFill(\'setlok_id\', \'' . $Setlok->setlok_id . '\'); addFill(\'setlok_long\', \'' . $Setlok->setlok_long . '\'); addFill(\'setlok_lat\', \'' . $Setlok->setlok_lat . '\');"><i class="fas fa-pen"></i></button>';
            return $button;
        })
            ->rawColumns(['aksiEdit'])->make(true);
    }

    public function insertData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setlok_long' => 'required',
            'setlok_lat' => 'required',
        ];
        $attributes = [
            'setlok_long' => 'Longitude',
            'setlok_lat' => 'Latitude',
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->getMessageBag()->all());
            $data['response'] = [
                'status' =>  Response::HTTP_BAD_REQUEST,
                'response' => "danger",
                'type' => "danger",
                'message' => $errorString
            ];
        } else {
            $SetlokModel = new SetlokModel();

            $SetlokModel->setlok_long = $request->setlok_long;
            $SetlokModel->setlok_lat = $request->setlok_lat;
            $SetlokModel->setlok_ucreate = $this->data['Pgn']->users_id;
            $SetlokModel->setlok_uupdate = $this->data['Pgn']->users_id;
            $save = $SetlokModel->save();
            if ($save) {
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Lokasi Berhasil Disimpan"
                ];
            } else {
                $data['response'] = ['status' => 500, 'response' => 'error', 'type' => "danger", 'message' => 'Data Lokasi Tidak Dapat Disimpan'];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        $this->data['Pgn'] = $this->getUser();

        $rules = [
            'setlok_long' => 'required',
            'setlok_lat' => 'required',
        ];
        $attributes = [
            'setlok_long' => 'Longitude',
            'setlok_lat' => 'Latitude',
        ];
        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            $errorString = implode(",", $validator->getMessageBag()->all());
            $data['response'] = [
                'status' =>  Response::HTTP_BAD_REQUEST,
                'response' => "danger",
                'type' => "danger",
                'message' => $errorString
            ];
        } else {
            try {
                $update = DB::table('setlok')->where('setlok_id', $request->setlok_id)->update([
                    'setlok_long' => $request->setlok_long,
                    'setlok_lat' => $request->setlok_lat,
                    'setlok_uupdate' => $this->data['Pgn']->users_id
                ]);
                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Lokasi Berhasil Diubah"
                ];
            } catch (\Exception $e) {
                $data['response'] = ['status' => 500, 'response' => 'error', 'type' => "danger", 'message' => 'Data Lokasi Tidak Dapat Disimpan, ' . $e->getMessage()];
            }
        }
        return response()->json($data, $data['response']['status']);
    }

    static function setRadius($lat1, $lon1, $unit)
    {
        $Setlok = DB::table('setlok')->select(['setlok_long', 'setlok_lat'])->get()->first();
        $lat2 = $Setlok->setlok_lat;
        $lon2 = $Setlok->setlok_long;
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        $area = "1";

        if ($unit == "K") {
            $miles = $miles * 1.609344;
            if ($miles <= 0.5) {
                $area = "1";
            }else {
                $area = "0";
            }
            // return ($miles * 1.609344);
        } else if ($unit == "N") {
            $miles = $miles * 0.8684;
            if ($miles <= 0.5) {
                $area = "1";
            }else {
                $area = "0";
            }
        } else {
            if ($miles <= 0.5) {
                $area = "1";
            }else {
                $area = "0";
            }
        }
        return $area;
    }
}
