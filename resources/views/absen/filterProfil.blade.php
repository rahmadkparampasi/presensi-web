
<form  action="{{route('absen.profilFilter', [$absen_sisp])}}" data-load="true" id="<?= $IdForm ?>filterForm" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none" onsubmit="return false;">
    <div class="card-body">
        @csrf
        <div class="form-row align-items-center">
            <div class="form-group col-md-4 col-lg-2">
                <label for="filter_tahun">Tahun</label>
            </div>
            <div class="form-group col-md-8 col-lg-10 fill">
                <select class="form-control border rounded border-dark" id="filter_tahun" name="filter_tahun"  required onchange="ambilDataSelect('filter_bulan', '{{url('absen/getDataBulanKelas')}}/', 'Pilih Salah Satu', toRemove=['filter_bulan'], removeMessage=['Pilih Salah Satu'], 'filter_tahun')">
                    <option value="" hidden>Pilih Salah Satu</option>
                    @foreach ($yearSelect as $tk)
                        <option value="{{$tk->year}}">{{$tk->year}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>
        <div class="form-row align-items-center">
            <div class="form-group col-md-4 col-lg-2">
                <label for="filter_bulan">Bulan</label>
            </div>
            <div class="form-group col-md-8 col-lg-10 fill">
                <select class="form-control border rounded border-dark" id="filter_bulan" name="filter_bulan" required>
                    <option value="" hidden>Pilih Salah Satu</option>
                </select>
            </div>
            
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <button type="button" onclick="filterSiswa()" class="btn btn-info mx-1">CARI</button>
            <button type="button" onclick="closeForm('<?= $IdForm ?>filterForm', '<?= $IdForm ?>filterForm', '{{route('absen.profilFilter')}}');" class="btn btn-danger mx-1">TUTUP</button>
        </div>
        
    </div>
    
</form>
<script>
    function filterSiswa()
    {
        var {{$IdForm}}filterForm = $('#{{$IdForm}}filterForm');
        myData = new FormData();
        myData.append('filter_tahun', $('#filter_tahun').val());
        myData.append('filter_bulan', $('#filter_bulan').val());
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
    function changeSeachData() {
        
        
    }
    // $(function() {
    //     $(document).ready(function() {
    //     });
    // });
</script>