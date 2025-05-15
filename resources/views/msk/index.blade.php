<!DOCTYPE html>
<html lang="en">

<head>
    <title>PRESENSI | MASUK</title>
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

    <script src="/vendors/script/jquery/3.1.1/jquery.min.js"></script>
    
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
                            <form class="form-contact contact_form" action="{{route('masuk.auth')}}" method="post" novalidate="novalidate">
                                @csrf
                                <img src="assets/img/logocop.png" width="200" alt="" class="img-fluid mb-0">
                                <p class="mb-5 f-w-400">ABSENSI DIGITAL</p>
                                
                                <h4 class="mb-2 f-w-bold">MASUK</h4>
                                <br/>
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <a href="#" class="close text-dark" data-dismiss="alert" aria-label="close">X</a>

                                        <ul class="list-unstyled text-left">
                                            @foreach ($errors->all() as $error)
                                                <li><strong>{{$error}}</strong></li>
                                            @endforeach
                                        </ul>
                                    </div><br />
                                @endif
                                @if (session()->has('loginError'))
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <a href="#" class="close text-dark" data-dismiss="alert" aria-label="close">X</a>
                                        <strong>{{session('loginError')}}</strong>
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" placeholder="Username" id="username" name="username" value="{{old('username')}}" required>
                                    @error('loginError')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required value="{{old('password')}}">
                                </div>

                                
                                <button type="submit" class="btn btn-block btn-primary mb-2">MASUK</button>
                                <p class="mb-1">Belum punya akun? <a href="{{route('register')}}" class="f-w-400">Daftar Disini</a></p>
                                <img src="assets/img/Logo-NLT-1080.png" width="70" alt="" class="img-fluid mt-2">

                                <p class="m-0 p-0">KEMENTERIAN PUPR</p>
                                <p class="m-0 p-0">DIREKTORAT JENDERAL SUMBER DAYA AIR</p>
                                <p class="m-0 p-0">BALAI WILAYAH SUNGAI SULAWESI III</p>
                                <p class="m-0 p-0">©SISDA - 2022</p>
                                {{-- <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © 2024 - <script>
                                        document.write(new Date().getFullYear())
                                    </script>
                
                                    <a href="https://kirana.id/" target="_blank" class="font-weight-bold" target="_blank">KIRANA</a>
                                </div> --}}
                            </form>
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
    
    <script src="/vendors/include/js/ripple.js"></script>
    <script src="/vendors/include/js/pcoded.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="/vendors/include/animation.js"></script>
</body>

</html>