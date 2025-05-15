
    <div class="card-header">
        
        <div class="card-title mb-0">Data Pengguna {{$User!=null ? $User->users_tipeAltT : ''}}</div>
    </div>
    <div class="card-body">
        @if ($User!=null)
            <ul class="list-group list-group-flush">
                @csrf
                <div class="form-group p-2 mb-0 pb-0 required">
                    <label class="control-label" for="users_nm">Nama Pengguna</label>
                    <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="users_nm" name="users_nm" required value="{{$User->users_nm}}" disabled>
                </div>
                <div class="form-group p-2 mb-0 pb-0 required">
                    <label class="control-label" for="users_nm">Username</label>
                    <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="users_nm" name="users_nm" required value="{{$User->username}}" disabled>
                </div>
                <div class="form-group p-2 mb-0 pb-0 required">
                    <label class="control-label" for="users_nm">Status</label>
                    @if ($Pgn->users_tipe=="A")
                        {!!$User->users_actAltBu!!}
                    @else
                        {!!$User->users_actAltBa!!}
                    @endif
                </div>
                <div class="form-group p-2 mb-0 pb-0 required">
                    <label class="control-label" for="users_nm">Kata Sandi</label>
                    <button class='btn btn-warning mx-1' data-toggle="modal" data-target="#changePwd" onclick=" addFill('users_nmPwd', '{{$User->users_nm}}'); addFill('users_idPwd', '{{$User->users_id}}'); addFill('tipePwd', 'D');"><i class="fa fa-sync-alt"></i> UBAH</button>
                </div>
            </ul>
        @else
            <h5>Belum Ada Data Pengguna</h5>
        @endif
    </div>
    @if ($User==null)
        @if ($Pgn->users_tipe=="A")
            <div class="card-footer">
                <button class='btn btn-primary mx-1' onclick="callOtherTWLoad('Membuat Data Pengguna', '{{route('user.generate', [$users_sisp, $tipe])}}', '{{route('user.detailProfil', [$users_sisp, $tipe])}}', '', 'userDetailProfil', '', '', 'Buat')"><i class="fa fa-user-plus"></i> BUAT DATA PENGGUNA</button>
            </div>
        @endif
    @endif