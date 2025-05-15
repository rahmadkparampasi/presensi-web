<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AbsenExcelController;
use App\Http\Controllers\AbsenPdfController;
use App\Http\Controllers\AgController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\BagKController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DftrController;
use App\Http\Controllers\FakeController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruCtkController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KabController;
use App\Http\Controllers\KecController;
use App\Http\Controllers\LapController;
use App\Http\Controllers\MskController;
use App\Http\Controllers\PrivacyControlller;
use App\Http\Controllers\SetcksController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\SetkatiController;
use App\Http\Controllers\SetkatpesController;
use App\Http\Controllers\SetkatpesjController;
use App\Http\Controllers\SetkrjController;
use App\Http\Controllers\SetlokController;
use App\Http\Controllers\SetpdController;
use App\Http\Controllers\SetstspegController;
use App\Http\Controllers\SettksController;
use App\Http\Controllers\SispController;
use App\Http\Controllers\SispiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaCtkController;
use App\Http\Controllers\SiswaDController;
use App\Http\Controllers\SurveiController;
use App\Http\Controllers\SurveiqController;
use App\Http\Controllers\SurveisController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [DftrController::class, 'indexGuru'])->name('register');
// Route::get('/registerGuru', [DftrController::class, 'indexGuru'])->name('registerGuru');
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('register/insert', [SispController::class, 'insertDataSiswa'])->name('register.insert');
Route::post('register/insertGuru', [SispController::class, 'insertDataGuru'])->name('register.insertGuru');
Route::get('register/success', [DftrController::class, 'success']);
Route::get('register/reload-captcha', [UserController::class, 'reloadCaptcha']);

Route::get('fake/comp', [FakeController::class, 'comp'])->middleware('auth');
Route::get('fake/person', [FakeController::class, 'person'])->middleware('auth');

Route::get('generate', [SispController::class, 'generateUser']);
// Route::get('generatePic', [SispController::class, 'generatePhoto']);
// Route::get('generateBarcode', [SispController::class, 'generateBc']);
// Route::get('generateKartu', [SispController::class, 'generateKrt']);
// Route::get('testingAbsen', [AbsenController::class, 'tesAbsen']);
// Route::get('checkNisnExcel', [SispController::class, 'checkNISNByExcel']);

// Route::get('sisp/searchLNisn', [SispController::class, 'checkLenghtNisn']);
// Route::get('sisp/searchNama', [SispController::class, 'checkNama']);
Route::get('sisp/migrasi', [SispController::class, 'migrateUser']);

Route::controller(MskController::class)->group(function(){
    Route::get('/', 'index')->name('masuk');
    Route::post('masuk/authMasuk', 'authMasuk')->name('masuk.auth');
    Route::get('/masuk/authKeluar', 'logout');
    Route::get('/reload-captcha', 'reloadCaptcha');
});
Route::get('privacy', [PrivacyControlller::class, 'index'])->name('pri.index');

Route::get('/kab/getDataJson/{kab_prov}', [KabController::class, 'getDataJson']);
Route::get('kec/getDataJson/{kec_kab}', [KecController::class, 'getDataJson']);
Route::get('desa/getDataJson/{desa_kec}', [DesaController::class, 'getDataJson']);

Route::get('ag', [AgController::class, 'index'])->middleware('auth')->name('ag.index');
Route::get('ag/load', [AgController::class, 'load'])->middleware('auth')->middleware('ajax');
Route::post('ag/insert', [AgController::class, 'insertData'])->middleware('auth')->name('ag.insert');
Route::post('ag/update', [AgController::class, 'updateData'])->middleware('auth')->name('ag.update');
Route::get('ag/delete/{ag_id}', [AgController::class, 'deleteData'])->middleware('auth');
Route::get('ag/setAct/{ag_act}/{ag_id}', [AgController::class, 'setAct'])->middleware('auth');

