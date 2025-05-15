<div class="card-body">
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddBag"><i class="fa fa-plus" aria-hidden="true"></i> Tambah</button>
    <button class="btn btn-warning " data-toggle="modal" data-target="#modalChangeBag"><i class="fa fa-pen" aria-hidden="true"></i> Ubah</button>
    @if (isset($bag_id))
        <button class="btn btn-danger" data-toggle="modal" data-target="#modalAddChange" onclick="callOtherTWF('Menghapus Data Bagian {{$Bag->bag_nm}}', '{{url('bag/delete/'.$bag_id)}}', getfill_treeview)"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>
    @endif
</div>
<script>
    function getfill_treeview(){
        setTimeout(() => {
            nSBag = sessionStorage.getItem("nSBag");
            fill_treeview();
        }, 3000);
    }
</script>