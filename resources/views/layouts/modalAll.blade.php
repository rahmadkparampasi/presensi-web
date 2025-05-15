<div id="{{$idModalAll}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="{{$idModalAll}}Title" aria-modal="true">
    <div class="modal-dialog {{$sizeModalAll}}" role="document">
        <form id="{{$idModalAll}}Form" method="POST" action="{{$urlModalAll}}" enctype="multipart/form-data" data-load="{{$dataLoadModalAll}}" data-div="{{$divLoadModalAll}}" data-urlload="{{$urlLoadModalAll}}" data-parsley-validate="">
            @csrf
            @yield('contentInputHidden')

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="{{$idModalAll}}Title">{{$titleModalAll}}</h6>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @yield('contentModalBody'.$countModalBody)
                </div>
                <div class="modal-footer">
                    @yield('contentModalFooter'.$countModalFooter)
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function() {
        $(document).ready(function() {
            var {{$idModalAll}}Form = $('#{{$idModalAll}}Form');
            {{$idModalAll}}Form.submit(function(e) {
                showAnimated();
                //$('#addChildRmr :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: {{$idModalAll}}Form.attr('method'),
                    url: {{$idModalAll}}Form.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        console.log(data);
                        $('#{{$idModalAll}}').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        if(typeof {{$idModalAll}}Form.attr('data-load')!=='undefined'){
                            if ({{$idModalAll}}Form.attr('data-load')==='true') {
                                $.ajax({
                                    url:{{$idModalAll}}Form.attr('data-urlload'),
                                    success: function(data1) {
                                        if(typeof {{$idModalAll}}Form.attr('data-div')!=='undefined'){
                                            $('#'+{{$idModalAll}}Form.attr('data-div')).html(data1);
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
                        console.log(xhr);
                        showToast(xhr.responseJSON.response.message, 'error');
                    }
                });
            });
        });
    });
</script>