Route::get('bag', [BagController::class, 'index'])->name('bag.index')->middleware('auth');
Route::get('bag/getTreeData', [BagController::class, 'getParentByJson'])->name('bag.getTree')->middleware('auth')->middleware('ajax');
Route::get('bag/getDataButton/{bag_id?}', [BagController::class, 'viewDataButton'])->name('bag.getDataButton')->middleware('ajax')->middleware('auth');
Route::get('bag/getDataForm/{bag_id?}', [BagController::class, 'viewDataForm'])->name('bag.getDataForm')->middleware('ajax')->middleware('auth');
Route::get('bag/getDataFormUser/{bag_id?}', [BagController::class, 'viewDataFormUser'])->name('bag.getDataFormUser')->middleware('ajax')->middleware('auth');
Route::post('bag/insert', [BagController::class, 'insertData'])->name('bag.insert')->middleware('ajax')->middleware('auth');
Route::post('bag/insertBaru', [BagController::class, 'insertDataBaru'])->name('bag.insertBaru')->middleware('ajax')->middleware('auth');
Route::post('bag/update', [BagController::class, 'updateData'])->name('bag.update')->middleware('ajax')->middleware('auth');
Route::get('bag/delete/{bag_id}', [BagController::class, 'deleteData'])->middleware('auth');
Route::get('bag/getDataJsonKelas/{bag_nm}', [BagController::class, 'getDataJsonKelas']);

Route::get('bagk/getDataPpk/{bagk_bag?}', [BagKController::class, 'viewDataPpk'])->name('bagk.getDataPpk')->middleware('ajax')->middleware('auth');
Route::get('bagk/getDataSatker/{bagk_bag?}', [BagKController::class, 'viewDataSatker'])->name('bagk.getDataSatker')->middleware('ajax')->middleware('auth');
Route::post('bagk/insert', [BagKController::class, 'insertData'])->name('bagk.insert')->middleware('ajax')->middleware('auth');
Route::get('bagk/delete/{bag_id}', [BagKController::class, 'deleteData'])->middleware('auth');

Route::get('bagk/satker/{id?}', [BagKController::class, 'detailSatker'])->name('bagk.satker')->middleware('auth')->middleware('ajax');
Route::get('bagk/absenSatker/{bag?}', [BagKController::class, 'dataAbsenSatker'])->name('bagk.absenSatker')->middleware('auth')->middleware('ajax');
Route::get('absen/loadAbsenSatker/{bag?}', [BagKController::class, 'loadAbsenSatker'])->name('bagk.loadAbsenSatker')->middleware('ajax')->middleware('auth');

Route::get('bagk/ppk/{id?}', [BagKController::class, 'detailPpk'])->name('bagk.ppk')->middleware('auth')->middleware('ajax');
Route::get('bagk/absenPpk/{bag?}', [BagKController::class, 'dataAbsenPpk'])->name('bagk.absenPpk')->middleware('auth')->middleware('ajax');
Route::get('absen/loadAbsenPpk/{bag?}', [BagKController::class, 'loadAbsenPpk'])->name('bagk.loadAbsenPpk')->middleware('ajax')->middleware('auth');


Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware('auth');
Route::get('user/guru', [UserController::class, 'guru'])->name('user.guru')->middleware('auth');
Route::get('user/siswa', [UserController::class, 'siswa'])->name('user.siswa')->middleware('auth');
Route::get('user/pegawai', [UserController::class, 'pegawai'])->name('user.pegawai')->middleware('auth');
Route::get('user/na', [UserController::class, 'nonaktif'])->name('user.na')->middleware('auth');

