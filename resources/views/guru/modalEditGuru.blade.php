@extends('layouts.modalAll', ['idModalAll' => 'modalEditGuru', 'sizeModalAll' => '', 'divLoadModalAll' => 'guruDetailGuru', 'urlLoadModalAll' =>route('sisp.detailSisp', [$Guru->sisp_id]), 'dataLoadModalAll'=>'true', 'urlModalAll'=>route('sisp.updateGuru'), 'titleModalAll' => 'UBAH DATA Pegawai'])

@section('contentInputHidden')
    <input type="hidden" id="sisp_idGuru" name="sisp_id" value="{{$Guru->sisp_id}}" />
@endsection
@section('contentModalBody'.$countModalBody)
   
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_kntrk">Nomor Kontrak</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_kntrk" name="sisp_kntrk"  required value="{{$Guru->sisp_kntrk}}">
    </div>
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_tglkntrk">Tanggal Kontrak</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tglkntrk" name="sisp_tglkntrk"  required value="{{$Guru->sisp_tglkntrk}}">
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_nm">Nama Lengkap</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nm" name="sisp_nm"  required value="{{$Guru->sisp_nm}}">
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label for="sisp_satker">Satuan Kerja</label>
        <select class="form-control border rounded border-dark" id="sisp_satker" name="sisp_satker" required onchange="ambilDataSelect('sisp_bag', '{{url('bag/getDataJsonKelas')}}/', 'Pilih Salah Satu', toRemove=['sisp_bag'], removeMessage=['Pilih Salah Satu'], 'sisp_satker')">
            <option value="" hidden>Pilih Salah Satu</option>
            @foreach ($SatkerC as $tk)
                <option {{$Guru->sisp_satker == $tk['bag_id'] ? 'selected' : ''}} value="{{$tk['bag_id']}}">{{$tk['bag_nm']}}</option>
            @endforeach
        </select>
        @error('registerError')
            <div class="invalid-feedback">{{$message}}</div>
        @enderror
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label for="sisp_bag">PPK</label>
        <select class="form-control border rounded border-dark" id="sisp_bag" name="sisp_bag" required>
            <option value="" hidden>Pilih Salah Satu</option>
            @foreach ($Bag as $tk)
                <option {{$Guru->sisp_bag == $tk->bag_id ? 'selected' : ''}} value="{{$tk->bag_id}}">{{$tk->bag_nm}}</option>
            @endforeach
        </select>
        @error('registerError')
            <div class="invalid-feedback">{{$message}}</div>
        @enderror
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_tmptlhr">Tempat Lahir</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tmptlhr" name="sisp_tmptlhr" value="{{$Guru->sisp_tmptlhr}}" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_tgllhr">Tanggal Lahir</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tgllhr" name="sisp_tgllhr" value="{{$Guru->sisp_tgllhr}}" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_jk">Jenis Kelamin</label>
        <select class="form-control border rounded border-dark" id="sisp_jk" name="sisp_jk" required>
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="L" {{ $Guru->sisp_jk == "L" ? 'selected' : '' }}>Laki-Laki</option>
            <option value="P" {{ $Guru->sisp_jk == "P" ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label for="sisp_setpd">Pendidikan Terakhir</label>
        <select class="form-control border rounded border-dark" id="sisp_setpd" name="sisp_setpd" required>
            <option value="" hidden>Pilih Salah Satu</option>
            @foreach ($Setpd as $tk)
                <option {{$Guru->sisp_setpd == $tk->setpd_id ? 'selected' : ''}} value="{{$tk->setpd_id}}">{{$tk->setpd_nm}}</option>
            @endforeach
        </select>
        @error('registerError')
            <div class="invalid-feedback">{{$message}}</div>
        @enderror
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_alt">Alamat</label>
        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_alt" name="sisp_alt"required cols="30" rows="2">{{$Guru->sisp_alt}}</textarea>
        <small>Masukan Alamat Hanya Nama Jalan, Nama Lorong, RTRW Atau Dusun. (Tidak Perlu Menginputkan Nama Desa, Kecamatan Atau Kabupaten Pada Isian Ini)</small>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_telp">Telepon Pegawai</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_telp" name="sisp_telp" value="{{$Guru->sisp_telp}}">
        <small>Jika Tidak Ada Nomor Telepon, Kosongkan Saja</small>
    </div>
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_wa">WA Pegawai</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_wa" name="sisp_wa" value="{{$Guru->sisp_wa}}">
        <small>Jika Tidak Ada Nomor Telepon, Kosongkan Saja</small>
    </div>
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_wak">WA Keluarga</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_wak" name="sisp_wak" value="{{$Guru->sisp_wak}}">
        <small>Jika Tidak Ada Nomor Telepon, Kosongkan Saja</small>
    </div>
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group">
        <button type="submit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
@endsection
