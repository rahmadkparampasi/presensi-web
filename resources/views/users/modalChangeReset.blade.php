<div id="modalChangeReset" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalChangeResetTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalChangeResetF" method="POST" action="{{route('user.updateReset')}}" enctype="multipart/form-data" data-load="true">
            @csrf
            <input type="hidden" id="users_idReset" name="users_id" />
            <input type="hidden" id="users_idResetSession" name="users_id_session" value="{{$Pgn->users_id}}" />
            <input type="hidden" id="tipeReset" value="D" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeResetTitle">Reset Password</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="users_nmReset">Nama Pengguna</label>
                        <input type="text" class="form-control" id="users_nmReset" name="users_nm" placeholder="" required readonly>
                    </div>
                    
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="passwordReset">Password Baru</label>
                        <input type="password" class="form-control" id="passwordReset" name="password" placeholder="" required>
                    </div>

                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="password_confirmationReset">Ulangi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmationReset" name="password_confirmation" placeholder="" required>
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
            var modalChangeResetF = $('#modalChangeResetF');
            modalChangeResetF.submit(function(e) {
                showAnimated();
                //$('#addChildRmr :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: modalChangeResetF.attr('method'),
                    url: modalChangeResetF.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        $('#modalChangeReset').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        if(typeof modalChangeResetF.attr('data-load')!=='undefined'){
                            if (modalChangeResetF.attr('data-load')==='true') {
                                var tipeReset = $('#tipeTmbh').val();
                                var users_idReset = $('#users_idReset').val();
                                var users_idResetSession = $('#users_idResetSession').val();
                                if (tipeReset==''||tipeReset!='D') {
                                    if (users_idReset==users_idResetSession) {
                                        swal.fire({
                                        title: "Terima Kasih",
                                        text: data.response.message,
                                        icon: data.response.response
                                        }).then(function() {
                                            window.location.reload();
                                        });
                                    }else{
                                        $.ajax({
                                            url:"{{url($BasePage.'/load')}}",
                                            success: function(data1) {
                                                $('#{{$IdForm}}data').html(data1);
                                                resetForm('modalChangeResetF')
                                                showToast(data.response.message, 'success');
                                            },
                                            error:function(xhr) {
                                                window.location = "{{url($UrlForm)}}";
                                            }
                                        });
                                    }
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
            });
        });
    });
</script>