<div id="modalChangeImg" class="modal" tabindex="-1" role="dialog" aria-labelledby="modalChangeImgTitle" aria-modal="true">
    <div class="modal-dialog" role="document">
        <form id="modalChangeImgF" method="POST" action="" enctype="multipart/form-data" data-load="true" data-div="cardBodyDataKre" data-urlload="" data-parsley-validate="">
            @csrf
            <input type="hidden" id="brksImgId" name="brksImgId" value="" />
            <input type="hidden" id="brksImgName" name="brksImgName" value="" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeImgTitle">Ubah Gambar</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="brksImg">Berkas Gambar</label>
                        <div class="w-100 d-flex justify-content-center align-content-center">
                            <img id="brksImgPre" src="{{url('assets/img/image.png')}}" alt="Gambar" class="w-100"/>
                        </div>
    
                        <input type="file" required accept="image/png, image/jpeg" class="form-control" name="brksImg" id="brksImg" onchange="showPreviewBrksImg(event);" data-parsley-max-file-size="5120" data-parsley-trigger="change" />
                        <small>Berkas tidak dapat melebihi 5120kb</small>
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
    function showPreviewBrksImg(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("brksImgPre");
            preview.src = src;
            preview.style.display = "block";
        }
    }
    $(function() {
        $(document).ready(function() {
            window.Parsley.addValidator('maxFileSize', {
                validateString: function(_value, maxSize, parsleyInstance) {
                    if (!window.FormData) {
                        alert('You are making all developpers in the world cringe. Upgrade your browser!');
                        return true;
                    }
                    var files = parsleyInstance.$element[0].files;
                    return files.length != 1  || files[0].size <= maxSize * 1024;
                },
                requirementType: 'integer',
                messages: {
                    en: 'This file should not be larger than %s Kb',
                    id: 'Berkas tidak dapat lebih dari %s Kb.',
                }
            });
        });
    });
    $(function() {
        $(document).ready(function() {
            var modalChangeImgF = $('#modalChangeImgF');
            modalChangeImgF.submit(function(e) {
                showAnimated();
                //$('#addChildRmr :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: modalChangeImgF.attr('method'),
                    url: modalChangeImgF.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        $('#modalChangeImg').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        if(typeof modalChangeImgF.attr('data-load')!=='undefined'){
                            if (modalChangeImgF.attr('data-load')==='true') {
                                $.ajax({
                                    url:modalChangeImgF.attr('data-urlload'),
                                    success: function(data1) {
                                        if(typeof modalChangeImgF.attr('data-div')!=='undefined'){
                                            $('#'+modalChangeImgF.attr('data-div')).html(data1);
                                        }
                                        showToast(data.response.message, 'success');
                                    },
                                    error:function(xhr) {
                                        window.location.reload();
                                    }
                                });
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