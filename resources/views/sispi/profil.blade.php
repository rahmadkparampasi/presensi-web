<div class="col-12" >
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 col-lg-8 my-auto">
                    <h6>Data Izin Siswa</h6>
                </div>
                <div class="col-6 col-lg-4">
                    <button class='btn btn-primary' style="float: right;" data-target="#sispiAddDataModal" data-toggle="modal" onclick="callModalSispi()"><i class="fa fa-plus"></i> TAMBAH</button>
                </div>
            </div>
            
        </div>
        <div class="card-body" id="{{$IdForm}}data">
            @include('sispi.dataProfil')
        </div>
    </div>
</div>
@include('sispi.modalAddDataProfil', ['countModalBody' => 'Sispi', 'countModalFooter' => 'Sispi'])
