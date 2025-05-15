<div id="modalChangeBag" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalChangeBagTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalChangeBagF" method="POST" action="{{route('bag.update')}}" data-div="" data-url="" enctype="multipart/form-data" data-load="true" data-parsley-validate="">
            @csrf
            <input type="hidden" required="" class="form-control" name="bag_id" id="bag_idC" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeBagTitle">Ubah Nama Bagian</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group required">
                        <label class="control-label"  for="bag_nmC">Nama Bagian</label>
                        <input type="text" required="" class="form-control" name="bag_nm" id="bag_nmC"/>
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
            $('#modalChangeBagF').parsley();
            var modalChangeBagF = $('#modalChangeBagF');
            modalChangeBagF.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#modalChangeBagF').parsley().isValid) {
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: modalChangeBagF.attr('method'),
                        url: modalChangeBagF.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            $('#modalChangeBag').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            if(typeof modalChangeBagF.attr('data-load')!=='undefined'){
                                if (modalChangeBagF.attr('data-load')==='true') {
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