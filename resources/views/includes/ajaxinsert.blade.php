<script>
    $(function() {
        $(document).ready(function() {
            var <?= $IdForm ?> = $('#<?= $IdForm ?>');
            <?= $IdForm ?>.submit(function(e) {
                showAnimated();
                $('#<?= $IdForm ?> :input').prop("disabled", false);
                $(this).attr('disabled', 'disabled');
                e.stopPropagation();
                e.preventDefault();
                $.ajax({
                    type: <?= $IdForm ?>.attr('method'),
                    url: <?= $IdForm ?>.attr('action'),
                    enctype: 'multipart/form-data',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        hideAnimated();
                        swal.fire({
                            title: "Terima Kasih",
                            text: data.response.message,
                            icon: data.response.response
                        }).then(function() {
                            window.location = '<?= $UrlForm ?>';
                        });
                    },
                    error: function(xhr) {
                        hideAnimated();                        
                        swal.fire({
                            title: "Tidak Dapat Melanjutkan Proses",
                            text: xhr.responseJSON.response.message,
                            icon: "error"
                        });
                    }
                });
            });
        });
    });
    </script>