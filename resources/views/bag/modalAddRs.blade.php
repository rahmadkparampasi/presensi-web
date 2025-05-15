<div id="modalAddRs" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalAddRsTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalAddRsF" method="POST" action="{{route('bag.insertBaru')}}" data-div="" data-url="" enctype="multipart/form-data" data-load="true" data-parsley-validate="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddRsTitle">Tambah Unit Baru</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group required">
                        <label class="control-label"  for="bag_nmRs">Nama Unit</label>
                        <input type="text" required class="form-control" name="bag_nmRs" id="bag_nmRs"/>
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
            $('#modalAddRsF').parsley();
            var modalAddRsF = $('#modalAddRsF');
            modalAddRsF.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#modalAddRsF').parsley().isValid) {
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: modalAddRsF.attr('method'),
                        url: modalAddRsF.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            $('#modalAddRs').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            if(typeof modalAddRsF.attr('data-load')!=='undefined'){
                                if (modalAddRsF.attr('data-load')==='true') {
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