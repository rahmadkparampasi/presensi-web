<script>
    $(function() {
        $(document).ready(function() {
            var {{$IdForm}} = $('#{{$IdForm}}');
            {{$IdForm}}.submit(function(e) {
                showAnimated();
                $('#{{$IdForm}} :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: {{$IdForm}}.attr('method'),
                    url: {{$IdForm}}.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        if(typeof {{$IdForm}}.attr('data-load')!=='undefined'){
                            if ({{$IdForm}}.attr('data-load')==='true') {
                                $.ajax({
                                    url:"{{url($BasePage.'/load')}}",
                                    success: function(data1) {
                                        $('#{{$IdForm}}data').html(data1);
                                        closeForm('{{$IdForm}}card', '{{$IdForm}}')
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
            });
        });
    });
</script>