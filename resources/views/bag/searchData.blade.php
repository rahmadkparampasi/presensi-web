<form  action="{{route('haki.search')}}" data-load="true" id="<?= $IdForm ?>searchForm" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none">
    <div class="card-body">
        @csrf
        <div class="form-row align-items-center">
            <div class="form-group col-md-4 col-lg-2 mb-0">
                <select class="form-control" id="search_key" name="search_key" required>
                    <option value="Judul">Judul HAKI</option>
                    <option value="Nomor">Nomor HAKI</option>
                </select>
            </div>
            <div class="form-group col-md-8 col-lg-10 fill mb-0">
                <input type="text" class="form-control" id="search_val" name="search_val" placeholder="Masukan Kata Kunci" required oninput="">
                
            </div>
            <div class="form-group col-md-4 col-lg-2"></div>
            <div class="form-group col-md-8 col-lg-10 fill">
                <small>Masukan Kata Kunci Minimal 3 Karakter</small>
            </div>
            
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <button type="button" onclick="closeForm('<?= $IdForm ?>searchForm', '<?= $IdForm ?>searchForm', '{{route('haki.search')}}'); " class="btn btn-danger">TUTUP</button>
        </div>
        
    </div>
    
</form>
<script>
    $("#search_val").on("input", function(){
        if(this.value.length>=3){
            var {{$IdForm}}searchForm = $('#{{$IdForm}}searchForm');
            myData = new FormData();
            myData.append('search_key', $('#search_key').val());
            myData.append('search_val', $('#search_val').val());
            myData.append('_token', '{{csrf_token()}}');
            $.ajax({
                type: {{$IdForm}}searchForm.attr('method'),
                url: {{$IdForm}}searchForm.attr('action'),
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
    });
    function changeSeachData() {
        
        
    }
</script>