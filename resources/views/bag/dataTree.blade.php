<div class="card">
    <div class="card-header">
        <h5>STRUKTUR BAGIAN</h5>
        {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddRs"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Unit</button> --}}
    </div>
    <div class="card-body">
        <div class="treeview-animated" style="font-size: 13px;" id="cardDir"></div>
    </div>
</div>
<script>
    var nSBag;
    $(document).ready(function() {
        setTimeout(() => {
            nSBag = sessionStorage.getItem("nSBag");
            fill_treeview();
        }, 3000);
    });
    function loadTabButton(id) {
        $.ajax({
            url: "/bag/getDataButton/" + id,
            success: function(data) {
                $('#cardButton').html(data);
            }
        });
    }
    function loadTabForm(id) {
        $.ajax({
            url: "/bag/getDataForm/" + id,
            success: function(data) {
                $('#cardForm').html(data);
            }
        });
    }
    function loadTabFormUser(id) {
        $.ajax({
            url: "/bag/getDataFormUser/" + id,
            success: function(data) {
                $('#tabUsersBag').html(data);
            }
        });
    }
    function loadTabFormPpk(id){
        myUri = "{{route('bagk.getDataPpk', [':id'])}}";
        myUri = myUri.replace(':id', id);
        $.ajax({
            url: myUri,
            success: function(data){
                $('#cardPpk').html(data);
                $('#cardSatker').html('');
            }
        });
    }
    function loadTabFormSatker(id){
        myUri = "{{route('bagk.getDataSatker', [':id'])}}";
        myUri = myUri.replace(':id', id);
        $.ajax({
            url: myUri,
            success: function(data){
                $('#cardSatker').html(data);
                $('#cardPpk').html('');
            }
        });
    }
    function fill_treeview() {
        $.ajax({
            url: "{{ url('bag/getTreeData')}}",
            dataType: "json",
            success: function(data) {
                $('#cardDir').treeview({
                    data: data,
                    levels: 5,
                    showBorder: false,
                    color: "#428bca",
                    showIcon: true,
                    expandIcon: "far fa-folder",
                    collapseIcon: "far fa-folder-open",
                    // // selectedIcon: "fas fa-folder-open",
                    icon: "far fa-folder-open",
                    emptyIcon: "far fa-file",
                    // nodeIcon: "fas fa-folder-open",

                    //showTags: true,
                    highlightSelected: true,
                    onNodeSelected: function(event, data) {
                        var sels = $('#cardDir').treeview('getSelected');
                        addSess('nSBag', sels[0].nodeId);
                        
                        // loadTabButton(sels[0].idEx);
                        // loadTabForm(sels[0].idEx);
                        // console.log(sels);
                        // console.log(sels[0].idEx);

                        if (sels[0].str=="2") {
                            loadTabFormSatker(sels[0].idEx);
                        }else if(sels[0].str=="3"){
                            loadTabFormPpk(sels[0].idEx);
                        }else if(sels[0].str=="1"){
                            $('#cardSatker').html('');
                            $('#cardPpk').html('');
                        }
                        
                        document.getElementById("bag_idC").value = sels[0].idEx;
                        document.getElementById("bag_nmC").value = sels[0].textAlt;

                        document.getElementById("bag_prnt").value = sels[0].idEx;
                        document.getElementById("bag_prntNm").value = sels[0].textAlt;
                        document.getElementById("bag_str").value = sels[0].str;
                        
                    },
                });
                if (nSBag!=null) {
                    nSBag = parseInt(nSBag);
                    $('#cardDir').treeview('selectNode', [nSBag]);
                    $('#cardDir').treeview('expandNode', [nSBag, {
                        levels: 1,
                        silent: true
                    }]);
                    
                }
            }
        });
    }
</script>