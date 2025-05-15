@extends('layouts.modalAll', ['idModalAll' => 'modalFilterAbsen', 'sizeModalAll' => '', 'divLoadModalAll' => '', 'urlLoadModalAll' =>'', 'dataLoadModalAll'=>'', 'urlModalAll'=>route('absen.filter'), 'titleModalAll' => 'SELEKSI DATA ABSEN'])

@section('contentInputHidden')
@endsection
@section('contentModalBody'.$countModalBody)
    <div id="filter_group" class="w-100">
        @if (isset($filtert_bagk))
            @if ($filtert_bagk=='S')
                <input type="hidden" id="filters_satker" name="filters_satker" value="{{$Bagk != null ? $Bagk->bag_id : ''}}" />
                <div class="form-row align-items-center" id="filterGuru">
                    <h6 class="col-12">PPK</h6>
                    <div class="form-group col-md-4 col-lg-2">
                        <label for="filters_ppk">PPK</label>
                    </div>
                    <div class="form-group col-md-8 col-lg-10 ">
                        <select class="form-control border rounded border-dark" id="filters_ppk" name="filters_ppk"  required onchange="">
                            <option value="" hidden>Pilih Salah Satu</option>
                            <option value="All">Semua</option>
                            @foreach ($Bag as $tk)
                                <option value="{{$tk->bag_id}}">{{$tk->bag_nm}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <input type="hidden" id="filters_satker" name="filters_satker" value="{{$Bagksatker != null ? $Bagksatker->bag_id : ''}}" />
                <input type="hidden" id="filters_ppk" name="filters_ppk" value="{{$Bagk != null ? $Bagk->bag_id : ''}}" />
            @endif
        @endif
        
        <input type="hidden" id="filtert_bagk" name="filtert_bagk" value="" />
        <input type="hidden" id="filtert_div" name="filtert_div" value="" />
        
    </div>
    <div class="form-row align-items-center" id="filterTgl">
        <h6 class="col-12">ABSENSI</h6>
        <div class="form-group col-md-4 col-lg-2">
            <label for="filtert_tgl">Kategori Tanggal</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 ">
            <select class="form-control border rounded border-dark" id="filtert_tgl" name="filtert_tgl"  required onchange="showFilterTanggal(this.value)">
                <option value="" hidden>Pilih Salah Satu</option>
                <option value="today">Hari Ini</option>
                <option value="kemarin">Kemarin</option>
                <option value="minggu">Minggu Ini</option>
                <option value="pilih">Pilih Tanggal</option>
                <option value="bulan">Bulan</option>
                <option value="rentang">Rentang Waktu</option>
            </select>
        </div>
        <div class="form-group col-md-4 col-lg-2 filtert_blnbln_class d-none">
            <label for="filtert_blnthn">Tahun</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_blnbln_class d-none">
            <select class="form-control border rounded border-dark" id="filtert_blnthn" name="filtert_blnthn"  required onchange="ambilDataSelect('filtert_blnbln', '{{url('absen/getMonthYear')}}/', 'Pilih Salah Satu', toRemove=['filtert_blnbln'], removeMessage=['Pilih Salah Satu'], 'filtert_blnthn')">
                <option value="" hidden>Pilih Salah Satu</option>
                @foreach ($Tahun as $tk)
                    <option value="{{$tk->year}}">{{$tk->year}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4 col-lg-2 filtert_blnbln_class d-none">
            <label for="filtert_blnbln">Bulan</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_blnbln_class d-none">
            <select class="form-control border rounded border-dark" id="filtert_blnbln" name="filtert_blnbln" required >
                <option value="" hidden>Pilih Salah Satu</option>
            </select>
        </div>

        <div class="form-group col-md-4 col-lg-2 filtert_smstr_class d-none">
            <label for="filtert_smstr">Semester</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_smstr_class d-none">
            <select class="form-control border rounded border-dark" id="filtert_smstr" name="filtert_smstr"  required >
                <option value="" hidden>Pilih Salah Satu</option>
                
            </select>
        </div>

        <div class="form-group col-md-4 col-lg-2 filtert_rentang_class d-none">
            <label for="filtert_rentangawal">Tanggal Awal</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_rentang_class d-none">
            <input type="date" class="form-control border rounded border-dark" id="filtert_rentangawal" name="filtert_rentangawal"  required />
        </div>
        <div class="form-group col-md-4 col-lg-2 filtert_rentang_class d-none">
            <label for="filtert_rentangakhir">Tanggal Akhir</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_rentang_class d-none">
            <input type="date" class="form-control border rounded border-dark" id="filtert_rentangakhir" name="filtert_rentangakhir"  required />
        </div>

        <div class="form-group col-md-4 col-lg-2 filtert_pilih_class d-none">
            <label for="filtert_pilih">Pilih Tanggal</label>
        </div>
        <div class="form-group col-md-8 col-lg-10 filtert_pilih_class d-none">
            <input type="date" class="form-control border rounded border-dark" id="filtert_pilih" name="filtert_pilih"  required />
        </div>
    </div>

    <script>
        function showFilterTanggal(val = '')
        {
            $('#filtert_blnthn').val('');
            $('#filtert_blnbln').val('');
            $('#filtert_smstr').val('');
            $('#filtert_smstr').val('');
            $('#filtert_rentangawal').val('');
            $('#filtert_rentangakhir').val('');
            $('#filtert_pilih').val('');
            
            if (val=='pilih') {
                $('.filtert_pilih_class').removeClass('d-none');
                $('.filtert_blnbln_class').addClass('d-none');
                $('.filtert_smstr_class').addClass('d-none');
                $('.filtert_rentang_class').addClass('d-none');
            }else if(val == 'bulan'){
                $('.filtert_blnbln_class').removeClass('d-none');
                $('.filtert_pilih_class').addClass('d-none');
                $('.filtert_smstr_class').addClass('d-none');
                $('.filtert_rentang_class').addClass('d-none');
            }else if(val == 'semester'){
                $('.filtert_smstr_class').removeClass('d-none');
                $('.filtert_blnbln_class').addClass('d-none');
                $('.filtert_pilih_class').addClass('d-none');
                $('.filtert_rentang_class').addClass('d-none');
            }else if(val == 'rentang'){
                $('.filtert_rentang_class').removeClass('d-none');
                $('.filtert_smstr_class').addClass('d-none');
                $('.filtert_blnbln_class').addClass('d-none');
                $('.filtert_pilih_class').addClass('d-none');
            }else{
                $('.filtert_rentang_class').addClass('d-none');
                $('.filtert_smstr_class').addClass('d-none');
                $('.filtert_blnbln_class').addClass('d-none');
                $('.filtert_pilih_class').addClass('d-none');
            }
        }
    </script>
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="button" onclick="filterSiswa()" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
    <script>
        function filterSiswa()
        {
            ppk = '';
            nextKat = false;
            nextTgl = false;
            if ($('#filters_ppk').val()=='') {
                showToast('Kategori PPK Harus Dipilih', 'error');
                $('#filters_ppk').focus();
            }else{
                if ($('#filters_ppk').val()=='All') {
                    nextKat = true;
                }else {
                    ppk = $('#filters_ppk').val();
                    nextKat = true;
                }
            }

            if ($('#filtert_tgl').val()=='') {
                showToast('Kategori Tanggal Harus Dipilih', 'error');
                $('#filtert_tgl').focus();
            } else {
                if ($('#filtert_tgl').val()=='today'||$('#filtert_tgl').val()=='kemarin'||$('#filtert_tgl').val()=='minggu') {
                    nextTgl = true;
                }else if($('#filtert_tgl').val()=='pilih'){
                    if ($('#filtert_pilih').val()=='') {
                        showToast('Tanggal Harus Dipilih', 'error');
                        $('#filtert_pilih').focus();
                    }else{
                        nextTgl = true;
                    }
                }else if($('#filtert_tgl').val()=='bulan'){
                    if ($('#filtert_blnbln').val()==''&&$('#filtert_blnthn').val()=='') {
                        showToast('Tahun Dan Bulan Harus Dipilih', 'error');
                        $('#filtert_blnthn').focus();
                    }else if($('#filtert_blnbln').val()==''){
                        showToast('Bulan Harus Dipilih', 'error');
                        $('#filtert_blnbln').focus();
                    }else{
                        nextTgl = true;
                    }
                }else if($('#filtert_tgl').val()=='semester'){
                    if ($('#filtert_smstr').val()=='') {
                        showToast('Semester Harus Dipilih', 'error');
                        $('#filtert_smstr').focus();
                    }else{
                        nextTgl = true;
                    }
                }else if($('#filtert_tgl').val()=='rentang'){
                    if ($('#filtert_rentangawal').val()==''&&$('#filtert_rentangakhir').val()=='') {
                        showToast('Tanggal Awal Dan Tanggal Akhir Harus Dipilih', 'error');
                        $('#filtert_rentangawal').focus();
                    }else if($('#filtert_rentangawal').val()==''){
                        showToast('Tanggal Awal Harus Dipilih', 'error');
                        $('#filtert_rentangawal').focus();
                    }else if($('#filtert_rentangakhir').val()==''){
                        showToast('Tanggal Akhir Harus Dipilih', 'error');
                        $('#filtert_rentangakhir').focus();
                    }else{
                        nextTgl = true;
                    }
                }
            }

            if (nextKat&&nextTgl) {
                var modalFilterAbsenForm = $('#modalFilterAbsenForm');
                myData = new FormData();
                myData.append('filters_satker', $('#filters_satker').val());
                myData.append('filters_ppk', ppk);
                
                myData.append('filtert_tgl', $('#filtert_tgl').val());
                myData.append('filtert_blnthn', $('#filtert_blnthn').val());
                myData.append('filtert_blnbln', $('#filtert_blnbln').val());
                myData.append('filtert_smstr', $('#filtert_smstr').val());
                myData.append('filtert_pilih', $('#filtert_pilih').val());
                myData.append('filtert_rentangawal', $('#filtert_rentangawal').val());
                myData.append('filtert_rentangakhir', $('#filtert_rentangakhir').val());
                myData.append('filtert_bagk', $('#filtert_bagk').val());
                myData.append('_token', '{{csrf_token()}}');
                $.ajax({
                    type: modalFilterAbsenForm.attr('method'),
                    url: modalFilterAbsenForm.attr('action'),
                    enctype: 'multipart/form-data',
                    data: myData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        hideAnimated();
                        $('#modalFilterAbsen').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#'+$('#filtert_div').val()).html('<br/><br/>'+data);
                    },
                    error: function(xhr) {
                        hideAnimated();                        
                        showToast(xhr.responseJSON.response.message, 'error');
                    }
                });
            }
        }
    </script>
@endsection
