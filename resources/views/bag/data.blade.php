<table id="{{$IdForm}}dT" class=" display table align-items-centertable-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th class="text-wrap">Nomor</th>
            <th class="text-wrap">Judul</th>
            <th class="text-wrap">Inovasi / Produk</th>
            <th class="text-wrap">Inovator</th>
            <th class="text-wrap">Kategori</th>
            <th class="text-wrap">Jenis</th>
            <th class="text-wrap">Tanggal</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($Haki as $tk) @php $no++ @endphp 
        
        <tr>
            <td>{{$no}}</td>
            <td class="text-wrap">{{$tk['haki_nmr']}}</td>
            <td class="text-wrap">{{$tk['haki_jdl']}}</td>
            <td class="text-wrap">{{$tk['kegd_nama']}}</td>
            <td class="text-wrap">{{$tk['kegd_ino']}}</td>
            <td class="text-wrap">{{$tk['haki_katAltT']}}</td>
            <td class="text-wrap">{{$tk['haki_jnsAltT']}}</td>
            <td class="text-wrap">{{$tk['haki_tglAltT']}}</td>
            <td>
                <button data-toggle="modal" data-target="#modalViewPdfFrame" class="btn btn-success" onclick="$('#modalViewPdfFrameLabel').html('LIHAT BERKAS HAKI {{$tk['haki_jdl']}}'); $('#modalViewPdfFrameLabelFrame').attr('src', '{{url('uploads/'.$tk['haki_fl'])}}'); $('#modalViewPdfFrameLabelDownload').attr('href', '{{url('uploads/'.$tk['haki_fl'])}}');"><i class="fas fa-file-pdf"></i></button>

                <a href="{{url($tk['haki_kat'].'/detail/'.$tk['kegd_idIno'])}}" target="_blank" class="btn btn-primary" onclick=""><i class="fas fa-eye"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@if (isset($search))
    <div class="d-flex align-items-center justify-content-center">
        <button type="button" onclick="closeForm('<?= $IdForm ?>searchForm', '<?= $IdForm ?>searchForm', '{{route('haki.search')}}'); cancelSearch();" class="btn btn-danger">TUTUP HASIL PENCARIAN</button>
    </div>
    <script>
        function cancelSearch() {
            $.ajax({
                url:"{{url($BasePage.'/load')}}",
                success: function(data1) {
                    $('#{{$IdForm}}data').html(data1);
                },
                error:function(xhr) {
                    window.location = "{{url($UrlForm)}}";
                }
            });
        }
    </script>
@endif
<script>
    $(document).ready(function() {
        dTD('table#{{$IdForm}}dT');
    });
</script>