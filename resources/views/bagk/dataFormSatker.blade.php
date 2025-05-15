<div class="card">
    <form class="mb-5" action="{{route('bagk.insert')}}" data-load="true" id="<?= $IdForm ?>Satker" method="post" enctype="multipart/form-data" data-parsley-validate="" style="display: none !important;">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Form Tambah {{$PageTitle}}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            @csrf
            <input type="hidden" class="form-control" id="bagk_bag" name="bagk_bag" value="{{$bagk_bag}}">
            <input type="hidden" class="form-control" id="bagk_satker" name="bagk_satker" value="{{$Bag->bag_str == '2' ? '1' : '0'}}">
            <div class="form-group p-4 mb-0 pb-0 required">
                <label class="control-label" for="bagk_sisp">Koordinator SATKER</label>
                <select class="form-control select2" id="bagk_sisp" name="bagk_sisp" placeholder="" required>
                    <option value="" hidden>Pilih Salah Satu</option>
                    @foreach ($Sisp as $tk)
                        <option value="{{$tk['sisp_id']}}">{{$tk['sisp_nmAltT']. ' - '.$tk['sisp_idsp']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">SIMPAN</button>
            <button type="button" onclick="closeForm('<?= $IdForm ?>Satker', '<?= $IdForm ?>Satker', '{{route('bagk.insert')}}')" class="btn btn-danger">BATAL</button>
        </div>
    </form>
</div>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 col-lg-8 my-auto">
                <h6>{{$PageTitle}}</h6>
            </div>
            <div class="col-6 col-lg-4">
                <button class='btn btn-primary' onclick="showForm('{{$IdForm}}Satker', 'block'); cActForm('{{$IdForm}}Satker', '{{route('bagk.insert')}}'); resetForm('{{$IdForm}}Satker')"><i class="fa fa-plus"></i> TAMBAH</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (count($BagK)>0)
            <div class="row">
                <div class="col-12">
                    <table class="table table align-items-centertable-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP/NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($BagK as $tk) @php $no++ @endphp 
                            
                            <tr>
                                <td>{{$no}}</td>
                                <td>{{$tk->sisp_idsp}}</td>
                                <td>{{$tk->sisp_nmAltT}}</td>
                            
                                <td>
                                    <button type="button" class="btn btn-danger float-right" onclick="callOtherTWLoad('Menghapus Koordinator SATKER','{{url('bagk/delete/'.$tk->bagk_id)}}', '{{route('bagk.getDataSatker', [$bagk_bag])}}', '<?= $IdForm ?>Satker', 'cardSatker')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
    
<script>
    $(function() {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap4"
            });
            $('#{{$IdForm}}Satker').parsley();
            var {{$IdForm}}Satker = $('#{{$IdForm}}Satker');
            {{$IdForm}}Satker.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#{{$IdForm}}Satker').parsley().isValid) {
                    $('#{{$IdForm}}Satker :input').prop("disabled", false);
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: {{$IdForm}}Satker.attr('method'),
                        url: {{$IdForm}}Satker.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            if(typeof {{$IdForm}}Satker.attr('data-load')!=='undefined'){
                                if ({{$IdForm}}Satker.attr('data-load')==='true') {
                                    $.ajax({
                                        url:"{{route('bagk.getDataSatker', [$bagk_bag])}}",
                                        success: function(data1) {
                                            $('#cardSatker').html(data1);
                                            closeForm('{{$IdForm}}Satker', '{{$IdForm}}Satker')
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