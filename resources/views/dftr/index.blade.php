<!DOCTYPE html>
<html lang="en">

<head>
    <title>PRESENSI | REGISTRASI</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="keywords" content="PRESENSI"/>
    <meta name="description" content="Sistem Absensi Digital"/>
    <meta name="copyright"content="KIRANA">
    <meta name="og:image" content="/logo.png"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    
    <meta name="author" content="KIRANA" />
	<!-- Favicon icon -->
    
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>

	<!-- vendor css -->
    <link rel="stylesheet" href="/vendors/include/css/style.css">
    <link rel="stylesheet" href="/vendors/include/loading.css" media="all">

    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <script src="/vendors/script/jquery/3.1.1/jquery.min.js"></script>
    <style>
        .select2.select2-container {
            width: 100% !important;
        }

        .select2.select2-container .select2-selection {
            border: none;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            
            height: calc(1.5em + 1.25rem + 2px);
            /* margin-bottom: 15px; */
            /* padding: 0.625rem 1.1875rem; */
            outline: none !important;
            transition: all .15s ease-in-out;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            display: block;
            word-wrap: normal;
            text-transform: none;
            font-family: inherit;
            margin: 0;
            text-align: left !important;
            padding-left: 0;
            padding-right: 0;
            border-bottom: 1px solid #ced4da;
        }

        .select2.select2-container .select2-selection .select2-selection__rendered {
            color: #333;
            line-height: 32px;
            padding-left: 0;
        }

        .select2.select2-container .select2-selection .select2-selection__arrow {
            background: none;
            border-left: none;
            height: 32px;
            width: 33px;
        }

        .select2.select2-container.select2-container--open .select2-selection.select2-selection--single {
            background: #f8f8f8;
        }
        .select2.select2-container.select2-container--open .select2-selection.select2-selection--single .select2-selection__arrow {
        -webkit-border-radius: 0 3px 0 0;
        -moz-border-radius: 0 3px 0 0;
        border-radius: 0 3px 0 0;
        }
    </style>
    
</head>

