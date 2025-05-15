<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5>Form Tambah {{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('ag.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="ag_id" name="ag_id">
                <div class="form-group row p-4 mb-0 pb-0">
                    <label for="ag_nm">Nama Agama</label>
    
                    <input type="text" class="form-control" id="ag_nm" name="ag_nm" placeholder="Masukan Nama Agama" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('ag.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>