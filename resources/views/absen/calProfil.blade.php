<h5 class="text-center">Kalender Absensi Bulan {{$monthN}} Tahun {{$year}}</h5>
<div class="row mt-2 mb-2">

    @if ($cal!=null)
        @for ($i = 0; $i < count($cal); $i++)
            <div class="col mt-2 mb-2 pr-0 pl-0 text-center" style="flex: 0 0 14%; max-width: 14%;">
                <a role="button" class="position-relative {{$Agent->isMobile() ? 'pr-0 pl-0':''}} font-weight-bold btn rounded {{isset($cal[$i]['color']) ? 'text-white btn-'.$cal[$i]['color'] : '' }}" style="{{$Agent->isMobile() ? 'width: 90%; font-size: 12px; white-space: nowrap; text-align:center;' :'font-size: 14px;'}}"
                    {{isset($cal[$i]['id']) ? "onclick=callModalRefAbsen('".$cal[$i]['id']."')" : ''}}>
                    @if (isset($cal[$i]['node']))
                        <div class="position-absolute bg-{{$cal[$i]['node']}} border border-white rounded-circle" style="width: 10px; height: 10px; top: 0; left: 0;"></div>
                    @endif
                    {{$cal[$i]['val']}}
                </a>
            </div>
        @endfor
    @endif
</div>