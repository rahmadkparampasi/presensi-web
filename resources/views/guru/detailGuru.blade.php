<div class="card d-flex position-relative">
    @php
        $pic = '';
    @endphp
    <img style="object-fit: cover" onclick="showPreviewImgSrc('{{asset('storage/uploads/'.$Guru->sisp_pic)}}'); $('#modalViewImgTitle').html('Pratinjau Foto {{$Guru->sisp_nmAltT}} | NIP/NIK : {{$Guru->sisp_idsp}}')" data-target="#modalViewImg" data-toggle="modal" class="card-img-top" src="{{ $Guru->sisp_pic!='' ? url('storage/uploads/'.$Guru->sisp_pic) : url('assets/img/user.png')}}" height="250" alt="Foto Pegawai {{$Guru->sisp_nmAltT}}">

    <button class="btn btn-warning btn-sm top-0 position-absolute" onclick="$('#modalChangeImgF').attr('action', '{{route('sisp.pic')}}'); $('#modalChangeImgTitle').html('Ubah Foto Guru'); $('#brksImgId').attr('name', 'sisp_id'); addFill('brksImgId', '{{$Guru->sisp_id}}'); addFill('brksImgName', '{{$Guru->sisp_pic}}');$('#brksImg').attr('name', 'sisp_pic'); $('#brksImgPre').attr('src', '{{url('storage/uploads/'.$Guru->sisp_pic)}}'); $('#modalChangeImgF').attr('data-urlload', '{{route('sisp.detailSisp', [$Guru->sisp_id])}}'); $('#modalChangeImgF').attr('data-div', 'guruDetailGuru');" data-toggle="modal" data-target="#modalChangeImg"><i class="fa fa-sync"></i></button>

    <div class="card-body">
        <h5 class="text-center mb-0">{{$Guru->sisp_nmAltT}}</h5>
        <p class="text-center text-muted">{{$Guru->sisp_idsp}}</p>

        <div class="w-100 d-flex justify-content-center mb-1">
            @if ($Pgn->users_tipe=='A')
                {!!$Guru->sisp_actAltBu!!}
            @else
                {!!$Guru->sisp_actAltBa!!}
            @endif

        </div>

        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-id-badge mr-3"></i><p class="mb-0">{{$Guru->sisp_satkernm.' - '.$Guru->bag_nm}}</p></div>
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa {{$Guru->sisp_jk=='L' ? 'fa-male' : 'fa-female'}} mr-3"></i><p class="mb-0">{{$Guru->sisp_jkAltT}}</p></div>
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-birthday-cake mr-3"></i><p class="mb-0">{{$Guru->sisp_tmptlhr}}, {{$Guru->sisp_tgllhrAltT}}</p></div>
        
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-graduation-cap mr-3"></i><p class="mb-0">{{$Guru->setpd_nm}}</p></div>
        <div class="d-inline-flex align-items-center justify-content-start w-100 mb-3"><i class="fa fa-map-signs mr-3"></i><p class="mb-0">{{$Guru->sisp_alt}} {!!$Guru->sisp_telpAltT!!}</p></div>
    </div>
    <div class="card-footer">
        <button class='btn btn-primary mx-1 w-100' data-target="#modalEditGuru" data-toggle="modal" onclick=""><i class="fa fa-pen"></i> UBAH DATA PEGAWAI</button>
    </div>
</div>
