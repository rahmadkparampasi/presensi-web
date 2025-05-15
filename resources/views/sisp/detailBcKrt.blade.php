
    <div class="card-header">
        
        <div class="card-title mb-0">Data Kartu Dan Barcode</div>
    </div>
    <div class="card-body">
        @if ($Sisp!=null)
            <ul class="list-group list-group-flush">
                @csrf
                <div class="form-group p-2 mb-0 pb-0 required">
                    <label class="control-label" for="sisp_bc">Barcode</label><br/>
                    <img style="object-fit: cover" id="sisp_bc"  src="{{url('storage/bc/'.$Sisp->sisp_bc)}}" width="250" alt="Tidak Ada Barcode Siswa">
                </div>
            </ul>
        @else
            <h5>Belum Ada Data Kartu Dan Barcode</h5>
        @endif
    </div>
    