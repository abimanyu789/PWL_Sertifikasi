<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Make sure the full height is covered */
        html, body {
            height: 100%;
            margin: 0;
        }

        /* Full-screen blue background */
        body {
            background-color: #1F4C97; /* Blue background */
        }

        /* Container for login form */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Login box styling */
        .login-box {
            width: 600px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        /* Pseudo-element untuk kotak transparan di atas kiri */
        .container::before,
        .container::after {
            content: "";
            position: absolute;
            background-color: white;
            opacity: 0.2;
            border-radius: 15px; /* Sudut sedikit melengkung */
        }

        .container::before {
            width: 300px;
            height: 300px;
            top: -40px; /* Posisi di sudut kiri atas */
            left: -40px;
        }

        .container::after {
            width: 300px;
            height: 300px;
            bottom: -40px; /* Posisi di sudut kanan bawah */
            right: -40px;
        }

        /* Logo container */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px; /* Adjust logo size */
        }

        h3 {
            color: #1F4C97; /* Blue color */
            font-weight: bold;
        }

        /* Form inputs */
        .form-control {
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            font-size: 16px;
        }

        /* Submit button */
        .btn-primary {
            background-color: #1F4C97;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            padding: 8px 5px;
            width: 300px; /* Sesuaikan lebar tombol */
            margin: 0 auto; /* Memusatkan tombol secara horizontal */
        }

        /* Hover effect for button */
        .btn-primary:hover {
            background-color: #1F4C97;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .login-box {
                width: 50%;
            }
        }

        /* Center the "Login" text */
        .login-text {
            text-align: center;
            font-size: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo-container">
                <!-- Ensure you have the correct path to the image -->
                <img src="{{ asset('logo_polinema.png') }}" class="logo" alt="Logo">
            </div>
            <div class="form-container">
                <h3 class="text-center">JTI POLINEMA</h3>
                <!-- Adding the Login text under the logo -->
                <p class="login-text">Login</p>
                <form action="/login" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

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
                    password: {required: true, minlength: 5, maxlength: 20}
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
                                    window.location = response.redirect;
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
