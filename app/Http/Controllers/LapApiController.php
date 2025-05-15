<?php

namespace App\Http\Controllers;

use App\Http\Resources\LapResource;
use App\Models\LapModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LapApiController extends Controller
{
    public function detailProfil()
    {
        $data['MethodForm'] = 'insertData';
        $data['IdForm'] = 'lapAddData';
        $data['UrlForm'] = 'lap';
        $data['urlLoad'] = route('lap.loadProfil', [Auth::user()->users_sisp]);

        $data['Sisp'] = DB::table('sisp')->where('sisp_id', Auth::user()->users_sisp)->select(['sisp_id', 'sisp_idsp', 'sisp_nm'])->get()->first();

        $data['lap_sisp'] = Auth::user()->users_sisp;
        
        $Lap = LapController::loadData(Auth::user(), $data, 0, Auth::user()->users_sisp, '', false, false);

        return new LapResource($Lap);
    }

    public function getYear()
    {
        $data = [];
        $date = (int)date("Y")-2;
        for ($i=0; $i < 3; $i++) { 
            $data[$i]['year'] = $date+$i;
        }

        return new LapResource($data);
    }

    public function getMonth()
    {
        
        $data[0]['name'] = 'JANUARI';
        $data[0]['val'] = '1';

        $data[1]['name'] = 'FEBRUARI';
        $data[1]['val'] = '2';

        $data[2]['name'] = 'MARET';
        $data[2]['val'] = '3';

        $data[3]['name'] = 'APRIL';
        $data[3]['val'] = '4';

        $data[4]['name'] = 'MEI';
        $data[4]['val'] = '5';

        $data[5]['name'] = 'JUNI';
        $data[5]['val'] = '6';

        $data[6]['name'] = 'JULI';
        $data[6]['val'] = '7';

        $data[7]['name'] = 'AGUSTUS';
        $data[7]['val'] = '8';

        $data[8]['name'] = 'SEPTEMBER';
        $data[8]['val'] = '9';

        $data[9]['name'] = 'OKTOBER';
        $data[9]['val'] = '10';

        $data[10]['name'] = 'NOVEMBER';
        $data[10]['val'] = '11';

        $data[11]['name'] = 'DESEMBER';
        $data[11]['val'] = '12';
        

        return new LapResource($data);
    }

    public function insertData(Request $request)
    {
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'lap_bln' => 'required',
            'lap_thn' => 'required',
            'lap_fl' => 'required|mimes:pdf',
        ];
        $attributes = [
            'lap_bln' => 'Bulan Laporan',
            'lap_thn' => 'Tahun Laporan',
            'lap_fl' => 'Berkas Laporan',
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
            $Lap = DB::table('lap')->where('lap_sisp', Auth::user()->users_sisp)->where('lap_bln', $request->lap_bln)->where('lap_thn', $request->lap_thn)->get()->first();
            if ($Lap!=null) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan, Sudah Ada Laporan Yang Sama'];
            }else{
                $LapModel = new LapModel();
                
                $LapModel->lap_sisp = Auth::user()->users_sisp;
                $LapModel->lap_bln = $request->lap_bln;
                $LapModel->lap_thn = $request->lap_thn;
    
                $guessExtension = $request->file('lap_fl')->guessExtension();
                $filename = 'file-lap-'.date("Y-m-d-H-i-s").".".$guessExtension;
    
                $LapModel->lap_fl = $filename;
                
                $save = $LapModel->save();
                if ($save) {            
                    $file = $request->file('lap_fl')->storeAs('/public/uploads', $filename );
                    if ($file) {
                        $data['response'] = [
                            'status' => 200,
                            'response' => "success",
                            'type' => "success",
                            'message' => "Data Laporan Berhasil Disimpan"];
                    }else{
                        $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan, Terdapat Masalah Pada Berkas Surat Laporan'];
                    }
                }else{
                    $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Laporan Tidak Dapat Disimpan'];
                }
            }
        }
        return response()->json($data['response'], $data['response']['status']);
    }

    public function updateData(Request $request)
    {
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.\,]+$/u', $value);
        }, ':attribute Tidak boleh menggunakan karakter selain huruf, spasi, titik (.) dan koma (.)');

        $rules = [
            'lap_id' => 'required',
            'lap_fl' => 'required|mimes:pdf|max:1000',
        ];
        $attributes = [
            'lap_id' => 'ID Laporan',
            'lap_fl' => 'Berkas Laporan',
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
            $guessExtension = $request->file('lap_fl')->guessExtension();
            $filename = 'file-lap-'.date("Y-m-d-H-i-s").".".$guessExtension;

            try {
                $update = DB::table('lap')->where('lap_id', $request->lap_id)->update([
                    'lap_c' => addslashes($request->lap_c),
                    'lap_fl' => $filename,
                    'lap_uupdate' =>  Auth::user()->users_id
                ]);
                $file = $request->file('lap_fl')->storeAs('/public/uploads', $filename );

                $data['response'] = [
                    'status' => 200,
                    'response' => "success",
                    'type' => "success",
                    'message' => "Data Perubahan Laporan Berhasil Disimpan"
                ];
            } catch (\Throwable $e) {
                $data['response'] = ['status' => 500, 'response' => 'error','type' => "danger", 'message' => 'Data Perubahan Laporan Tidak Dapat Disimpan, '.$e->getMessage()];
            }
        }
        return response()->json($data['response'], $data['response']['status']);
    }
}
