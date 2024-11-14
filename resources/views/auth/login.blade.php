<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <!-- Additional custom styles -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: #1F4C97; /* Blue background */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 500px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
            margin-bottom: 15px;
        }

        h3 {
            color: #1F4C97;
            font-weight: bold;
            text-align: center;
        }

        .form-control {
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            font-size: 16px;
        }

        .btn-primary {
            background-color: #1F4C97;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            padding: 8px 0; /* Mengurangi padding kiri dan kanan */
            width: 100%; /* Membuat tombol lebar penuh dalam container */
            margin: 0 auto; /* Memastikan tombol berada di tengah */
        }


        .btn-primary:hover {
            background-color: #1F4C97;
        }

        .login-text {
            text-align: center;
            font-size: 20px;
            margin-top: 10px;
        }

        .container::before, .container::after {
            content: "";
            position: absolute;
            background-color: white;
            opacity: 0.2;
            border-radius: 15px;
        }

        .container::before {
            width: 300px;
            height: 300px;
            top: -40px;
            left: -40px;
        }

        .container::after {
            width: 300px;
            height: 300px;
            bottom: -40px;
            right: -40px;
        }

        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            .login-box {
                width: 80%;
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="container">
        <div class="login-box">
            <div class="logo-container">
                <img src="{{ asset('logo_polinema.png') }}" class="logo" alt="Logo">
            </div>
            <h3>JTI POLINEMA</h3>
            <p class="login-text">Login</p>
            <div class="card-body">
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery --> 
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> 
<!-- Bootstrap 4 --> 
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
<!-- jquery-validation --> 
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script> 
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script> 
<!-- SweetAlert2 --> 
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script> 
<!-- AdminLTE App --> 
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<script> 
    $.ajaxSetup({ 
      headers: { 
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
      } 
    }); 
   
    $(document).ready(function() { 
      $("#form-login").validate({ 
        rules: { 
          username: {required: true, minlength: 4, maxlength: 20}, 
          password: {required: true, minlength: 6, maxlength: 20} 
        }, 
        submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan 
          $.ajax({ 
            url: form.action, 
            type: form.method, 
            data: $(form).serialize(), 
            success: function(response) { 
              if(response.status){ // jika sukses 
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function() {
                    window.location = response.redirect; // Pastikan redirect URL valid
                });
              }else{ // jika error 
                $('.error-text').text(''); 
                $.each(response.msgField, function(prefix, val) { 
                    $('#error-'+prefix).text(val[0]); 
                }); 
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Terjadi Kesalahan', 
                    text: response.message 
                }); 
              } 
            } 
          }); 
          return false; 
        }, 
        errorElement: 'span', 
        errorPlacement: function (error, element) { 
          error.addClass('invalid-feedback'); 
          element.closest('.input-group').append(error); 
        }, 
        highlight: function (element, errorClass, validClass) { 
          $(element).addClass('is-invalid'); 
        }, 
        unhighlight: function (element, errorClass, validClass) { 
          $(element).removeClass('is-invalid'); 
        } 
      }); 
    }); 
  </script> 
  
</body>
</html>