Route::get('user/load/{act?}/{tipe?}', [UserController::class, 'load'])->name('user.load')->middleware('ajax')->middleware('auth');
Route::post('user/insert', [UserController::class, 'insertData'])->name('user.insert')->middleware('auth');
Route::post('user/insertBag', [UserController::class, 'insertDataBag'])->middleware('auth')->name('user.insertBag')->middleware('ajax');
Route::post('user/update', [UserController::class, 'updateData'])->middleware('auth')->name('user.update');
Route::post('user/updateBag', [UserController::class, 'updateDataBag'])->middleware('auth')->name('user.updateBag')->middleware('ajax');
Route::post('user/updatePwd', [UserController::class, 'updateDataPWD'])->middleware('auth')->name('user.updatePwd');
Route::post('user/updateReset', [UserController::class, 'updateDataReset'])->middleware('auth')->name('user.updateReset');
Route::get('user/delete/{user_id}', [UserController::class, 'deleteData'])->middleware('auth');
Route::get('user/setAct/{user_act}/{user_id}', [UserController::class, 'setAct'])->middleware('auth');
Route::get('user/detailProfil/{id}/{tipe?}', [UserController::class, 'detailSisp'])->name('user.detailProfil')->middleware('ajax')->middleware('auth');
Route::get('user/generate/{id}/{tipe}', [UserController::class, 'generateSisp'])->name('user.generate')->middleware('auth');

Route::get('pengaturan', [SetController::class, 'index'])->name('set.index')->middleware('auth');

