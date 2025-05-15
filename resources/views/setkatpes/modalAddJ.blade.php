@extends('layouts.modalAll', ['idModalAll' => 'modalAddJ', 'sizeModalAll' => '','divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' =>url('setkatpes/load'), 'dataLoadModalAll'=>'true', 'urlModalAll'=>route('setkatpesj.insert'), 'titleModalAll' => 'TAMBAH JAM KERJA PESERTA'])

@section('contentInputHidden')
    <input type="hidden" id="setkatpesj_setkatpes" name="setkatpesj_setkatpes"/>
@endsection
@section('contentModalBody'.$countModalBody)

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="setkatpesj_hr">Hari Absensi</label>
        <select class="form-control border rounded border-dark" id="setkatpesj_hr" name="setkatpesj_hr" required>
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="1">Senin</option>
            <option value="2">Selasa</option>
            <option value="3">Rabu</option>
            <option value="4">Kamis</option>
            <option value="5">Jumat</option>
            <option value="6">Sabtu</option>
            <option value="7">Minggu</option>
            <option value="O">Otomatis</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="setkatpesj_masuk">Jam Masuk</label>
        <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_masuk" name="setkatpesj_masuk" placeholder="Ketik Disini" required>
    </div>
    
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="setkatpesj_keluar">Jam Keluar</label>
        <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_keluar" name="setkatpesj_keluar" placeholder="Ketik Disini" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="setkatpesj_bts">Batas Absensi</label>
        <select class="form-control border rounded border-dark" id="setkatpesj_bts" name="setkatpesj_bts" required onchange="cPesJ('setkatpesj_btsjDiv', 'setkatpesj_btsj', 'setkatpesj_bts')">
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="0">Tidak Ada Batas Jam Absensi</option>
            <option value="1">Ada Batas Jam Absensi</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 d-none" id='setkatpesj_btsjDiv'>
        <label class="control-label" for="setkatpesj_btsj">Jam Batas Absensi</label>
        <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_btsj" name="setkatpesj_btsj" placeholder="Ketik Disini">
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="setkatpesj_tol">Toleransi Absensi</label>
        <select class="form-control border rounded border-dark" id="setkatpesj_tol" name="setkatpesj_tol" required onchange="cPesJ('setkatpesj_toljDiv', 'setkatpesj_tolj', 'setkatpesj_tol')">
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="0">Tidak Ada Toleransi Jam Absensi</option>
            <option value="1">Ada Toleransi Jam Absensi</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 d-none" id="setkatpesj_toljDiv">
        <label class="control-label" for="setkatpesj_tolj">Jam Toleransi Absensi</label>
        <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_tolj" name="setkatpesj_tolj" placeholder="Ketik Disini">
    </div>
    <script>
        function cPesJ(idDiv, idSelectInput, idSelect){
            let idSelectInputExtra = document.getElementById(idSelect).value;
            
            if (idSelectInputExtra=="1") {
                $('#'+idDiv).removeClass('d-none');
                $('#'+idDiv).addClass('required');
                $('#'+idSelectInput).prop('required',true);
                $('#'+idSelectInput).val('');
            }else{
                $('#'+idDiv).removeClass('required');
                $('#'+idDiv).addClass('d-none');
                $('#'+idSelectInput).prop('required',false);
                $('#'+idSelectInput).val('');
            }
        }
        $(document).ready( function () {
            $('.setkatpesj').timepicker({
                timeFormat: 'HH:mm:ss',
                interval: 30,
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                zindex: 9999999,
                
            });
        });
    </script>

@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
