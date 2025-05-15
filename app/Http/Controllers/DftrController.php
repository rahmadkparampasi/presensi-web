<?php

namespace App\Http\Controllers;

use App\Models\SispModel;
use App\Models\UsersModel;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DftrController extends Controller
{
    protected $data;
    protected $Da;
    protected $session;

    public function __construct(Request $request)
    {
        $this->data = [
            'WebTitle' => 'DAFTAR',
            'PageTitle' => 'Daftar',
            'BasePage' => 'dftr',
        ];
    }

    public function index(Request $request)
    {
        if (Auth::user()) {
            return redirect()->intended('/home');
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'dftrAddData';
        $this->data['UrlForm'] = 'dftr';

        $this->data['Prov'] = ProvController::getData();
        $this->data['Kec'] = KecController::getData();
        $this->data['Agama'] = AgController::getDataActStat();
        $this->data['Setkrj'] = SetkrjController::getDataActStat();

        $this->data['Setpd'] = SetpdController::getDataActStat();
        $this->data['Setcks'] = SetcksController::getDataActStat();
        $this->data['Settks'] = SettksController::getDataActStat();

        $this->data['Setkatpes_ps'] = SetkatpesController::getDataPsStat();

        $this->data['MethodForm1'] = substr($this->data['MethodForm'], 0, 10);

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('dftr.index', $this->data);
    }

    public function indexGuru(Request $request)
    {
        if (Auth::user()) {
            return redirect()->intended('/home');
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'dftrGuruAddData';
        $this->data['UrlForm'] = 'dftrGuru';

        $this->data['Prov'] = ProvController::getData();

        $this->data['Agama'] = AgController::getDataActStat();

        $this->data['Setstspeg'] = SetstspegController::getDataActStat();
        $this->data['Bag'] = BagController::getDataStat();

        $this->data['Setkatpes_ps'] = SetkatpesController::getDataPgStat();

        $this->data['MethodForm1'] = substr($this->data['MethodForm'], 0, 10);

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('dftr.indexGuru', $this->data);
    }

    

    public function success()
    {
        return view('dftr.success', $this->data);
    }
}
