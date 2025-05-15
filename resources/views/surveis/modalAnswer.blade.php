<input type="hidden" id="surveis_sisp" name="surveis_sisp" value="{{$sisp}}" />

<div class="accordion" id="accordionExample">
    <input type="hidden" id="surveis_survei" name="surveis_survei[]" value="{{$Survei->survei_id}}" />

    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Survei {{$Survei->survei_thn}}</button>
            </h5>
        </div>
    
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                @foreach ($Survei->q as $tkd)
                    <div class="form-group p-2 mb-0 pb-0 required">
                        <label for="surveisa_surveiqa">{{$tkd->surveiq_lbl}}</label>
                        <select class="form-control border rounded border-dark" id="surveisa_surveiqa" name="surveisa_surveiqa[]" required>
                            
                            @foreach ($tkd->a as $tkda)
                                <option value="{{$tkda->surveiqa_id}}">{{$tkda->surveiqa_a}}</option>
                            @endforeach
                        </select>
                        <small>{{$tkd->surveiq_desk}}</small>
                    </div>
                
                @endforeach
            </div>
        </div>
    </div>
</div>
