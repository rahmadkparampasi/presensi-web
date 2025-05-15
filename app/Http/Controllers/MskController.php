<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;

class MskController extends Controller
{
    protected $data;
    protected $session;

    public function __construct(Request $request)
    {
        $this->data = [
            'WebTitle' => 'MASUK',
            'PageTitle' => 'Masuk',
            'BasePage' => 'msk',
        ];
    }

    public function index()
    {
        if (Auth::user()) {
            return redirect()->intended('/home');
        }
        $this->data['MethodForm'] = 'insertData';
        $this->data['IdForm'] = 'mskAddData';
        $this->data['UrlForm'] = 'msk';

        $this->data['MethodForm1'] = substr($this->data['MethodForm'], 0, 10);

        $this->data['DisplayForm'] = $this->setDisplay($this->data['MethodForm']);

        return view('msk.index', $this->data);
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('flat')]);
    }

    public function authMasuk(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            // 'captcha' => 'required|captcha'
        ]);
        $credentials = $request->only('username','password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->users_mailconf == '0') {
                Auth::logout();
                return back()->with(['loginError'=> 'Email Belum Di Konfirmasi']);
            }
            if (Auth::user()->users_act == '0') {
                Auth::logout();
                return back()->with(['loginError'=> 'Data Pengguna Telah Di Nonaktifkan, Silahkan Hubungi Administrator']);
            }
            $request->session()->regenerate();
            $user = Auth::user();
            if($user){
                return redirect()->intended('/home');            
            }else{
                return back()->with(['loginError'=> 'Tidak Ada Pengguna Yang Dimaksud']);
            }
        }
        return back()->withErrors(['loginError' => 'Username Atau Password Salah'])->withInput($request->input());
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
