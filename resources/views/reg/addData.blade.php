<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5>Form Tambah {{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('reg.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="reg_id" name="reg_id">
                <div class="form-group row p-4 mb-0 pb-0">
                    <label for="reg_jdl">Judul Regulasi Atau Informasi</label>
                    <input type="text" class="form-control" id="reg_jdl" name="reg_jdl" placeholder="" required>
                </div>
                <div class="form-group row p-4 mb-0 pb-0">
                    <label for="reg_inst">Nama Instansi</label>
                    <input type="text" class="form-control" id="reg_inst" name="reg_inst" placeholder="" required>
                    <small>Nama Instansi Yang Menerbitkan Regulasi Atau Informasi Atau Informasi</small>
                </div>
                <div class="form-group row p-4 mb-0 pb-0">
                    <label for="reg_fl">Berkas</label>
                    <input type="file" class="form-control" accept="application/pdf" id="reg_fl" name="reg_fl" placeholder="" required data-parsley-max-file-size="5000" data-parsley-max-file-dimensions="1080x1080">
                    <small>Berkas tidak dapat melebihi 5Mb</small>

                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('reg.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>