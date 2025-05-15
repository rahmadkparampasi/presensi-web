<!DOCTYPE html>
<html lang="en">

<head>
    <title>PRESENSI</title>
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
    
    <meta name="author" content="Badan Perencanaan Pembangunan Penelitian Dan Pengembangan Daerah Kabupaten Parigi Moutong" />
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
                    <img style="width: 18rem;" src="/assets/img/fav.png" alt="" style="margin-left: auto; margin-right: auto;" class="imageTemp">
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
                            <img src="assets/img/logocop.png" width="200" alt="" class="img-fluid mb-0">
                            <p class="mb-5 f-w-400">ABSENSI DIGITAL</p>
                            <br/>
                            <div class="alert alert-success alert-dismissible pr-3 pl-3" role="alert">
                                <strong class="text-center">Registrasi Berhasil Dilakukan<br/><br/></strong>
                                <p class="text-justify">Terima kasih telah mendaftar di aplikasi absensi digital. Kami dengan senang hati mengonfirmasi bahwa proses registrasi Anda telah berhasil. <br/><br/>
                                    
                                Terima kasih sekali lagi atas kerjasama Anda! </p>
                            </div>

                            <a href="{{route('register')}}" class="btn btn-block btn-info text-white mb-4">DAFTAR LAGI</a>
                            <a href="{{url('/')}}" class="btn btn-block btn-info text-white mb-4">MASUK</a>
                            
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


   
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