<script type="text/javascript">
    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
            textbox.addEventListener(event, function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        });
    }

    // setInputFilter(document.querySelectorAll("justNumber"), function(value) {
    //     return /^-?\d*$/.test(value); 
    // });
    function readURL(input, img) {
        var url = input.value;
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById(img).setAttribute('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById(img).setAttribute('src', '/assets/no_preview.png');
        }
    }

    function showModalImage(input, img, titleModalPreview, titleModalResults, imageSource) {
        if (imageSource != "") {
            var input_src = imageSource;
        } else {
            var input_src = document.getElementById(input).src;
        }

        document.getElementById(img).setAttribute('src', input_src);
        var titleModalPreview = document.getElementById(titleModalPreview);
        titleModalPreview.innerText = titleModalResults;
    }

    function callData(idInput, valInput) {
        // console.log("Bisa");
        var a = Object.keys(idInput).length;
        for (j = a - 1; j >= 0; j--) {
            // console.log(document.getElementById(Object.keys(idInput)[j]));
            // console.log(valInput[j]);
            if (document.getElementById(Object.keys(idInput)[j]).tagName == "SELECT") {
                if (document.getElementById(Object.keys(idInput)[j]).options.length > 1) {
                    document.getElementById(Object.keys(idInput)[j]).value = valInput[j];
                }
            } else if (document.getElementById(Object.keys(idInput)[j]).tagName == "IMG") {
                document.getElementById(Object.keys(idInput)[j]).setAttribute('src', valInput[j]);
            } else if (document.getElementsByName(Object.keys(idInput)[j])[0].type == "radio") {
                var lengthRadio = document.getElementsByName(Object.keys(idInput)[j]).length;
                for (k = lengthRadio - 1; k >= 0; k--) {
                    // console.log(document.getElementsByName(Object.keys(idInput)[j])[k].value);
                    if (document.getElementsByName(Object.keys(idInput)[j])[k].value == valInput[j]) {
                        document.getElementsByName(Object.keys(idInput)[j])[k].checked = true;
                    }
                }
                // console.log("Radio " + Object.keys(idInput)[j]);
            } else {
                var typeDoc = document.getElementById(Object.keys(idInput)[j]).type;
                if (typeDoc == "text" || typeDoc == "date" || typeDoc == "textarea" || typeDoc == "number" || typeDoc ==
                    "hidden") {
                    document.getElementById(Object.keys(idInput)[j]).value = valInput[j];
                } else {
                    document.getElementById(Object.keys(idInput)[j]).innerHTML = valInput[j];
                }
            }
            // console.log(document.getElementById(Object.keys(idInput)[j]).tagName);
        }
    }

    function ambilDataSelect(idSelect, link, messageHidden, toRemove, removeMessage, extraParam = '', selectOne = '') {
        let idSelectInput = document.getElementById(idSelect);
        let idSelectInputExtra = "";
        if (extraParam != '') {
            idSelectInputExtra = document.getElementById(extraParam).value;
        } else {
            idSelectInputExtra = "";
        }
        link += idSelectInputExtra;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', link, true);
        xhr.onload = function() {
            if (this.status == 200) {
                let daftar = JSON.parse(xhr.responseText);
                let hasil = "";
                var length = idSelectInput.options.length;
                for (i = length - 1; i >= 0; i--) {
                    idSelectInput.options[i] = null;
                }
                var lengthRemove = toRemove.length;

                for (j = lengthRemove - 1; j >= 0; j--) {
                    let idSelectInputRemove = document.getElementById(toRemove[j]);
                    var lengthOpt = idSelectInputRemove.options.length;
                    for (k = lengthOpt - 1; k >= 0; k--) {
                        idSelectInputRemove.options[k] = null;
                    }
                    var optHide = document.createElement('option');
                    optHide.innerHTML = removeMessage[j];
                    optHide.value = '';
                    optHide.setAttribute('hidden', 'hidden');
                    idSelectInputRemove.appendChild(optHide);
                }
                var optHide = document.createElement('option');
                optHide.innerHTML = messageHidden;
                optHide.value = '';
                optHide.setAttribute('hidden', 'hidden');
                idSelectInput.appendChild(optHide);
                daftar.forEach(function(data) {
                    var opt = document.createElement('option');
                    opt.appendChild(document.createTextNode(`${data.optText}`));
                    opt.value = `${data.optValue}`;
                    idSelectInput.appendChild(opt);
                    if (`${data.optValue}` == selectOne) {
                        opt.setAttribute('selected', 'selected');
                    }
                });
            }
        }
        xhr.send();
    }



    /*fungsi callOther
    Parameter Yang Dibutuhkan :
    - pesan : Pesan yang akan ditampilkan sebelum mengeksekusi link
    - link : Sebagai link yang akan dituju untuk merealisasikan fungsi yang akan dijalankan, hasil dari link tersebut adalah JSON

    Plugin Yang Dibutuhkan :
    - Sweetalert 2
    */
    function callOther(pesan, link, locationend = '') {
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00a92c',
            cancelButtonColor: '#ff4242',
            confirmButtonText: 'Setuju',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        let data = JSON.parse(xhr.responseText);
                        hideAnimated();
                        swal.fire({
                            title: 'Terima Kasih',
                            text: data.response.message,
                            icon: data.response.response
                        }).then(function() {
                            if (locationend == '') {
                                location.reload();
                            } else {
                                window.location.href = locationend;
                            }

                        });
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }

    function callOtherWOP(link, locationend = '') {
        showAnimated();
        var xhr = new XMLHttpRequest();
        xhr.open('GET', link, true);
        xhr.onload = function() {
            if (this.status == 200) {
                let data = JSON.parse(xhr.responseText);
                hideAnimated();
                swal.fire({
                    title: 'Terima Kasih',
                    text: data.response.message,
                    icon: data.response.response
                }).then(function() {
                    if (locationend == '') {
                        location.reload();
                    } else {
                        window.location.href = locationend;
                    }

                });
                
            } else {
                hideAnimated();
                let data = JSON.parse(xhr.responseText);
                swal.fire({
                    title: 'Tidak Dapat Melanjutkan Proses',
                    // text : 'Request failed.  Returned status of ' + xhr.status,
                    text: data.response.message,
                    icon: data.response.response
                });
            }
        }
        xhr.send();
    }

    function callOtherWOPNR(link) {
        showAnimated();
        var xhr = new XMLHttpRequest();
        xhr.open('GET', link, true);
        xhr.onload = function() {
            if (this.status == 200) {
                let data = JSON.parse(xhr.responseText);
                hideAnimated();
                swal.fire({
                    title: 'Terima Kasih',
                    text: data.response.message,
                    icon: data.response.response
                });
                
            } else {
                hideAnimated();
                let data = JSON.parse(xhr.responseText);
                swal.fire({
                    title: 'Tidak Dapat Melanjutkan Proses',
                    // text : 'Request failed.  Returned status of ' + xhr.status,
                    text: data.response.message,
                    icon: data.response.response
                });
            }
        }
        xhr.send();
    }

    function callOtherWF(pesan, link, func, locationend = '', vari = '') {
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00a92c',
            cancelButtonColor: '#ff4242',
            confirmButtonText: 'Setuju',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        let data = JSON.parse(xhr.responseText);
                        hideAnimated();
                        swal.fire({
                            title: 'Terima Kasih',
                            text: data.response.message,
                            icon: data.response.response
                        }).then(function() {
                            if (locationend == '') {
                                // location.reload();
                                func(vari)
                            } else {
                                window.location.href = locationend;
                            }
                        });
                        
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }

    function callOtherTWF(pesan, link, func, vari = '', btn1 = '', cBtn1 = '') {
        if (btn1=='') {
            btn1 = 'Setuju';
        }
        
        if (cBtn1=='') {
            cBtn1 = '#00a92c';
        }
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: cBtn1,
            cancelButtonColor: '#ff4242',
            confirmButtonText: btn1,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        let data = JSON.parse(xhr.responseText);
                        if (data.response.response == 'success') {
                            hideAnimated();
                            showToast(data.response.message, 'success');
                            func(vari)
                        } else {
                            hideAnimated();
                            swal.fire({
                                title: 'Tidak Dapat Melanjutkan Proses',
                                text: data.response.message,
                                icon: data.response.response
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }
    function callOtherTWFC(pesan, link, link2, func, vari = '', btn1 = '', cBtn1 = '', btn2 = '', cBtn2 = '') {
        if (btn1=='') {
            btn1 = 'Setuju';
        }
        if (btn2=='') {
            btn2 = 'Tidak Setuju';
        }
        if (cBtn1=='') {
            cBtn1 = '#00a92c';
        }
        if (cBtn2=='') {
            cBtn2 = '#0094ff';
        }
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: cBtn1,
            denyButtonColor: cBtn2,
            cancelButtonColor: '#ff4242',
            confirmButtonText: btn1,
            denyButtonText: btn2,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        let data = JSON.parse(xhr.responseText);
                        if (data.response.response == 'success') {
                            hideAnimated();
                            showToast(data.response.message, 'success');
                            func(vari)
                        } else {
                            hideAnimated();
                            swal.fire({
                                title: 'Tidak Dapat Melanjutkan Proses',
                                text: data.response.message,
                                icon: data.response.response
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }else if(result.isDenied){
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link2, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        let data = JSON.parse(xhr.responseText);
                        if (data.response.response == 'success') {
                            hideAnimated();
                            showToast(data.response.message, 'success');
                            func(vari)
                        } else {
                            hideAnimated();
                            swal.fire({
                                title: 'Tidak Dapat Melanjutkan Proses',
                                text: data.response.message,
                                icon: data.response.response
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }

    function callOtherTWLoad(pesan, link, vari = '', idForm = '', idData = '', idCard = '', type = 'form', btn1 = '', cBtn1 = '') {
        if (btn1=='') {
            btn1 = 'Setuju';
        }
        
        if (cBtn1=='') {
            cBtn1 = '#00a92c';
        }
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: cBtn1,
            cancelButtonColor: '#ff4242',
            confirmButtonText: btn1,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        hideAnimated();

                        let data = JSON.parse(xhr.responseText);
                        if (idForm!='') {
                            var myIdForm = $('#'+idForm);
    
                            if(typeof myIdForm.attr('data-load')!=='undefined'){
                                if (myIdForm.attr('data-load')==='true') {
                                    $.ajax({
                                        url:vari,
                                        success: function(data1) {
                                            $('#'+idData).html(data1);
                                            if (type=='form') {
                                                if (idCard!='') {
                                                    closeForm(idCard, idForm)
                                                }
                                            }else{
                                                if (idCard!='') {
                                                    $('#'+idCard).modal('hide');
                                                    $('body').removeClass('modal-open');
                                                    $('.modal-backdrop').remove();
                                                }
                                            }
                                            showToast(data.response.message, 'success');
                                        },
                                        error:function(xhr) {
                                            window.location.reload();
                                        }
                                    });
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                }
                            }else{
                                swal.fire({
                                title: "Terima Kasih",
                                text: data.response.message,
                                icon: data.response.response
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        }else{
                            $.ajax({
                                url:vari,
                                success: function(data1) {
                                    $('#'+idData).html(data1);
                                    if (type=='form') {
                                        if (idCard!='') {
                                            closeForm(idCard, idForm)
                                        }
                                    }else{
                                        if (idCard!='') {
                                            $('#'+idCard).modal('hide');
                                            $('body').removeClass('modal-open');
                                            $('.modal-backdrop').remove();
                                        }
                                    }
                                    showToast(data.response.message, 'success');
                                },
                                error:function(xhr) {
                                    window.location.reload();
                                }
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }
    
    function callOtherTWCLoad(pesan, link, link2, btn1 ='', btn2 ='',vari = '', idForm = '', idData = '', idCard = '', type = 'form', cBtn1 = '', cBtn2 = '') {
        if (btn1=='') {
            btn1 = 'Setuju';
        }
        if (btn2=='') {
            btn2 = 'Tidak Setuju';
        }
        if (cBtn1=='') {
            cBtn1 = '#00a92c';
        }
        if (cBtn2=='') {
            cBtn2 = '#0094ff';
        }
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: cBtn1,
            denyButtonColor: cBtn2,
            cancelButtonColor: '#ff4242',
            confirmButtonText: btn1,
            denyButtonText: btn2,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        hideAnimated();

                        let data = JSON.parse(xhr.responseText);
                        if (idForm!='') {
                            var myIdForm = $('#'+idForm);
    
                            if(typeof myIdForm.attr('data-load')!=='undefined'){
                                if (myIdForm.attr('data-load')==='true') {
                                    $.ajax({
                                        url:vari,
                                        success: function(data1) {
                                            $('#'+idData).html(data1);
                                            if (type=='form') {
                                                if (idCard!='') {
                                                    closeForm(idCard, idForm)
                                                }
                                            }else{
                                                if (idCard!='') {
                                                    $('#'+idCard).modal('hide');
                                                    $('body').removeClass('modal-open');
                                                    $('.modal-backdrop').remove();
                                                }
                                            }
                                            showToast(data.response.message, 'success');
                                        },
                                        error:function(xhr) {
                                            window.location.reload();
                                        }
                                    });
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                }
                            }else{
                                swal.fire({
                                title: "Terima Kasih",
                                text: data.response.message,
                                icon: data.response.response
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        }else{
                            $.ajax({
                                url:vari,
                                success: function(data1) {
                                    $('#'+idData).html(data1);
                                    if (type=='form') {
                                        if (idCard!='') {
                                            closeForm(idCard, idForm)
                                        }
                                    }else{
                                        if (idCard!='') {
                                            $('#'+idCard).modal('hide');
                                            $('body').removeClass('modal-open');
                                            $('.modal-backdrop').remove();
                                        }
                                    }
                                    showToast(data.response.message, 'success');
                                },
                                error:function(xhr) {
                                    window.location.reload();
                                }
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }else if(result.isDenied){
                showAnimated();
                var xhr = new XMLHttpRequest();
                xhr.open('GET', link2, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        hideAnimated();

                        let data = JSON.parse(xhr.responseText);
                        if (idForm!='') {
                            var myIdForm = $('#'+idForm);
    
                            if(typeof myIdForm.attr('data-load')!=='undefined'){
                                if (myIdForm.attr('data-load')==='true') {
                                    $.ajax({
                                        url:vari,
                                        success: function(data1) {
                                            $('#'+idData).html(data1);
                                            if (type=='form') {
                                                if (idCard!='') {
                                                    closeForm(idCard, idForm)
                                                }
                                            }else{
                                                if (idCard!='') {
                                                    $('#'+idCard).modal('hide');
                                                    $('body').removeClass('modal-open');
                                                    $('.modal-backdrop').remove();
                                                }
                                            }
                                            showToast(data.response.message, 'success');
                                        },
                                        error:function(xhr) {
                                            window.location.reload();
                                        }
                                    });
                                }else{
                                    swal.fire({
                                    title: "Terima Kasih",
                                    text: data.response.message,
                                    icon: data.response.response
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                }
                            }else{
                                swal.fire({
                                title: "Terima Kasih",
                                text: data.response.message,
                                icon: data.response.response
                                }).then(function() {
                                    window.location.reload();
                                });
                            }
                        }else{
                            $.ajax({
                                url:vari,
                                success: function(data1) {
                                    $('#'+idData).html(data1);
                                    if (type=='form') {
                                        if (idCard!='') {
                                            closeForm(idCard, idForm)
                                        }
                                    }else{
                                        if (idCard!='') {
                                            $('#'+idCard).modal('hide');
                                            $('body').removeClass('modal-open');
                                            $('.modal-backdrop').remove();
                                        }
                                    }
                                    showToast(data.response.message, 'success');
                                },
                                error:function(xhr) {
                                    window.location.reload();
                                }
                            });
                        }
                    } else {
                        hideAnimated();
                        let data = JSON.parse(xhr.responseText);
                        swal.fire({
                            title: 'Tidak Dapat Melanjutkan Proses',
                            // text : 'Request failed.  Returned status of ' + xhr.status,
                            text: data.response.message,
                            icon: data.response.response
                        });
                    }
                }
                xhr.send();
            }
        });
    }

    function callOtherTNCWF(link, func, locationend = '', vari = '') {
        showAnimated();
        var xhr = new XMLHttpRequest();
        xhr.open('GET', link, true);
        xhr.onload = function() {
            if (this.status == 200) {
                let data = JSON.parse(xhr.responseText);
                if (data.response == 'success') {
                    hideAnimated();
                    showToast(data.response.message, 'success');
                    func(vari)
                } else {
                    hideAnimated();
                    swal.fire({
                        title: 'Tidak Dapat Melanjutkan Proses',
                        text: data.response.message,
                        icon: data.response.response
                    });
                }
            } else {
                hideAnimated();
                let data = JSON.parse(xhr.responseText);
                swal.fire({
                    title: 'Tidak Dapat Melanjutkan Proses',
                    // text : 'Request failed.  Returned status of ' + xhr.status,
                    text: data.response.message,
                    icon: data.response.response
                });
            }
        }
        xhr.send();
    }

    function callHrefWC(pesan, link) {
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00a92c',
            cancelButtonColor: '#ff4242',
            confirmButtonText: 'Setuju',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                window.location.href = link;
                
            }
        });
    }

    function callHref(link) {
        window.location.href = link;
    }

    function callBlank(link) {
        window.open(link, '_blank');
    }

    function cBCW(link) {
        window.close();
        window.open(link, '_blank');
    }


    function ambilDataText(idText, link, toRemove, extraParam = '') {
        let idTextInput = document.getElementById(idText);
        let idSelectInputExtra = "";
        if (extraParam != '') {
            idSelectInputExtra = document.getElementById(extraParam).value;
        } else {
            idSelectInputExtra = "";
        }
        link += idSelectInputExtra;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', link, true);
        xhr.onload = function() {
            if (this.status == 200) {
                let daftar = JSON.parse(xhr.responseText);
                let hasil = "";
                idTextInput.value = "";

                var lengthRemove = toRemove.length;

                for (j = lengthRemove - 1; j >= 0; j--) {
                    document.getElementById(toRemove[j]).value = "";
                }

                idTextInput.value = daftar.optValue;
            }
        }
        xhr.send();
    }

    function removeQuote(idText) {
        let idTextInput = document.getElementById(idText);
        let Text = idTextInput.value;
        var text = Text.replace(/['"]+/g, '');
        idTextInput.value = text;
    }

    function callOtherBlank(pesan, link) {
        Swal.fire({
            title: 'Apakah Anda Menyutui?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00a92c',
            cancelButtonColor: '#ff4242',
            confirmButtonText: 'Setuju',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                window.open(link, '_blank');
            }
        });
    }

    function showForm(idForm, sD = "") {
        let idFormShow = document.getElementById(idForm);
        if (sD == '') {
            idFormShow.style.display = "flex";
        } else {
            idFormShow.style.display = sD;
        }

        window.scrollTo(0, 0);
    }

    function showFormNS(idForm, sD = "") {
        let idFormShow = document.getElementById(idForm);
        if (sD == '') {
            idFormShow.style.display = "flex";
        } else {
            idFormShow.style.display = sD;
        }
    }

    function cActForm(form, act = ''){
        if (act != '') {
            document.getElementById(form).setAttribute('action', act)
        }
    }

    function cAttrElement(element = '', attr = '', val = '') {
        if (attr != ''&& element != ''&& val != '') {
            document.getElementById(element).setAttribute(attr, val)
        }
    }

    function closeForm(idForm, form, act = '') {
        let idFormShow = document.getElementById(idForm);
        idFormShow.style.display = "none";
        document.getElementById(form).reset();
        if (act != '') {
            document.getElementById(form).setAttribute('action', act)
        }
    }

    function closeFormNR(idForm) {
        let idFormShow = document.getElementById(idForm);
        idFormShow.style.display = "none";
    }

    function edAttr(bDisabled) {
        var inputs = document.getElementsByTagName("input");
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].disabled = bDisabled;
        }
        var selects = document.getElementsByTagName("select");
        for (var i = 0; i < selects.length; i++) {
            selects[i].disabled = bDisabled;
        }
        var textareas = document.getElementsByTagName("textarea");
        for (var i = 0; i < textareas.length; i++) {
            textareas[i].disabled = bDisabled;
        }
    }

    function resetForm(form) {
        document.getElementById(form).reset();
    }

    function resetFormAct(form, fClass){
        var myform = $("#"+form+" input."+fClass);
        for (let i = 0; i < myform.length; i++) {
            if (myform[i].type=="hidden") {
                continue;
            }
            if (myform[i].readOnly==true) {
                continue;
            }
            myform[i].value = '';
        }
    }

    function copyTextTOC(idInput = '') {
        var text = document.getElementById(idInput).innerText;
        var elem = document.createElement("textarea");
        document.body.appendChild(elem);
        elem.value = text;
        elem.select();
        document.execCommand("copy");
        document.body.removeChild(elem);
        showToast('Teks Berhasil Disalin');
    }

    function showToast(text = '', type = 'info') {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        if (type == 'info') {
            toastr.info(text);
        } else if (type == 'success') {
            toastr.success(text);
        } else if (type == 'error') {
            toastr.error(text);
        }
    }



    function addSess(nameSes = '', dataSes = '') {
        sessionStorage.setItem(nameSes, dataSes);
    }

    function addFill(idComp = '', fill = '') {
        document.getElementById(idComp).value = fill;
    }

    var languangeDT = {
        "lengthMenu": "Menampilkan _MENU_ data per halaman",
        "zeroRecords": "Belum Ada Data",
        "info": "Halaman _START_ / _END_ dari _TOTAL_ Tampilan",
        "infoEmpty": "Tidak Ada Halaman",
        "loadingRecords": "Memuat...",
        "processing": "Memproses...",
        "search": "Cari:",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
        },
    }

    function dTD(params = '', l = 10, scroll = false) {
        var atrExp = $(params).attr('data-exp');
        var domExp = 'lfrtip';
        var btnExp = [];
        if (typeof atrExp !== 'undefined' && atrExp !== false) {
            var domExp = 'Bfrtip';
            var btnExp = [{
                    extend: 'copyHtml5',
                    text: 'Salin'
                },
                'excelHtml5',
                'csvHtml5',
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-eye"></i>'
                },
            ];
        }


        $(params).DataTable({
            "order": [
                [0, "asc"]
            ],
            "language": languangeDT,
            pageLength: l,
            responsive: true,

            fixedHeader: false,
            keys: true,
            dom: domExp,
            buttons: btnExp,

            scrollX: scroll,

        });
    }

    function cAF(id = '', action = '') {
        document.getElementById(id).action = action;
    }


    $(document).ready(function() {
        dTD('table.dtK');
        dTD('table.dtK100', 100);
    });

    function setDate() {
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear() + "-" + (month) + "-" + (day);

        var time = now.getHours() + ":" + now.getMinutes();
        $('input[type="date"]').val(today);

        $('.datetimepicker-input').each(function() {
            if ($(this).val() == '') {
                $(this).val(time);

            }
        });
    }

    // $.extend( $.validator.messages, {
    //     required: "Kolom ini diperlukan.",
    //     remote: "Harap benarkan kolom ini.",
    //     email: "Silakan masukkan format email yang benar.",
    //     url: "Silakan masukkan format URL yang benar.",
    //     date: "Silakan masukkan format tanggal yang benar.",
    //     dateISO: "Silakan masukkan format tanggal(ISO) yang benar.",
    //     number: "Silakan masukkan angka yang benar.",
    //     digits: "Harap masukan angka saja.",
    //     creditcard: "Harap masukkan format kartu kredit yang benar.",
    //     equalTo: "Harap masukkan nilai yg sama dengan sebelumnya.",
    //     maxlength: $.validator.format( "Input dibatasi hanya {0} karakter." ),
    //     minlength: $.validator.format( "Input tidak kurang dari {0} karakter." ),
    //     rangelength: $.validator.format( "Panjang karakter yg diizinkan antara {0} dan {1} karakter." ),
    //     range: $.validator.format( "Harap masukkan nilai antara {0} dan {1}." ),
    //     max: $.validator.format( "Harap masukkan nilai lebih kecil atau sama dengan {0}." ),
    //     min: $.validator.format( "Harap masukkan nilai lebih besar atau sama dengan {0}." )
    // } );

    var toolBarQuil = [
        ['bold', 'italic', 'underline', 'strike'],

        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }],
        [{
            'align': []
        }],

    ];

    function showToastBS(m, t) {
        $('.toast').addClass('bg-'+t);
        $('.toast-body').html(m);
        $('.toast').toast('show');
    }

    function loadDataHtml(myData = []){
        var length = myData['link'].length;
        $.each(myData['link'], function(i){
            $.ajax({
                url:myData['link'][i],
                success: function(data1) {
                    $('#'+myData['div'][i]).html(data1);
                },
                error:function(xhr) {
                    window.location.reload();
                }
            });
        });
    }
</script>
{{-- <div class="toast-container position-absolute p-3 top-0 end-0" id="toastPlacement">
    <div class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
        <div class="d-flex">
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div> --}}