<div class="col-sm-12">
    <div class="card" style="<?= $DisplayForm ?>" id="<?= $IdForm ?>card">
        <div class="card-header">
            <h5 id="<?= $IdForm ?>title">Form Tambah</h5><h5>{{$PageTitle}}</h5>
        </div>
        <form class="" action="{{route('setkatpes.insert')}}" data-load="true" id="<?= $IdForm ?>" method="post" enctype="multipart/form-data" data-parsley-validate="">
            <div class="card-body">
                @csrf
                <input type="hidden" class="form-control" id="setkatpes_id" name="setkatpes_id">
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setkatpes_nm">Nama Kategori Peserta</label>
                    <input type="text" class="form-control" id="setkatpes_nm" name="setkatpes_nm" placeholder="" required>
                </div>
                
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setkatpesj_masukKrj">Jam Masuk Otomatis</label>
                    <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_masukKrj" name="setkatpesj_masuk" placeholder="Ketik Disini" required>
                </div>
                
                <div class="form-group p-4 mb-0 pb-0 required">
                    <label class="control-label" for="setkatpesj_keluarKrj">Jam Keluar Otomatis</label>
                    <input type="text" class="setkatpesj form-control border rounded border-dark" id="setkatpesj_keluarKrj" name="setkatpesj_keluar" placeholder="Ketik Disini" required>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <button type="button" onclick="closeForm('<?= $IdForm ?>card', '<?= $IdForm ?>', '{{route('setkatpes.insert')}}')" class="btn btn-danger">BATAL</button>
            </div>
        </form>
    </div>
</div>