<body>
    <div id="selfLoading" class="hide">
        <div class="imagePos">
            <div class="row">
                <div class="col-lg-12" style="text-align: center;">
                    <img style="width: 18rem;" src="/assets/img/logocop.png" alt="" style="margin-left: auto; margin-right: auto;" class="imageTemp">
                </div>
                <div class="col-lg-12">
                    <p style="color: #fff; font-size: 40px; text-align: center;"><strong>LOADING</strong></p>
                </div>
            </div>
        </div>

    </div>

    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="card">
                <div class="row align-items-center text-center">
                    <div class="col-md-12">
                        <div class="card-body">
                            @php
                                $NowIid = date("Y-m-d H:i:s");
                                $DateNowIid = strtotime($NowIid);
                                $BatasIid = strtotime("2024-09-30 23:59:00");
                                
                                $NewBatasIid = date("Y-m-d H:i:s", $BatasIid);
                    
                                // if ($DateNowIid>$BatasIid) {
                                //     continue;
                                // }
                            @endphp
                            <h3 class="text-center">Siswa Waktu Pendaftaran</h3>
                            <h3 class="text-center text-danger" id="timeCountIid" style="font-size: 40px; display: block;  font-weight: bold;"></h3>
                            <script>
                                // Set the date we're counting down to
                                var countDownDateIid = new Date("{{$NewBatasIid}}").getTime();
                                
                                // Update the count down every 1 second
                                var xIid = setInterval(function() {
                                
                                    var nowIid = new Date().getTime();
                                
                                    var distanceIid = countDownDateIid - nowIid;
                                
                                    var daysIid = Math.floor(distanceIid / (1000 * 60 * 60 * 24));
                                    var hoursIid = Math.floor((distanceIid % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutesIid = Math.floor((distanceIid % (1000 * 60 * 60)) / (1000 * 60));
                                    var secondsIid = Math.floor((distanceIid % (1000 * 60)) / 1000);
                                
                                    document.getElementById("timeCountIid").innerHTML = daysIid + "Hari " + hoursIid + "Jam "+ minutesIid + "Menit " + secondsIid + "Detik ";
                    
                                    if(distanceIid <= 0){
                                        clearInterval(xIid);
                                        document.getElementById("timeCountIid").innerHTML = "WAKTU TELAH HABIS";
                                        $("#hiddenregis").html('');
                                    }
                                }, 1000);
                            </script>
                            <img src="assets/img/logocop.png" width="200" alt="" class="img-fluid mb-0">
                            <p class="mb-5 f-w-400">ABSENSI DIGITAL</p>
                            <div id="hiddenregis">
                                <h4 class="mb-2 f-w-bold">REGISTRASI SISWA</h4>
                                <br/>
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <a href="#" class="close text-dark" data-dismiss="alert" aria-label="close">X</a>

                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li><strong>{{$error}}</strong></li>
                                            @endforeach
                                        </ul>
                                    </div><br />
                                @endif
                                @if (session()->has('registerError'))
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <a href="#" class="close text-dark" data-dismiss="alert" aria-label="close">X</a>
                                        <strong>{{session('registerError')}}</strong>
                                    </div>
                                @endif
                                <form class="form-contact contact_form" action="{{route('register.insert')}}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="form-group mb-3 text-left">
                                        <input type="hidden" id="sisp_setkatpes" name="sisp_setkatpes" value="{{$Setkatpes_ps->setkatpes_id}}" required>

                                        <label for="sisp_idsp">NIS/NISN (Nomor Induk Siswa)</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_idsp" name="sisp_idsp" value="{{old('sisp_idsp')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_nm">Nama Lengkap</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_nm" name="sisp_nm" value="{{old('sisp_nm')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_tmptlhr">Tempat Lahir</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tmptlhr" name="sisp_tmptlhr" value="{{old('sisp_tmptlhr')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_tgllhr">Tanggal Lahir</label>
                                        <input type="date" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_tgllhr" name="sisp_tgllhr" value="{{old('sisp_tgllhr')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_jk">Jenis Kelamin</label>
                                        <select class="form-control border rounded border-dark" id="sisp_jk" name="sisp_jk" value="{{old('sisp_jk')}}" required>
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            <option value="L" {{ old('sisp_jk') == "L" ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="P" {{ old('sisp_jk') == "P" ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_tkt">Tingkat</label>
                                        <select class="form-control border rounded border-dark" id="sisp_tkt" name="sisp_tkt" value="{{old('sisp_tkt')}}" required onchange="ambilDataSelect('sisp_bag', '{{url('bag/getDataJsonKelas')}}/', 'Pilih Salah Satu', toRemove=['sisp_bag'], removeMessage=['Pilih Salah Satu'], 'sisp_tkt')">
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            <option value="10">10 (Sepuluh)</option>
                                            <option value="11">11 (Sebelas)</option>
                                            <option value="12">12 (Dua Belas)</option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_bag">Kelas</label>
                                        <select class="form-control border rounded border-dark" id="sisp_bag" name="sisp_bag" required>
                                            <option value="" hidden>Pilih Salah Satu</option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_thn">Tahun Masuk Sekolah</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_thn" name="sisp_thn" value="{{old('sisp_thn')}}">
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_alt">Alamat</label>
                                        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_alt" name="sisp_alt"required cols="30" rows="2">{{old('sisp_alt')}}</textarea>
                                        <small>Masukan Alamat Hanya Nama Jalan, Nama Lorong, RTRW Atau Dusun. (Tidak Perlu Menginputkan Nama Desa, Kecamatan Atau Kabupaten Pada Isian Ini)</small>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sisp_telp">Telepon Siswa</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sisp_telp" name="sisp_telp" value="{{old('sisp_telp')}}">
                                        <small>Jika Tidak Ada Nomor Telepon, Kosongkan Saja</small>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_tglortu">Tinggal Dengan Orang Tua</label>
                                        <select class="form-control border rounded border-dark" id="sispds_tglortu" name="sispds_tglortu"  required>
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            <option value="1" {{ old('sispds_tglortu') == "1" ? 'selected' : '' }}>Ya</option>
                                            <option value="0" {{ old('sispds_tglortu') == "0" ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_setcks">Cara Ke Sekolah</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_setcks" name="sispds_setcks" value="{{old('sispds_setcks')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Setcks as $tk)
                                                <option value="{{$tk['setcks_id']}}" {{ old('sispds_setcks') == $tk['setcks_id'] ? 'selected' : '' }}>{{$tk['setcks_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_settks">Transportasi Ke Sekolah</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_settks" name="sispds_settks" value="{{old('sispds_settks')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Settks as $tk)
                                                <option value="{{$tk['settks_id']}}" {{ old('sispds_settks') == $tk['settks_id'] ? 'selected' : '' }}>{{$tk['settks_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                

                                    <h6 class="mb-3 mt-5 f-w-bold">ORANG TUA</h6>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_ayah">Nama Ayah</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispds_ayah" name="sispds_ayah" value="{{old('sispds_ayah')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_asetpd">Tingkat Pendidikan Ayah</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_asetpd" name="sispds_asetpd" value="{{old('sispds_asetpd')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Setpd as $tk)
                                                <option value="{{$tk['setpd_id']}}" {{ old('sispds_asetpd') == $tk['setpd_id'] ? 'selected' : '' }}>{{$tk['setpd_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_asetkrj">Pekerjaan Ayah</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_asetkrj" name="sispds_asetkrj" value="{{old('sispds_asetkrj')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Setkrj as $tk)
                                                <option value="{{$tk['setkrj_id']}}" {{ old('sispds_asetkrj') == $tk['setkrj_id'] ? 'selected' : '' }}>{{$tk['setkrj_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_ibu">Nama Ibu</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispds_ibu" name="sispds_ibu" value="{{old('sispds_ibu')}}" required>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_isetpd">Tingkat Pendidikan Ibu</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_isetpd" name="sispds_isetpd" value="{{old('sispds_isetpd')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Setpd as $tk)
                                                <option value="{{$tk['setpd_id']}}" {{ old('sispds_isetpd') == $tk['setpd_id'] ? 'selected' : '' }}>{{$tk['setpd_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_isetkrj">Pekerjaan Ibu</label>
                                        <select type="text" class="form-control border rounded border-dark" id="sispds_isetkrj" name="sispds_isetkrj" value="{{old('sispds_isetkrj')}}" required >
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            @foreach ($Setkrj as $tk)
                                                <option value="{{$tk['setkrj_id']}}" {{ old('sispds_isetkrj') == $tk['setkrj_id'] ? 'selected' : '' }}>{{$tk['setkrj_nm']}}</option>
                                            @endforeach
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_altortu">Alamat Orang Tua</label>
                                        <textarea class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispds_altortu" name="sispds_altortu"required cols="30" rows="2">{{old('sispds_altortu')}}</textarea>
                                        <small>Masukan Alamat Hanya Nama Jalan, Nama Lorong, RTRW Atau Dusun. (Tidak Perlu Menginputkan Nama Desa, Kecamatan Atau Kabupaten Pada Isian Ini)</small>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_telportu">Telepon Orang Tua</label>
                                        <input type="text" class="form-control border rounded border-dark" placeholder="Ketik Disini" id="sispds_telportu" name="sispds_telportu" value="{{old('sispds_telportu')}}">
                                        <small>Jika Tidak Ada Nomor Telepon Orang Tua, Kosongkan Saja</small>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_stsortu">Status Orang Tua</label>
                                        <select class="form-control border rounded border-dark" id="sispds_stsortu" name="sispds_stsortu"  required>
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            <option value="0" {{ old('sispds_stsortu') == "0" ? 'selected' : '' }}>Masih Hidup Semua</option>
                                            <option value="1" {{ old('sispds_stsortu') == "1" ? 'selected' : '' }}>Ayah Hidup Ibu Meninggal</option>
                                            <option value="2" {{ old('sispds_stsortu') == "2" ? 'selected' : '' }}>Ibu Hidup Ayah Meninggal</option>
                                            <option value="3" {{ old('sispds_stsortu') == "3" ? 'selected' : '' }}>Telah Meninggal Semua </option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 text-left">
                                        <label for="sispds_nkhortu">Status Pernikahan Orang Tua</label>
                                        <select class="form-control border rounded border-dark" id="sispds_nkhortu" name="sispds_nkhortu"  required>
                                            <option value="" hidden>Pilih Salah Satu</option>
                                            <option value="0" {{ old('sispds_nkhortu') == "0" ? 'selected' : '' }}>Menikah</option>
                                            <option value="1" {{ old('sispds_nkhortu') == "1" ? 'selected' : '' }}>Cerai Hidup</option>
                                            <option value="2" {{ old('sispds_nkhortu') == "2" ? 'selected' : '' }}>Cerai Mati</option>
                                        </select>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <h6 class="mb-3 mt-5 f-w-bold">FOTO SISWA</h6>
                                    <div class="form-group mb-3 text-left">
                                        <label class="w-100 text-center f-12" >CONTOH FOTO SISWA. FOTO HARUS MENGGUNAKAN LATAR MERAH, MENGGUNAKAN BAJU SMA DAN TIDAK BOLEH FOTO SELFI (BERSAMA TEMAN ATAU SENDIRIAN), HARUS FOTO TEGAK LURUS SEPERTI CONTOH</label>
                                        <label class="w-100 text-center f-12" ><a href="https://www.photoroom.com/tools/change-background-color" target="_blank">JIKA FOTO BELUM BERLATAR MERAH, SILAHKAN TEKAN DISINI UNTUK UBAH LATAR</a></label>
                                        <div class="row">
                                            <div class="col-6 text-center"><img class="m-auto" src="/assets/img/contoh_1.jpg" width="90" alt="Contoh 1"></div>
                                            <div class="col-6 text-center"><img class="m-auto" src="/assets/img/contoh_2.jpg" width="90" alt="Contoh 1"></div>
                                        </div><br/>
                                        <label for="sisp_pic" class="w-100 text-center">UPLOAD FOTO SISWA</label>
                                        <input type="file" class="form-control border rounded border-dark" placeholder="Foto Siswa" id="sisp_pic" name="sisp_pic" value="{{old('sisp_pic')}}" accept="image/png, image/jpg, image/jpeg">
                                        <small class="w-100 text-sm-center">Foto Harus Berformat .jpg/.jpeg/.png, Dan Foto Tidak Boleh Melebihi 500Kb</small>
                                        @error('registerError')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    
                                    <button type="submit" class="btn btn-block btn-primary mb-2">DAFTAR</button>
                                    <p class="mb-1">Sudah punya akun? <a href="{{route('masuk')}}" class="f-w-400">Masuk Disini</a></p><br/>
                                    
                                </form>
                                
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-4"><a target="_blank" href="https://www.instagram.com/kiranatrigemilang/"><img src="/assets/img/instagram.png" width="30" alt="Instagram"></a></div>
                                <div class="col-4"><a target="_blank" href="https://www.youtube.com/@kiranatrigemilang"><img src="/assets/img/youtube.png" width="30" alt="Instagram"></a></div>
                                <div class="col-4"><a target="_blank" href="https://www.facebook.com/kiranatrigemilang"><img src="/assets/img/facebook.png" width="30" alt="Instagram"></a></div>
                            </div>
                            <br/>
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © 2024 - <script>
                                    document.write(new Date().getFullYear())
                                </script>
            
                                <a href="https://kirana.id/" target="_blank" class="font-weight-bold" target="_blank">KIRANA</a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.anotherscript')

    {{-- @include('includes.anotherscript') --}}
    {{-- @include('includes.ajaxinsert') --}}
    <!-- Required Js -->
    <script src="/vendors/script/jquery/3.5.1/jquery.min.js"></script>
    
    <script src="/vendors/include/js/vendor-all.min.js"></script>
    
    <script src="/vendors/include/js/plugins/bootstrap.min.js"></script>

    <!-- select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script src="/vendors/include/js/ripple.js"></script>
    <script src="/vendors/include/js/pcoded.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="/vendors/include/animation.js"></script>
</body>

</html>