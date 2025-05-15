@extends('layouts.modalAll', ['idModalAll' => 'lapUpdateDataModal', 'sizeModalAll' => '', 'divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' => $urlLoad ?? '', 'dataLoadModalAll'=>'true','urlModalAll'=>route('lap.updateProfil'), 'titleModalAll' => 'PERBAIKAN LAPORAN '.strtoupper($tipeAltT ?? '')])

@section('contentInputHidden'.$countModalBody)
@endsection
@section('contentModalBody'.$countModalBody)
    <input type="hidden" id="lap_id_c" name="lap_id" required>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_bln_c">Bulan Laporan</label>
        <input type="text" disabled required class="form-control" name="lap_bln" id="lap_bln_c"  />
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_thn_c">Tahun Laporan</label>
        <input type="text" disabled required class="form-control" name="lap_thn" id="lap_thn_c"  />
    </div>

    <div class="form-group p-2 mb-0 pb-0 required" id="lap_flClass">
        <label class="control-label" for="lap_fl_c">Laporan</label>
        <input type="file" required accept="application/pdf" class="form-control" name="lap_fl" id="lap_fl_c" data-parsley-max-file-size="1000" data-parsley-trigger="change" />
        <small>Berkas tidak dapat melebihi 1000kb</small>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="lap_c_c">Detail Perbaikan</label>
        <textarea class="form-control" name="lap_c" id="lap_c_c"></textarea>
    </div>
    
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group" id="lapUpdateDataModalFooter">
        <button type="submit" id="lapUpdateDataModalSubmit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
    

    <script>
        function callModalLapChange(){
            resetForm('lapUpdateDataModalForm'); 
            $('#lapUpdateDataModalForm').attr('action', '{{route('lap.updateProfil')}}'); 
        }
    </script>
@endsection
