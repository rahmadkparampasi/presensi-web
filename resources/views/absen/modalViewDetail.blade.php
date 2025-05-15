@extends('layouts.modalAll', ['idModalAll' => 'absenAddDataModal', 'sizeModalAll' => '', 'divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' => '', 'dataLoadModalAll'=>'true','urlModalAll'=>'', 'titleModalAll' => 'DETAIL ABSENSI'])

@section('contentInputHidden')
    
    
@endsection
@section('contentModalBody'.$countModalBody)
    

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_sts">Status</label>
        <select class="form-control border rounded border-dark" id="absen_sts" name="absen_sts" required disabled>
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="TH">Tidak Hadir</option>
            <option value="H">Hadir</option>
            <option value="I">Izin</option>
        </select>
    </div>

    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_tgl">Tanggal Absen</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_tgl" name="absen_tgl" required disabled>
    </div>
    <br/>
    <h5 class="text-center">MASUK</h5>
    <hr/>
    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_masuk">Jam Masuk</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_masuk" name="absen_masuk" required disabled>
    </div>

    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_cd">Cepat Datang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_cd" name="absen_cd" required disabled>
    </div>

    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_lmbt">Lambat</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_lmbt" name="absen_lmbt" required disabled>
    </div>
    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_masuklok">Lokasi Masuk</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_masuklok" name="absen_masuklok" required disabled>
    </div>

    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_masuklok">Foto Masuk</label>

        <div class="form-group w-100 d-flex justify-content-center">
            <img id="viewImgPreAbsenMasukPic" src="{{url('assets/img/user.png')}}" alt="Pratinjau Gambar" width="200" />
        </div>
    </div>
    

    <br/>
    <h5 class="text-center">PULANG</h5>
    <hr/>
    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_keluar">Jam pulang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_keluar" name="absen_keluar" required disabled>
    </div>

    <div class="showHadir showPulang form-group p-2 mb-0 pb-0 required d-none">
        <label class="control-label" for="absen_cp">Cepat Pulang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_cp" name="absen_cp" required disabled>
    </div>

    <div class="showHadir showPulang form-group p-2 mb-0 pb-0 required d-none">
        <label class="control-label" for="absen_lbh">Lambat Pulang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_lbh" name="absen_lbh" required disabled>
    </div>

    <div class="showHadir showPulang form-group p-2 mb-0 pb-0 required d-none">
        <label class="control-label" for="absen_keluarlok">Lokasi Pulang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_keluarlok" name="absen_keluarlok" required disabled>
    </div>

    <div class="showHadir showPulang form-group p-2 mb-0 pb-0 required d-none">
        <label class="control-label" for="absen_masuklok">Foto Pulang</label>
        <div class="form-group w-100 d-flex justify-content-center">
            <img id="viewImgPreAbsenKeluarPic" src="{{url('assets/img/user.png')}}" alt="Pratinjau Gambar" width="200" />
        </div>
    </div>

    <div class="showHadir form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="absen_keluar_lbh">Jam Pulang Seharusnya</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_keluar_lbh" name="absen_keluar_lbh" required disabled>
    </div>

    <div class="showIzin form-group p-2 mb-0 pb-0">
        <label class="control-label" for="absen_tiket">Nomor Tiket Izin</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="absen_tiket" name="absen_tiket" required disabled>
    </div>
    <script>
        function callModalRefAbsen(absen_id){
            // console.log(sispi_id);
            $('#absenAddDataModal').modal('show');
            $.ajax({
                url: '{{route('absen.ajax')}}'+'/'+absen_id,
                success: function(data1) {
                    console.log(data1);
                    if (data1.absen_sts=='H') {
                        $('.showIzin').addClass('d-none');
                        $('.showHadir').removeClass('d-none');
                    }else if(data1.absen_sts=='I') {
                        $('.showIzin').removeClass('d-none');
                        $('.showHadir').addClass('d-none');
                    }else if(data1.absen_sts=='TH') {
                        $('.showIzin').addClass('d-none');
                        if (data1.absen_masuk==="00:00:00") {
                            $('.showHadir').addClass('d-none');
                        }
                    }
                    
                    $('#absen_sts').val(data1.absen_sts);
                    $('#absen_tgl').val(data1.absen_tgl);
                    $('#absen_masuk').val(data1.absen_masuk);
                    if (data1.absen_masukk=="0"&&data1.absen_masuk=="00:00:00") {
                        $('#absen_masuk').val('Tidak Melakukan Absen Masuk');
                    }
                    $('#absen_keluar').val(data1.absen_keluar);
                    if (data1.absen_keluark=="0"&&data1.absen_keluar=="00:00:00") {
                        $('#absen_keluar').val('Tidak Melakukan Absen Keluar');
                        $('.showPulang').addClass('d-none');
                    }

                    $('#absen_keluar_lbh').val(data1.absen_psn);

                    $('#absen_cp').val(data1.absen_cp);
                    $('#absen_cd').val(data1.absen_cd);
                    $('#absen_lmbt').val(data1.absen_lmbt);
                    $('#absen_lbh').val(data1.absen_lbh);
                    $('#absen_tiket').val(data1.sispi_tiket);

                    if (data1.absen_masuklok=="0") {
                        $('#absen_masuklok').val('Tidak Berada Di Kantor');
                    }else{
                        $('#absen_masuklok').val('Berada Di Kantor');
                    }

                    fileMasuk = '/storage/uploads/'+data1.absen_masukpic;
                    
                    $('#viewImgPreAbsenMasukPic').attr('src', '{{url("")}}'+fileMasuk);


                    if (data1.absen_keluarlok=="0") {
                        $('#absen_keluarlok').val('Tidak Berada Di Kantor');
                    }else{
                        $('#absen_keluarlok').val('Berada Di Kantor');
                    }

                    if (data1.absen_keluarpic!=='') {
                        fileKeluar = '/storage/uploads/'+data1.absen_keluarpic;
    
                        $('#viewImgPreAbsenKeluarPic').attr('src', '{{url("")}}'+fileKeluar);    
                    }
                    
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="button" class="btn btn-danger" data-dismiss="modal">TUTUP</button>
    </div>
@endsection
