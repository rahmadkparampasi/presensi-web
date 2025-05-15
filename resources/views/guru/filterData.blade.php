<form  action="{{route('sisp.filter', [$act])}}" data-load="true" id="<?= $IdForm ?>filterForm" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none" onsubmit="return false;">
    <div class="card-body">
        @csrf
        <div class="form-row align-items-center">
            <div class="form-group col-md-4 col-lg-2">
                <label for="filter_sts">Status Pegawai</label>
            </div>
            <div class="form-group col-md-8 col-lg-10 fill">
                <select type="text" class="form-control border rounded border-dark" id="filter_sts" name="filter_sts" required >
                    <option value="" hidden>Pilih Salah Satu</option>
                    @foreach ($Setstspeg as $tk)
                        <option value="{{$tk['setstspeg_id']}}">{{$tk['setstspeg_nm']}}</option>
                    @endforeach
                </select>
            </div>
            
        </div>
        
        <div class="d-flex align-items-center justify-content-center">
            <button type="button" onclick="filterGuru()" class="btn btn-info mx-1">CARI</button>
            {{-- <button type="button" onclick="closeForm('<?= $IdForm ?>filterForm', '<?= $IdForm ?>filterForm', '{{route('siswa.filter')}}');" class="btn btn-danger mx-1">TUTUP</button> --}}
        </div>
        
    </div>
    
</form>
<script>
    function filterGuru()
    {
        var {{$IdForm}}filterForm = $('#{{$IdForm}}filterForm');
        myData = new FormData();
        myData.append('filter_sts', $('#filter_sts').val());
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