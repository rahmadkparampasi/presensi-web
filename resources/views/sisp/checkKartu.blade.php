@extends('layouts.mainlayout')

@section('title', $WebTitle)

@section('content')
<div class="col-sm-12">
    <div class="card" style="" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Cek Kartu</h5>
        </div>
        <div class="card-body">
            <div class="form-group p-4 mb-0 pb-0 required">
                <label class="control-label" for="sisp_idsp">NIS/NISN/NIP</label>
                <input type="text" class="form-control" id="sisp_idsp" autofocus name="sisp_idsp" placeholder="" required onkeydown="search(this)">
            </div>
        </div>
        <form class="" action="{{route('sisp.updateKartu')}}" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" >
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="sisp_id" name="sisp_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_nm">Nama Lengkap</label>
                    <input type="text" class="form-control" id="sisp_nm" name="sisp_nm" placeholder="" required disabled>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_jk">Jenis Kelamin</label>
                    <input type="text" class="form-control" id="sisp_jk" name="sisp_jk" placeholder="" required disabled>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_tgllhr">Tempat/Tanggal Lahir</label>
                    <input type="text" class="form-control" id="sisp_tgllhr" name="sisp_tgllhr" placeholder="" required disabled>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_alt">Alamat</label>
                    <input type="text" class="form-control" id="sisp_alt" name="sisp_alt" placeholder="" required disabled>
                </div>

                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_pic">Foto Peserta</label>
                    <div class="w-100 d-flex justify-content-center align-content-center">
                        <img id="sisp_picPre" src="{{url('assets/img/user.png')}}" alt="Gambar" width="200" class=""/>
                    </div>
                </div>

                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_krt">Kode Kartu</label>
                    <input type="text" class="form-control" id="sisp_krt" name="sisp_krt" placeholder="" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>
        </form>
    </div>
</div>
<script>
    function search(ele) {
        if(event.key === 'Enter') {
            $.ajax({
                url:"{{route('sisp.ajaxKrt')}}/"+ele.value,
                
                success: function(data1) {
                    console.log(data1);
                    $('#sisp_id').val(data1.sisp_id);
                    $('#sisp_nm').val(data1.sisp_nmAltT);
                    $('#sisp_jk').val(data1.sisp_jkAltT);
                    $('#sisp_tgllhr').val(data1.sisp_tmptlhr+', '+data1.sisp_tgllhrAltT);
                    $('#sisp_alt').val(data1.sisp_altAltT);
                    $('#sisp_picPre').attr('src', '/storage/uploads/'+data1.sisp_pic);
                    $('#sisp_krt').val(data1.sisp_krt);
                    $('#sisp_krt').focus();
                    $('#sisp_krt').select();
                    // $('#sispiProfile').append(data1);
                },
                error:function(xhr) {
                    Swal.fire({
                        title: "Maaf",
                        text: "Data Peserta Tidak Ditemukan",
                        icon: "error"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#sisp_idsp').val('');
                            $('#sisp_idsp').focus();
                        }
                    });

                }
            });
            
        }
    }
</script>
@include('includes.anotherscript')
@include('includes.ajaxinsertTV')
@endsection