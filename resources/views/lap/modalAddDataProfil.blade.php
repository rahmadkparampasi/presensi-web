@extends('layouts.modalAll', ['idModalAll' => 'lapAddDataModal', 'sizeModalAll' => '', 'divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' => $urlLoad ?? '', 'dataLoadModalAll'=>'true','urlModalAll'=>route('lap.insertProfil'), 'titleModalAll' => 'TAMBAH LAPORAN '.strtoupper($tipeAltT ?? '')])

@section('contentInputHidden')
    <input type="hidden" id="lap_sisp" name="lap_sisp" value="{{$Sisp->sisp_id ?? ''}}" required>
    <input type="hidden" id="lap_id" name="lap_id" required>
    <input type="hidden" id="sisp_nmSispiHid" name="sisp_nm" value="{{$Sisp->sisp_nm ?? ''}}" required>
@endsection
@section('contentModalBody'.$countModalBody)

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_bln">Bulan Laporan</label>
        <select class="form-control border rounded border-dark" id="lap_bln" name="lap_bln" required >
            <option value="" hidden>Pilih Salah Satu</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_thn">Tahun Laporan</label>
        <select class="form-control border rounded border-dark" id="lap_thn" name="lap_thn" required >
            <option value="" hidden>Pilih Salah Satu</option>
            @for ($i = (int)date("Y")-3; $i < (int)date("Y")+1; $i++)
                <option value="{{$i}}">{{$i}}</option>
            @endfor
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required" id="lap_flClass">
        <label class="control-label" for="lap_fl">Laporan</label>
        <input type="file" required accept="application/pdf" class="form-control" name="lap_fl" id="lap_fl" data-parsley-max-file-size="1000" data-parsley-trigger="change" />
        <small>Berkas tidak dapat melebihi 1000kb</small>
    </div>
    
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group" id="lapAddDataModalFooter">
        <button type="submit" id="lapAddDataModalSubmit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
    

    <script>
        function callModalLap(){
            resetForm('lapAddDataModalForm'); 
            $('#lapAddDataModalForm').attr('action', '{{route('lap.insertProfil')}}'); 
        }
    </script>
@endsection
