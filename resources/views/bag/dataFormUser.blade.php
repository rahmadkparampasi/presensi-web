<form class="mb-5" action="{{route('user.insertBag')}}" data-load="true" id="<?= $IdForm ?>User" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none !important;">
    @csrf
    <input type="hidden" class="form-control" id="users_id" name="users_id">
    <input type="hidden" class="form-control" id="users_bag" name="users_bag" value="{{$bag_id}}">
    
    <div class="form-group p-4 mb-0 pb-0 required">
        <label class="control-label" for="users_nm">Nama Pengguna</label>
        <input type="text" class="form-control" id="users_nm" name="users_nm" placeholder="" required>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required">
        <label class="control-label" for="users_tipe">Kategori Pengguna</label>
        <select class="form-control" id="users_tipe" name="users_tipe" placeholder="" required>
            <option value="">Pilih Salah Satu Pilihan</option>
            <option value="KR">Koordinator Unit</option>
            <option value="R">Koordinator Ruangan</option>
        </select>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required">
        <label class="control-label" for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="" required>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required">
        <label class="control-label" for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="" required>
    </div>
    <div class="form-group p-4 mb-0 pb-0 required">
        <label class="control-label" for="password1">Password</label>
        <input type="password" class="form-control" id="password1" name="password1" placeholder="" required>
    </div>
    <button type="submit" class="btn btn-primary">SIMPAN</button>
    <button type="button" onclick="closeForm('<?= $IdForm ?>User', '<?= $IdForm ?>User', '{{route('user.insertBag')}}')" class="btn btn-danger">BATAL</button>
</form>
<div class="w-100 p-t-20 m-b-20">
    <button class='btn btn-primary' onclick="showForm('{{$IdForm}}User', 'block'); cActForm('{{$IdForm}}User', '{{route('user.insertBag')}}'); resetForm('{{$IdForm}}User')"><i class="fa fa-plus"></i> TAMBAH</button>
</div>
<table id="{{$IdForm}}dTUser" class=" display table align-items-centertable-striped table-hover w-100 mb-20" data-searching="false" data-paging="false">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pengguna</th>
            <th>Kategori Pengguna</th>
            <th>Username</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($User as $tk) @php $no++ @endphp 
        
        <tr>
            <td>{{$no}}</td>
            <td>{{$tk['users_nm']}}</td>
            <td>{{$tk['users_tipeAltT']}}</td>
            <td>{{$tk['username']}}</td>
            
            <td>
                <button type="button" class="btn btn-warning" onclick="showForm('{{$IdForm}}User', 'block'); cActForm('{{$IdForm}}User', '{{route('user.updateBag')}}'); addFill('users_id', '{{$tk['users_id']}}'); addFill('users_nm', '{{$tk['users_nm']}}'); addFill('username', '{{$tk['username']}}');"><i class="fas fa-pen"></i></button>

                <button type="button" class="btn btn-danger" onclick="callOtherTWLoad('Menghapus Data Pengguna Bagian','{{url('user/delete/'.$tk['users_id'])}}', '{{url('bag/getDataFormUser/'.$bag_id)}}', '{{$IdForm}}User', 'tabUsersBag')"><i class="fas fa-trash"></i></button>
                
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        dTD('table#{{$IdForm}}dTUser');
    });
</script>
<script>
    $(function() {
        $(document).ready(function() {
            $('#{{$IdForm}}User').parsley();
            var {{$IdForm}}User = $('#{{$IdForm}}User');
            {{$IdForm}}User.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#{{$IdForm}}User').parsley().isValid) {
                    $('#{{$IdForm}}User :input').prop("disabled", false);
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: {{$IdForm}}User.attr('method'),
                        url: {{$IdForm}}User.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            if(typeof {{$IdForm}}User.attr('data-load')!=='undefined'){
                                if ({{$IdForm}}User.attr('data-load')==='true') {
                                    $.ajax({
                                        url:"{{url('bag/getDataFormUser/'.$bag_id)}}",
                                        success: function(data1) {
                                            $('#tabUsersBag').html(data1);
                                            closeForm('{{$IdForm}}User', '{{$IdForm}}User')
                                            showToast(data.response.message, 'success');
                                        },
                                        error:function(xhr) {
                                            window.location = "{{url($UrlForm)}}";
                                        }
                                    });
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location = "{{url($UrlForm)}}";
                                    });
                                }
                            }else{
                                swal.fire({
                                title: "Terima Kasih",
                                text: data.response.message,
                                icon: data.response.response
                                }).then(function() {
                                    window.location = "{{url($UrlForm)}}";
                                });
                            }
                        },
                        error: function(xhr) {
                            hideAnimated();                        
                            showToast(xhr.responseJSON.response.message, 'error');
                        }
                    });
                }
            });
        });
    });
</script>