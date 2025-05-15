<div id="modalAddBag" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalAddBagTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalAddBagF" method="POST" action="{{route('bag.insert')}}" data-div="" data-url="" enctype="multipart/form-data" data-load="true" data-parsley-validate="">
            @csrf
            <input type="hidden" required="" class="form-control" name="bag_prnt" id="bag_prnt" />
            <input type="hidden" required="" class="form-control" name="bag_str" id="bag_str" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddBagTitle">Tambah Bagian</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group required">
                        <label class="control-label"  for="bag_prntNm">Bagian Atas</label>
                        <input type="text" readonly class="form-control" name="bag_prntNm" id="bag_prntNm"/>
                    </div>
                    <div class="form-group required">
                        <label class="control-label"  for="bag_nm">Nama Bagian</label>
                        <input type="text" required="" class="form-control" name="bag_nm" id="bag_nm"/>
                    </div>
                    <div class="form-group required">
                        <label class="control-label"  for="bag_thn">Tahun</label>
                        <input type="text" required="" class="form-control" name="bag_thn" id="bag_thn"/>
                    </div>
                    <div class="form-group required">
                        <label class="control-label"  for="bag_desk">Deskripsi</label>
                        <textarea required="" class="form-control" name="bag_desk" id="bag_desk" cols="3"> </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="item form-group">
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $(document).ready(function() {
            $('#modalAddBagF').parsley();
            var modalAddBagF = $('#modalAddBagF');
            modalAddBagF.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#modalAddBagF').parsley().isValid) {
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: modalAddBagF.attr('method'),
                        url: modalAddBagF.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            $('#modalAddBag').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            if(typeof modalAddBagF.attr('data-load')!=='undefined'){
                                if (modalAddBagF.attr('data-load')==='true') {
                                    setTimeout(() => {
                                        nSBag = sessionStorage.getItem("nSBag");
                                        fill_treeview();
                                    }, 3000);
                                    showToast(data.response.message, 'success');
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location.reload();
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
                }else{
                    hideAnimated();
                }
            });
        });
    });
</script>