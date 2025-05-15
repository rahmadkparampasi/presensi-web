<div class="card d-flex position-relative">
    

    <div class="card-body">
        
        <div class="w-100 d-flex justify-content-center mb-1">
            @if ($Pgn->users_tipe=='A')
                {!!$Survei->survei_actAltBu!!}
            @else
                {!!$Survei->survei_actAltBa!!}
            @endif

        </div>

        
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-calendar-alt mr-3"></i><p class="mb-0">Tahun Survei: {{$Survei->survei_thn}}</p></div>
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-calendar-times mr-3"></i><p class="mb-0">Kuis: {{$Survei->survei_kuisAltT}}</p></div>
    </div>
    <div class="card-footer">
        <button class='btn btn-primary mx-1 w-100' data-target="#modalEditSurvei" data-toggle="modal" onclick=""><i class="fa fa-pen"></i> UBAH DATA SURVEI</button>
    </div>
</div>
