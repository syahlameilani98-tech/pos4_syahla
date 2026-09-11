<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .login-header {
            background-color: #0d6efd;
            color: white;
            text-align: center;
            padding: 12px;
        }

        .login-body {
            padding: 24px;
        }

        .btn-login {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            color: white;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="card login-card">

            <div class="login-header">
                <h4 class="mb-0 fw-bold">
                    Login POS
                </h4>
            </div>

            <div class="login-body">

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">

                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email address
                        </label>

                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-login fw-semibold">
                        Submit
                    </button>

                </form>

            </div>
        </div>

    </div>

</body>

</html>