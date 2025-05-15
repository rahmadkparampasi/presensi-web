<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('setkati.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="setkati_id" name="setkati_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setkati_nm">Nama Kategori Izin</label>
                    <input type="text" class="form-control" id="setkati_nm" name="setkati_nm" placeholder="" required>
                </div>
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setkati_kd">Kode Kategori Izin</label>
                    <input type="text" class="form-control" id="setkati_kd" name="setkati_kd" placeholder="" required>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('setkati.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>