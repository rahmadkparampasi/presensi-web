<form class="" action="{{route('baginm.insert')}}" data-load="true" id="<?= $IdForm ?>Inm" method="post" enctype="multipart/form-data" data-parsley-validate="">
    @csrf
    <input type="hidden" class="form-control" id="baginm_bag" name="baginm_bag" value="{{$bag_id}}">
    
    <div class="row mt-3">
        @for ($i = 1; $i <= 13; $i++)
            @php
                $selectInm = '';
            @endphp
            @for ($j=0; $j < count($Baginm); $j++)
                @php
                if ($Baginm[$j]=="") {
                    continue;
                }
                if ($i==(int)$Baginm[$j]) {
                    $selectInm = "checked";
                }
                @endphp
            @endfor
            <div class="col-lg-3 col-6">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="checkBagInm{{$i}}" name="baginm_inm[]" value="{{$i}}" {{$selectInm}}>
                    <label class="form-check-label" for="checkBagInm{{$i}}">INM {{$i}}</label>
                </div>
            </div>
        @endfor
    </div>
    <button type="submit" class="btn btn-primary">SIMPAN</button>
</form>
<script>
    $(function() {
        $(document).ready(function() {
            $('#{{$IdForm}}Inm').parsley();
            var {{$IdForm}}Inm = $('#{{$IdForm}}Inm');
            {{$IdForm}}Inm.submit(function(e) {
                showAnimated();
                e.preventDefault();
                if ($('#{{$IdForm}}Inm').parsley().isValid) {
                    $('#{{$IdForm}}Inm :input').prop("disabled", false);
                    $(this).attr('disabled', 'disabled');
                    e.stopPropagation();
                    $.ajax({
                        type: {{$IdForm}}Inm.attr('method'),
                        url: {{$IdForm}}Inm.attr('action'),
                        enctype: 'multipart/form-data',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            hideAnimated();
                            showToast(data.response.message, 'success');
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