Route::get('setpd', [SetpdController::class, 'index'])->name('setpd.index')->middleware('auth');
Route::get('setpd/load', [SetpdController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setpd/insert', [SetpdController::class, 'insertData'])->name('setpd.insert')->middleware('auth');
Route::post('setpd/update', [SetpdController::class, 'updateData'])->middleware('auth')->name('setpd.update');
Route::get('setpd/delete/{setpd_id}', [SetpdController::class, 'deleteData'])->middleware('auth');
Route::get('setpd/setAct/{setpd_act}/{setpd_id}', [SetpdController::class, 'setAct'])->middleware('auth');

Route::get('setlok', [SetlokController::class, 'index'])->name('setlok.index')->middleware('auth');
Route::get('setlok/load', [SetlokController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setlok/insert', [SetlokController::class, 'insertData'])->name('setlok.insert')->middleware('auth');
Route::post('setlok/update', [SetlokController::class, 'updateData'])->middleware('auth')->name('setlok.update');

Route::get('setkrj', [SetkrjController::class, 'index'])->name('setkrj.index')->middleware('auth');
Route::get('setkrj/load', [SetkrjController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setkrj/insert', [SetkrjController::class, 'insertData'])->name('setkrj.insert')->middleware('auth');
Route::post('setkrj/update', [SetkrjController::class, 'updateData'])->middleware('auth')->name('setkrj.update');
Route::get('setkrj/delete/{setkrj_id}', [SetkrjController::class, 'deleteData'])->middleware('auth');
Route::get('setkrj/setAct/{setkrj_act}/{setkrj_id}', [SetkrjController::class, 'setAct'])->middleware('auth');

Route::get('setkati', [SetkatiController::class, 'index'])->name('setkati.index')->middleware('auth');
Route::get('setkati/load', [SetkatiController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setkati/insert', [SetkatiController::class, 'insertData'])->name('setkati.insert')->middleware('auth');
Route::post('setkati/update', [SetkatiController::class, 'updateData'])->middleware('auth')->name('setkati.update');
Route::get('setkati/delete/{setkati_id}', [SetkatiController::class, 'deleteData'])->middleware('auth');
Route::get('setkati/setAct/{setkati_act}/{setkati_id}', [SetkatiController::class, 'setAct'])->middleware('auth');

Route::get('setcks', [SetcksController::class, 'index'])->name('setcks.index')->middleware('auth');
Route::get('setcks/load', [SetcksController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setcks/insert', [SetcksController::class, 'insertData'])->name('setcks.insert')->middleware('auth');
Route::post('setcks/update', [SetcksController::class, 'updateData'])->middleware('auth')->name('setcks.update');
Route::get('setcks/delete/{setcks_id}', [SetcksController::class, 'deleteData'])->middleware('auth');
Route::get('setcks/setAct/{setcks_act}/{setcks_id}', [SetcksController::class, 'setAct'])->middleware('auth');

Route::get('settks', [SettksController::class, 'index'])->name('settks.index')->middleware('auth');
Route::get('settks/load', [SettksController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('settks/insert', [SettksController::class, 'insertData'])->name('settks.insert')->middleware('auth');
Route::post('settks/update', [SettksController::class, 'updateData'])->middleware('auth')->name('settks.update');
Route::get('settks/delete/{settks_id}', [SettksController::class, 'deleteData'])->middleware('auth');
Route::get('settks/setAct/{settks_act}/{settks_id}', [SettksController::class, 'setAct'])->middleware('auth');

Route::get('setstspeg', [SetstspegController::class, 'index'])->name('setstspeg.index')->middleware('auth');
Route::get('setstspeg/load', [SetstspegController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setstspeg/insert', [SetstspegController::class, 'insertData'])->name('setstspeg.insert')->middleware('auth');
Route::post('setstspeg/update', [SetstspegController::class, 'updateData'])->middleware('auth')->name('setstspeg.update');
Route::get('setstspeg/delete/{setstspeg_id}', [SetstspegController::class, 'deleteData'])->middleware('auth');
Route::get('setstspeg/setAct/{setstspeg_act}/{setstspeg_id}', [SetstspegController::class, 'setAct'])->middleware('auth');

Route::get('setkatpes', [SetkatpesController::class, 'index'])->name('setkatpes.index')->middleware('auth');
Route::get('setkatpes/load', [SetkatpesController::class, 'load'])->middleware('ajax')->middleware('auth');
Route::post('setkatpes/insert', [SetkatpesController::class, 'insertData'])->name('setkatpes.insert')->middleware('auth');
Route::post('setkatpes/update', [SetkatpesController::class, 'updateData'])->middleware('auth')->name('setkatpes.update');
Route::get('setkatpes/delete/{setkatpes_id}', [SetkatpesController::class, 'deleteData'])->middleware('auth');
Route::get('setkatpes/setAct/{setkatpes_act}/{setkatpes_id}', [SetkatpesController::class, 'setAct'])->middleware('auth');
Route::get('setkatpes/setPS/{setkatpes_act}/{setkatpes_id}', [SetkatpesController::class, 'setPS'])->middleware('auth');
Route::get('setkatpes/setPG/{setkatpes_act}/{setkatpes_id}', [SetkatpesController::class, 'setPG'])->middleware('auth');
Route::get('setkatpes/setPU/{setkatpes_pu}/{setkatpes_id}', [SetkatpesController::class, 'setPU'])->middleware('auth');
Route::get('setkatpes/setSH/{setkatpes_sh}/{setkatpes_id}', [SetkatpesController::class, 'setSH'])->middleware('auth');

Route::post('setkatpesj/insert', [SetkatpesjController::class, 'insertData'])->name('setkatpesj.insert')->middleware('auth');
Route::get('setkatpesj/delete/{setkatpesj_id}', [SetkatpesjController::class, 'deleteData'])->middleware('auth');

Route::get('sisp/detailBcKrtProf/{id?}', [SispController::class, 'detailBcKrt'])->name('sisp.detailBcKrtProf')->middleware('ajax')->middleware('auth');
Route::get('sisp/checkKartu/{id?}', [SispController::class, 'checkKrt'])->name('sisp.checkKartu')->middleware('auth');
Route::post('sisp/updateKartu', [SispController::class, 'updateDataKartu'])->middleware('auth')->name('sisp.updateKartu');
Route::get('sisp/ajaxKrt/{id?}', [SispController::class, 'loadAjaxKrt'])->name('sisp.ajaxKrt')->middleware('ajax')->middleware('auth');

Route::get('sisp', [GuruController::class, 'index'])->name('sisp.index')->middleware('auth');
Route::post('sisp/insert', [GuruController::class, 'insertData'])->name('sisp.insert');
Route::get('sisp/load/{act?}', [GuruController::class, 'load'])->name('sisp.load')->middleware('ajax')->middleware('auth');
Route::get('sisp/detail/{id?}', [GuruController::class, 'detail'])->name('sisp.detail')->middleware('auth');
Route::get('sisp/detailSisp/{id?}', [GuruController::class, 'detailGuru'])->name('sisp.detailSisp')->middleware('ajax')->middleware('auth');
Route::post('sisp/updateGuru', [GuruController::class, 'updateDataGuru'])->name('sisp.updateGuru')->middleware('ajax')->middleware('auth');
Route::post('sisp/updatePic', [GuruController::class, 'updateDataPic'])->name('sisp.pic')->middleware('ajax')->middleware('auth');
Route::post('sisp/filter/{act?}', [GuruController::class, 'filter'])->name('sisp.filter')->middleware('auth');
Route::get('sisp/setAct/{user_act}/{sisp_id}', [GuruController::class, 'setAct'])->middleware('auth');
Route::get('sisp/na', [GuruController::class, 'nonaktif'])->name('sisp.na')->middleware('auth');
Route::get('sisp/delete/{sisp_id}', [GuruController::class, 'deleteData'])->middleware('auth');

Route::get('sisp/dataKoo/{id?}', [SispController::class, 'showByKoo'])->name('sisp.dataKoo')->middleware('auth');

Route::get('guruCtk/{kat?}/{id?}', [GuruCtkController::class, 'index'])->name('guruCtk.index')->middleware('auth');

Route::get('lap', [LapController::class, 'index'])->name('lap.index')->middleware('auth');
Route::get('lap/approved', [LapController::class, 'disetujui'])->name('lap.approved')->middleware('auth');
Route::get('lap/rejected', [LapController::class, 'ditolak'])->name('lap.rejected')->middleware('auth');
Route::get('lap/expired', [LapController::class, 'lewat'])->name('lap.expired')->middleware('auth');
Route::get('lap/profil/{id?}/{tipe?}', [LapController::class, 'detailProfil'])->name('lap.profil')->middleware('auth')->middleware('ajax');
Route::post('lap/insertProfil', [LapController::class, 'insertDataProfil'])->name('lap.insertProfil');
Route::post('lap/updateProfil', [LapController::class, 'updateDataProfil'])->name('lap.updateProfil');
Route::post('lap/update', [LapController::class, 'updateData'])->name('lap.update');
Route::post('lap/updateTlk', [LapController::class, 'updateDataTlk'])->name('lap.updateTlk');
Route::get('lap/loadProfil/{id?}/{tipe?}', [LapController::class, 'loadProfil'])->name('lap.loadProfil')->middleware('ajax')->middleware('auth');
Route::get('lap/ajax/{id?}', [LapController::class, 'loadAjax'])->name('lap.ajax')->middleware('ajax')->middleware('auth');
Route::get('lap/load/{tipe?}', [LapController::class, 'load'])->name('lap.load')->middleware('ajax')->middleware('auth');
Route::get('lap/delete/{id}', [LapController::class, 'deleteData'])->middleware('auth');

Route::get('lap/lapkoo/{id?}', [LapController::class, 'detailKoo'])->name('lap.lapKoo')->middleware('auth');


Route::get('absen', [AbsenController::class, 'index'])->name('absen.index')->middleware('auth');
Route::post('absen/insert', [AbsenController::class, 'insertData'])->name('absen.insert');
Route::post('absen/insertM', [AbsenController::class, 'insertDataM'])->name('absen.insertM');
Route::post('absen/update', [AbsenController::class, 'updateData'])->name('absen.update');
Route::get('absen/load', [AbsenController::class, 'load'])->name('absen.load')->middleware('ajax')->middleware('auth');
Route::get('absen/profil/{id?}/{m?}/{y?}', [AbsenController::class, 'detailProfil'])->name('absen.profil')->middleware('auth')->middleware('ajax');
Route::post('absen/profilFilter/{id?}', [AbsenController::class, 'filterDataProfil'])->name('absen.profilFilter')->middleware('auth')->middleware('ajax');
Route::get('absen/profilCal/{id?}/{m?}/{y?}', [AbsenController::class, 'detailProfilCal'])->name('absen.profilCal')->middleware('auth')->middleware('ajax');
Route::get('absen/profilList/{id?}/{m?}/{y?}', [AbsenController::class, 'detailProfilList'])->name('absen.profilList')->middleware('auth')->middleware('ajax');
Route::get('absen/ajax/{id?}', [AbsenController::class, 'loadAjax'])->name('absen.ajax')->middleware('ajax')->middleware('auth');
Route::get('absen/getDataBulanKelas/{bag_nm}', [AbsenController::class, 'getDataBulanKelas']);

Route::post('absen/filter', [AbsenController::class, 'filter'])->name('absen.filter')->middleware('ajax')->middleware('auth');
Route::get('absen/filterData', [AbsenController::class, 'datafilter'])->name('absen.filterData')->middleware('ajax')->middleware('auth');
Route::get('absen/getMonthYear/{year}', [AbsenController::class, 'getMonthByYear']);

Route::get('absen/pdfF', [AbsenPdfController::class, 'pdfFilter'])->name('absen.pdfF')->middleware('auth');
Route::get('absen/excelF', [AbsenExcelController::class, 'excelFilter'])->name('absen.excelF')->middleware('auth');

Route::get('excel/sisp/{id?}/{m?}/{y?}', [AbsenExcelController::class, 'eSisp'])->name('excel.sisp')->middleware('auth');
Route::get('pdf/sisp/{id?}/{m?}/{y?}', [AbsenPdfController::class, 'pdfSisp'])->name('pdf.sisp')->middleware('auth');

Route::get('survei', [SurveiController::class, 'index'])->name('survei.index')->middleware('auth');
Route::get('survei/expired', [SurveiController::class, 'lewat'])->name('survei.expired')->middleware('auth');
Route::post('survei/insert', [SurveiController::class, 'insertData'])->name('survei.insert')->middleware('auth');
Route::post('survei/update', [SurveiController::class, 'updateData'])->name('survei.update')->middleware('auth');
Route::get('survei/delete/{id}', [SurveiController::class, 'deleteData'])->middleware('auth')->middleware('auth');
Route::get('survei/load/{tipe?}', [SurveiController::class, 'load'])->name('survei.load')->middleware('ajax')->middleware('auth');
Route::get('survei/detail/{id?}', [SurveiController::class, 'detail'])->name('survei.detail')->middleware('auth');

Route::get('survei/loadForm/{idsurvei?}/{idsisp?}', [SurveiController::class, 'showFormSurvei'])->name('survei.loadForm')->middleware('auth');

Route::get('surveiq/detail/{id?}', [SurveiqController::class, 'detailSurvei'])->name('surveiq.detailSurvei')->middleware('auth');
Route::post('surveiq/insert', [SurveiqController::class, 'insertData'])->name('surveiq.insert')->middleware('auth');
Route::get('surveiq/delete/{id?}', [SurveiqController::class, 'deleteData'])->name('surveiq.delete')->middleware('auth');

Route::post('surveis/insert', [SurveisController::class, 'insertData'])->name('surveis.insert')->middleware('auth');
Route::get('surveis/detail/{sisp?}', [SurveisController::class, 'getSurveisBySisp'])->name('surveis.detail')->middleware('auth');

Route::get('surveis/loadFormA/{idsurvei?}/{idsisp?}', [SurveiController::class, 'showFormSurveiA'])->name('survei.loadFormA')->middleware('auth');

Route::get('surveis/profil/{idsisp?}', [SurveisController::class, 'showProfil'])->name('surveis.profil')->middleware('auth');
// Route::get('lap/loadProfil/{id?}/{tipe?}', [LapController::class, 'loadProfil'])->name('lap.loadProfil')->middleware('ajax')->middleware('auth');
// Route::get('lap/ajax/{id?}', [LapController::class, 'loadAjax'])->name('lap.ajax')->middleware('ajax')->middleware('auth');

// Route::get('/linkstorage', function () {
//     $targetFolder = base_path().'/storage/app/public';
//     $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
//     symlink($targetFolder, $linkFolder); 
// });

// Route::get('cmd', function(){
//     $process = new Process(['ln', $_SERVER['DOCUMENT_ROOT'].'/storage' ,base_path().'/storage/app/public' ]);
//     $process->run();
 
//     // executes after the command finishes
//     if (!$process->isSuccessful()) {
//        throw new ProcessFailedException($process);
//     }
//     echo $process->getOutput();
//  });
 
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('optimize:clear');
    // return what you want
});