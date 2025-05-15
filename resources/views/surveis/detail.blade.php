<div class="list-group">
    @foreach ($Surveis as $tk)
        <button data-toggle="modal" data-target="#modalSurvei" onclick="$('#modalSurveiTitle').html('Jawaban Survei Pegawai: {{$tk->sisp_nm}}'); loadSurveiFormA('{{$tk->survei_id}}', '{{$tk->surveis_sisp}}'); $('#modalSurveiForm').attr('action', ''); " class="list-group-item list-group-item-action">{{$tk->survei_thn}}</button>
        
    @endforeach
</div>
