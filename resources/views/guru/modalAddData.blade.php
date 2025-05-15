@extends('layouts.modalAll', ['idModalAll' => 'modalAddData', 'sizeModalAll' => '', 'divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' =>route('sisp.load'), 'dataLoadModalAll'=>'true','urlModalAll'=>route('sisp.insert'), 'titleModalAll' => 'TAMBAH PEGAWAI'])

@section('contentInputHidden')
    <input type="hidden" id="sisp_setkatpes" name="sisp_setkatpes" value="{{$Setkatpes_ps->setkatpes_id}}" required>
@endsection
@section('contentModalBody'.$countModalBody)
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_idsp">NIP (Nomor Induk Pegawai) / KTP (Bagi Yang Belum ASN/PPPK)</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_idsp" name="sisp_idsp" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0">
        <label class="control-label" for="sisp_nmd">Gelar Depan</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nmd" name="sisp_nmd">
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_nm">Nama Lengkap</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nm" name="sisp_nm" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0">
        <label class="control-label" for="sisp_nmd">Gelar Belakang</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nmb" name="sisp_nmb">
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sispdp_setstspeg">Status Pegawai</label>
        <select type="text" class="form-control border rounded border-dark" id="sispdp_setstspeg" name="sispdp_setstspeg" required >
            <option value="" hidden>Pilih Salah Satu</option>
            @foreach ($Setstspeg as $tk)
                <option value="{{$tk['setstspeg_id']}}">{{$tk['setstspeg_nm']}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_tmptlhr">Tempat Lahir</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tmptlhr" name="sisp_tmptlhr" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_tgllhr">Tanggal Lahir</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tgllhr" name="sisp_tgllhr" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_jk">Jenis Kelamin</label>
        <select class="form-control border rounded border-dark" id="sisp_jk" name="sisp_jk" required>
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="L">Laki-Laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
    
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_alt">Alamat</label>
        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_alt" name="sisp_alt"required cols="30" rows="2"></textarea>
        <small>Masukan Alamat Hanya Nama Jalan, Nama Lorong, RTRW Atau Dusun. (Tidak Perlu Menginputkan Nama Desa, Kecamatan Atau Kabupaten Pada Isian Ini)</small>
    </div>

    <div class="form-group p-2 mb-0 pb-0">
        <label class="control-label" for="sisp_telp">Telepon</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_telp" name="sisp_telp">
        <small>Jika Tidak Ada Nomor Telepon, Kosongkan Saja</small>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_pic">Foto Pegawai</label>
        <div class="w-100 d-flex justify-content-center align-content-center">
            <img id="sisp_picPre" src="{{url('assets/img/user.png')}}" alt="Gambar" class="w-100"/>
        </div>

        <input type="file" required accept="image/png, image/jpeg" class="form-control" name="sisp_pic" id="sisp_pic" onchange="showPreviewBrksImg(event);" data-parsley-max-file-size="500" data-parsley-trigger="change" />
        <small>Berkas tidak dapat melebihi 500kb</small>
    </div>

    <script>
        function showPreviewBrksImg(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("sisp_picPre");
                preview.src = src;
                preview.style.display = "block";
            }
        }
        $(function() {
            $(document).ready(function() {
                window.Parsley.addValidator('maxFileSize', {
                    validateString: function(_value, maxSize, parsleyInstance) {
                        if (!window.FormData) {
                            alert('You are making all developpers in the world cringe. Upgrade your browser!');
                            return true;
                        }
                        var files = parsleyInstance.$element[0].files;
                        return files.length != 1  || files[0].size <= maxSize * 1024;
                    },
                    requirementType: 'integer',
                    messages: {
                        en: 'This file should not be larger than %s Kb',
                        id: 'Berkas tidak dapat lebih dari %s Kb.',
                    }
                });
            });
        });
    </script>

@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
