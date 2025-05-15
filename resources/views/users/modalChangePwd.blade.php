<div id="modalChangePwd" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalChangePwdTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalChangePwdF" method="POST" action="{{route('user.updatePwd')}}" enctype="multipart/form-data" data-load="true">
            @csrf
            <input type="hidden" id="users_idPwd" name="users_id" />
            <input type="hidden" id="users_idPwdSession" name="users_id_session" value="{{$Pgn->users_id}}" />
            <input type="hidden" id="tipePwd" value="D" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangePwdTitle">Ubah Password</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="users_nmPwd">Nama Pengguna</label>
                        <input type="text" class="form-control" id="users_nmPwd" name="users_nm" placeholder="" required readonly>
                    </div>
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="password_old">Password Lama</label>
                        <input type="password" class="form-control" id="password_old" name="password_old" placeholder="" required>
                    </div>
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="password_new">Password Baru</label>
                        <input type="password" class="form-control" id="password_new" name="password" placeholder="" required>
                    </div>
                    <div class="form-group p-4 mb-0 pb-0 required col-12">
                        <label class="control-label" for="password_new1">Ulangi Password Baru</label>
                        <input type="password" class="form-control" id="password_new1" name="password_confirmation" placeholder="" required>
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
            var modalChangePwdF = $('#modalChangePwdF');
            modalChangePwdF.submit(function(e) {
                showAnimated();
                //$('#addChildRmr :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: modalChangePwdF.attr('method'),
                    url: modalChangePwdF.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        $('#modalChangePwd').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        if(typeof modalChangePwdF.attr('data-load')!=='undefined'){
                            if (modalChangePwdF.attr('data-load')==='true') {
                                var tipePwd = $('#tipeTmbh').val();
                                var users_idPwd = $('#users_idPwd').val();
                                var users_idPwdSession = $('#users_idPwdSession').val();
                                if (tipePwd==''||tipePwd!='D') {
                                    if (users_idPwd==users_idPwdSession) {
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
                                                resetForm('modalChangePwdF')
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