
<form  action="{{route('absen.filter')}}" data-load="true" id="<?= $IdForm ?>filterForm" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none" onsubmit="return false;">
    <div class="card-body">
        @csrf
        <div class="form-row align-items-center" id="filterSiswa">
            <h6 class="col-12">SATKER DAN PPK</h6>
            <div class="form-group col-md-4 col-lg-2">
                <label for="filters_satker">SATKER</label>
            </div>
            <div class="form-group col-md-8 col-lg-10 ">
                <select class="form-control border rounded border-dark" id="filters_satker" name="filters_satker"  required onchange="ambilDataSelect('filters_ppk', '{{url('bag/getDataJsonKelas')}}/', 'Pilih Salah Satu', toRemove=['filters_ppk'], removeMessage=['Pilih Salah Satu'], 'filters_satker')">
                    <option value="" hidden>Pilih Salah Satu</option>
                    @foreach ($Satker as $tk)
                        <option value="{{$tk->bag_id}}">{{$tk->bag_nm}}</option>
                        
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4 col-lg-2">
                <label for="filters_ppk">PPK</label>
            </div>
            <div class="form-group col-md-8 col-lg-10 ">
                <select class="form-control border rounded border-dark" id="filters_ppk" name="filters_ppk" required>
                    <option value="" hidden>Pilih Salah Satu</option>
                </select>
            </div>            
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

        <div class="d-flex align-items-center justify-content-center">
            <button type="button" onclick="filterSiswa()" class="btn btn-info mx-1">CARI</button>
            <button type="button" onclick="closeForm('<?= $IdForm ?>filterForm', '<?= $IdForm ?>filterForm', '{{route('absen.filter')}}');" class="btn btn-danger mx-1">TUTUP</button>
        </div>
        
    </div>
    
</form>
<script>
    
    function showFilterTanggal(val = '')
    {
        $('#filtert_blnthn').val('');
        $('#filtert_blnbln').val('');
        
        $('#filtert_rentangawal').val('');
        $('#filtert_rentangakhir').val('');
        $('#filtert_pilih').val('');
        
        if (val=='pilih') {
            $('.filtert_pilih_class').removeClass('d-none');
            $('.filtert_blnbln_class').addClass('d-none');
            
            $('.filtert_rentang_class').addClass('d-none');
        }else if(val == 'bulan'){
            $('.filtert_blnbln_class').removeClass('d-none');
            $('.filtert_pilih_class').addClass('d-none');
            
            $('.filtert_rentang_class').addClass('d-none');
        }else if(val == 'semester'){
            
            $('.filtert_blnbln_class').addClass('d-none');
            $('.filtert_pilih_class').addClass('d-none');
            $('.filtert_rentang_class').addClass('d-none');
        }else if(val == 'rentang'){
            $('.filtert_rentang_class').removeClass('d-none');
            
            $('.filtert_blnbln_class').addClass('d-none');
            $('.filtert_pilih_class').addClass('d-none');
        }else{
            $('.filtert_rentang_class').addClass('d-none');
            
            $('.filtert_blnbln_class').addClass('d-none');
            $('.filtert_pilih_class').addClass('d-none');
        }
    }

    function filterSiswa()
    {
        nextKat = true;
        nextTgl = false;
        

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
            var {{$IdForm}}filterForm = $('#{{$IdForm}}filterForm');
            myData = new FormData();
            
            myData.append('filters_satker', $('#filters_satker').val());
            myData.append('filters_ppk', $('#filters_ppk').val());
            myData.append('filtert_tgl', $('#filtert_tgl').val());
            myData.append('filtert_blnthn', $('#filtert_blnthn').val());
            myData.append('filtert_blnbln', $('#filtert_blnbln').val());
            myData.append('filtert_pilih', $('#filtert_pilih').val());
            myData.append('filtert_rentangawal', $('#filtert_rentangawal').val());
            myData.append('filtert_rentangakhir', $('#filtert_rentangakhir').val());
            myData.append('_token', '{{csrf_token()}}');
            $.ajax({
                type: {{$IdForm}}filterForm.attr('method'),
                url: {{$IdForm}}filterForm.attr('action'),
                enctype: 'multipart/form-data',
                data: myData,
                contentType: false,
                processData: false,
                success: function(data) {
                    hideAnimated();
                    $('#{{$IdForm}}data').html(data);
                },
                error: function(xhr) {
                    hideAnimated();                        
                    showToast(xhr.responseJSON.response.message, 'error');
                }
            });
        }

    }

    function changeSeachData() {
        
        
    }
    // $(function() {
    //     $(document).ready(function() {
    //     });
    // });
</script>