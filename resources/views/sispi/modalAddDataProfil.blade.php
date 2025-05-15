@extends('layouts.modalAll', ['idModalAll' => 'sispiAddDataModal', 'sizeModalAll' => '', 'divLoadModalAll' => $IdForm.'data', 'urlLoadModalAll' => $urlLoad ?? '', 'dataLoadModalAll'=>'true','urlModalAll'=>route('sispi.insertProfil'), 'titleModalAll' => 'TAMBAH IZIN '.strtoupper($tipeAltT ?? '')])

@section('contentInputHidden')
    <input type="hidden" id="sispi_sisp" name="sispi_sisp" value="{{$sispi_sisp ?? ''}}" required>
    <input type="hidden" id="sispi_id" name="sispi_id" required>
    <input type="hidden" id="sisp_nmSispiHid" name="sisp_nm" value="{{$Sisp->sisp_nm ?? ''}}" required>
@endsection
@section('contentModalBody'.$countModalBody)
    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_idspSispi">NIS/NISN/NIP</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_idspSispi" name="sisp_idsp" required value="{{$Sisp->sisp_idsp ?? ''}}" disabled>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sisp_nmSispi">Nama Lengkap</label>
        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nmSispi" name="sisp_nmSispi" required value="{{$Sisp->sisp_nm ?? ''}}" disabled>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sispi_tglm">Tanggal Mulai Izin</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_tglm" name="sispi_tglm" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required d-none" id="sispi_tglmsClass">
        <label class="control-label" for="sispi_tglms">Tanggal Mulai Izin Disetujui</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_tglms" name="sispi_tglms" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sispi_tgls">Tanggal Akhir Izin</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_tgls" name="sispi_tgls" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required d-none" id="sispi_tglssClass">
        <label class="control-label" for="sispi_tglss">Tanggal Akhir Izin Disetujui</label>
        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_tglss" name="sispi_tglss" required>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required">
        <label class="control-label" for="sispi_setkati">Kategori Izin</label>
        <select class="form-control border rounded border-dark" id="sispi_setkati" name="sispi_setkati" required >
            <option value="" hidden>Pilih Salah Satu</option>
            @foreach ($Setkati as $tk)
                <option value="{{$tk['setkati_id']}}">{{$tk['setkati_nm']}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group p-2 mb-0 pb-0">
        <label class="control-label" for="sispi_ket">Keterangan Tambahan</label>
        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_ket" name="sispi_ket" cols="30" rows="2"></textarea>
    </div>

    <div class="form-group p-2 mb-0 pb-0 d-none" id="sispi_ketstjClass">
        <label class="control-label" for="sispi_ketstj">Keterangan Administrator</label>
        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispi_ketstj" name="sispi_ketstj" cols="30" rows="2"></textarea>
    </div>

    <div class="form-group p-2 mb-0 pb-0 required" id="sispi_flClass">
        <label class="control-label" for="sispi_fl">Surat Izin</label>
        <input type="file" required accept="image/png, image/jpeg, image/jpg, application/pdf" class="form-control" name="sispi_fl" id="sispi_fl" data-parsley-max-file-size="500" data-parsley-trigger="change" />
        <small>Berkas tidak dapat melebihi 500kb</small>
    </div>
    
@endsection
@section('contentModalFooter'.$countModalFooter)
    <div class="item form-group" id="sispiAddDataModalFooter">
        <button type="submit" id="sispiAddDataModalSubmit" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
    <div class="item form-group d-none" id="sispiAddDataModalFooterRef">
        <button type="button" onclick="callModalStjSipsi()" class="btn btn-success">SETUJUI</button>
        <button type="button" onclick="callModalTlkSipsi()" class="btn btn-warning">TOLAK</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>
    <div class="item form-group d-none" id="sispiAddDataModalFooterStj">
        <button type="button" onclick="callModalRefSispi($('#sispi_id').val())" class="btn btn-warning">KEMBALI</button>
        <button type="button" id="sispiAddDataModalStj" class="btn btn-primary">SIMPAN</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">BATAL</button>
    </div>

    <script>
        function callModalStjSipsi(){
            $.ajax({
                url: '{{route('sispi.ajax')}}'+'/'+$('#sispi_id').val(),
                success: function(data1) {
                    $('#sispiAddDataModalForm').attr('action', '{{route('sispi.updateStj')}}'); 
    
                    $('#sispi_id').val(data1.sispi_id);
                    $('#sisp_idspSispi').val(data1.sisp_idsp);
                    $('#sisp_nmSispi').val(data1.sisp_nm);
                    $('#sispi_tglm').val(data1.sispi_tglm);
                    if (data1.sispi_tglms=="0000-00-00") {
                        $('#sispi_tglms').val(data1.sispi_tglm);
                    }else{
                        $('#sispi_tglms').val(data1.sispi_tglms);
                    }
                    $('#sispi_tgls').val(data1.sispi_tgls);
                    if (data1.sispi_tglss=="0000-00-00") {
                        $('#sispi_tglss').val(data1.sispi_tgls);
                    }else{
                        $('#sispi_tglss').val(data1.sispi_tglss);
                    }
                    $('#sispi_setkati').val(data1.sispi_setkati);
                    $('#sispi_ket').val(data1.sispi_ket);
                    $('#sispi_tglmsClass').removeClass('d-none');
                    $('#sispi_tglssClass').removeClass('d-none');
                    $('#sispi_ketstjClass').removeClass('d-none');
    
                    $('#sispiAddDataModalFooterStj').removeClass('d-none');
                    $('#sispiAddDataModalFooterRef').addClass('d-none');
            
                    $("#sispi_tglms").attr("required", "required");
                    $("#sispi_tglss").attr("required", "required");
    
                    $('#sispiAddDataModalSubmit').attr('type', 'button'); 
                    $('#sispiAddDataModalStj').attr('type', 'submit'); 
    
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        }
        function callModalTlkSipsi(){
            $.ajax({
                url: '{{route('sispi.ajax')}}'+'/'+$('#sispi_id').val(),
                success: function(data1) {
                    $('#sispiAddDataModalForm').attr('action', '{{route('sispi.updateTlk')}}'); 
    
                    $('#sispi_id').val(data1.sispi_id);
                    $('#sisp_idspSispi').val(data1.sisp_idsp);
                    $('#sisp_nmSispi').val(data1.sisp_nm);
                    $('#sispi_tglm').val(data1.sispi_tglm);
                    $('#sispi_tglms').val(data1.sispi_tglm);
                    $('#sispi_tgls').val(data1.sispi_tgls);
                    $('#sispi_tglss').val(data1.sispi_tgls);
                    $('#sispi_setkati').val(data1.sispi_setkati);
                    $('#sispi_ket').val(data1.sispi_ket);
                    $('#sispi_tglmsClass').removeClass('d-none');
                    $('#sispi_tglssClass').removeClass('d-none');
                    $('#sispi_ketstjClass').removeClass('d-none');
    
                    $('#sispiAddDataModalFooterStj').removeClass('d-none');
                    $('#sispiAddDataModalFooterRef').addClass('d-none');
            
                    $('#sispi_tglmsClass').addClass('d-none');
                    $('#sispi_tglssClass').addClass('d-none');
                    $('#sispi_ketstjClass').removeClass('d-none');
    
                    $("#sispi_tglms").removeAttr("required");
                    $("#sispi_tglss").removeAttr("required");
    
                    $('#sispiAddDataModalSubmit').attr('type', 'button'); 
                    $('#sispiAddDataModalStj').attr('type', 'submit'); 
    
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        }
        function callModalRefSispi(sispi_id){
            // console.log(sispi_id);
            $('#sispiAddDataModal').modal('show');
            $.ajax({
                url: '{{route('sispi.ajax')}}'+'/'+sispi_id,
                success: function(data1) {
                    
                    $('#sispi_id').val(data1.sispi_id);
                    $('#sisp_idspSispi').val(data1.sisp_idsp);
                    $('#sisp_nmSispi').val(data1.sisp_nm);
                    $('#sispi_tglm').val(data1.sispi_tglm);
                    $('#sispi_tgls').val(data1.sispi_tgls);
                    $('#sispi_setkati').val(data1.sispi_setkati);
                    $('#sispi_ket').val(data1.sispi_ket);
                    $('#sispiAddDataModalFooter').addClass('d-none');
                    $('#sispi_flClass').addClass('d-none');
                    
                    $('#sispi_fl').removeAttr('required');
                    
                    
                    $('#sispiAddDataModalFooterRef').removeClass('d-none');
                    $('#sispiAddDataModalFooterStj').addClass('d-none');
    
                    $("#sispi_tglm").attr("disabled", "disabled");
                    $("#sispi_tgls").attr("disabled", "disabled");
                    $("#sispi_setkati").attr("disabled", "disabled");
                    $("#sispi_ket").attr("disabled", "disabled");
    
                    $('#sispi_tglmsClass').addClass('d-none');
                    $('#sispi_tglssClass').addClass('d-none');
                    $('#sispi_ketstjClass').addClass('d-none');
    
                    $("#sispi_tglms").removeAttr("required");
                    $("#sispi_tglss").removeAttr("required");
    
                    // $('#sispi_ket').val(data1.sispi_ket);
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        }
        function callModalSispi(){
            resetForm('sispiAddDataModalForm'); 
            $('#sispiAddDataModalForm').attr('action', '{{route('sispi.insertProfil')}}'); 
            $('#sispiAddDataModalSubmit').attr('type', 'submit'); 
            $('#sispiAddDataModalStj').attr('type', 'button'); 
            $('#sispiAddDataModalFooter').removeClass('d-none'); 
            $('#sispi_flClass').removeClass('d-none'); 
            $("#sispi_fl").attr("required", "required");
    
            $('#sispiAddDataModalFooterRef').addClass('d-none');
            $('#sispiAddDataModalFooterStj').addClass('d-none');
    
            $('#sispi_tglm').removeAttr('disabled');
            $('#sispi_tgls').removeAttr('disabled');
            $('#sispi_setkati').removeAttr('disabled');
            $('#sispi_ket').removeAttr('disabled');
    
            $('#sispi_tglmsClass').addClass('d-none');
            $('#sispi_tglssClass').addClass('d-none');
            $('#sispi_ketstjClass').addClass('d-none');
    
            $("#sispi_tglms").removeAttr("required");
            $("#sispi_tglss").removeAttr("required");
        }
    </script>
@endsection
