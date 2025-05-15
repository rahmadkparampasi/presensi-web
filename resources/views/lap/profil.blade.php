<div class="col-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data Laporan Pegawai</h6>
                </div>
                <div class="col-6 col-lg-4">
                    <button class='btn btn-primary' style="float: right;" data-target="#lapAddDataModal" data-toggle="modal" onclick="callModalLap()"><i class="fa fa-plus"></i> TAMBAH</button>
                </div>
            </div>
            
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('lap.dataProfil')
        </div>
    </div>
</div>

@include('lap.modalAddDataProfil', ['countModalBody' => 'Lap', 'countModalFooter' => 'Lap'])
@include('lap.modalChangeDataProfil', ['countModalBody' => 'LapC', 'countModalFooter' => 'LapC'])
