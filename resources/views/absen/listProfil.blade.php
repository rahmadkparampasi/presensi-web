<h5 class="text-center">Daftar Absensi Bulan {{$monthN}} Tahun {{$year}}</h5>
<div class="row mt-2 mb-2">

    @if ($list!=null)
        @for ($i = 0; $i < count($list); $i++)
            @php
                $border = 'border-success';
                if ($list[$i]->absen_sts=='TH') {
                    $border = 'border-danger';
                }elseif($list[$i]->absen_sts=='I') {
                    $border = 'border-primary';
                }
            @endphp
            <div class="col-12 border {{$border}} my-1 rounded" onclick="">
                <div class="row">
                    <div class="col-3 col-lg-2 border-right my-auto" onclick="callModalRefAbsen('{{$list[$i]->absen_id}}')">
                        <h1 class="text-center text-secondary f-20">{{date('d', strtotime($list[$i]->absen_tgl))}}</h1>
                        <h6 class="text-center text-secondary {{$Agent->isMobile() ? 'f-10':'f-12'}}">{{date('F', strtotime($list[$i]->absen_tgl))}}</h6>
                    </div>
                    @if ($list[$i]->absen_sts=='TH')
                        <div class="col-4 col-lg-4 my-auto">
                            <h6 class="text-center text-secondary my-0 f-14">STATUS:</h6>
                            <p class="text-center my-0 f-16"><span class="badge badge-danger">TIDAK HADIR</span></p>
                        </div>
                    @elseif ($list[$i]->absen_sts=='I')
                        <div class="col-4 col-lg-4 my-auto">
                            <h6 class="text-center text-secondary f-14 my-0">STATUS:</h6>
                            <p class="text-center my-0 f-16"><span class="badge badge-primary">IZIN</span></p>
                            
                        </div>
                        <div class="col-4 col-lg-4 my-auto">
                            @if ($list[$i]->sispi_fle=='pdf'||$list[$i]->sispi_fle=='PDF')
                                <button type="button" class="btn btn-info" onclick="changeUrl('{{asset('storage/uploads/'.$list[$i]->sispi_fl)}}', 'Pratinjau Surat Izin');" data-target="#modalViewPdf" data-toggle="modal"><i class="fas fa-eye"></i></button>
                            @else
                                <button type="button" class="btn btn-info" onclick="showPreviewImgSrc('{{asset('storage/uploads/'.$list[$i]->sispi_fl)}}'); $('#modalViewImgTitle').html('Pratinjau Surat Izin')" data-target="#modalViewImg" data-toggle="modal"><i class="fas fa-eye"></i></button>
                            @endif

                        </div>
                    @elseif ($list[$i]->absen_sts=='H')
                        <div class="col-4 col-lg-4 my-auto border-right">
                            <h6 class="text-center text-secondary f-12 my-0">MASUK:</h6>
                            @if ($list[$i]->absen_masukk=="1")
                                <p class="text-left my-0 f-12">Jam: {{$list[$i]->absen_masuk}}</p>
                                <p class="text-left my-0 f-12">Lambat: {{intdiv((int)$list[$i]->absen_lmbt, 60)}} Jam, {{((int)$list[$i]->absen_lmbt % 60)}} Menit</p>
                                <p class="text-left my-0 f-12">Cepat Datang: {{intdiv((int)$list[$i]->absen_cd, 60)}} Jam, {{((int)$list[$i]->absen_cd % 60)}} Menit</p>
                            @else
                                <h6 class="text-left f-12 my-0">TIDAK MELAKUKAN ABSEN MASUK</h6>

                            @endif
                        </div>
                        <div class="col-4 col-lg-4 my-auto">
                            <h6 class="text-center text-secondary f-12 my-0">PULANG:</h6>
                            @if ($list[$i]->absen_keluark=="1")
                                <p class="text-left my-0 f-12">Jam: {{$list[$i]->absen_keluar}}</p>
                                <p class="text-left my-0 f-12">Cepat Pulang: {{intdiv((int)$list[$i]->absen_cp, 60)}} Jam, {{((int)$list[$i]->absen_cp % 60)}} Menit</p>
                                <p class="text-left my-0 f-12">Lambat Pulang: {{intdiv((int)$list[$i]->absen_lbh, 60)}} Jam, {{((int)$list[$i]->absen_lbh % 60)}} Menit</p>
                            @else
                                @if (date("Y-m-d")==$list[$i]->absen_tgl)
                                    <button class='btn btn-primary' onclick="getLocation(); cActForm('absenAddDataProfilModalForm', '{{route('absen.update')}}'); addFill('absen_id', '{{$list[$i]->absen_id}}'); $('#tipe').val('WFO'); closeFormNR('divTipe'); showCam();" data-toggle="modal" data-target="#absenAddDataProfilModal" onclick=""><i class="fa fa-fingerprint" ></i> PULANG</button>
                                @else
                                    <h6 class="text-left f-12 my-0">TIDAK MELAKUKAN ABSEN PULANG</h6>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endfor
    @else
        <h4>Belum Ada Daftar Absensi Bulan {{$monthN}} Tahun {{$year}}</h4>
    @endif
</div>