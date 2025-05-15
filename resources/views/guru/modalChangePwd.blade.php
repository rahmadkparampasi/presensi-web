@extends('layouts.modalAll', ['idModalAll' => 'changePwd', 'sizeModalAll' => '', 'divLoadModalAll' => 'userDetailProfil', 'urlLoadModalAll' => route('user.detailProfil', [$Guru->sisp_id, 'M']), 'dataLoadModalAll'=>'true','urlModalAll'=>route('user.updatePwd'), 'titleModalAll' => 'UBAH KATA SANDI'])

@section('contentInputHidden')
    
    
    
@endsection
@section('contentModalBody'.$countModalBody)
    
    <input type="hidden" id="users_idPwd" name="users_id" />
    <input type="hidden" id="users_idPwdSession" name="users_id_session" value="{{$Pgn->users_id}}" />
    <input type="hidden" id="tipePwd" value="D" />

    <div class="form-group p-4 mb-0 pb-0 required col-12">
        <label class="control-label" for="users_nmPwd">Nama Pengguna</label>
        <input type="text" class="form-control" id="users_nmPwd" name="users_nm" placeholder="" required readonly>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required col-12">
        <label class="control-label" for="password_old">Password Lama</label>
        <input type="password" class="form-control" id="password_old" name="password_old" placeholder="" required>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required col-12">
        <label class="control-label" for="password_new">Password Baru</label>
        <input type="password" class="form-control" id="password_new" name="password" placeholder="" required>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required col-12">
        <label class="control-label" for="password_new1">Ulangi Password Baru</label>
        <input type="password" class="form-control" id="password_new1" name="password_confirmation" placeholder="" required>
    </div>
    
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-success">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">TUTUP</button>
    </div>
@endsection
