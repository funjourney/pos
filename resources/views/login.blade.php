<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-signin {
            max-width: 330px;
            padding: 1rem;
            margin: auto;
        }
    </style>
</head>
<body class="text-center">
    <main class="form-signin">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <img class="mb-4" src="https://o2.funjourney.co.id/assets/images/sites/FJ_logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

            <div class="form-floating">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail" placeholder="Email address" required autofocus value="{{ old('email') }}">
                <label for="inputEmail">Email address</label>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-floating">
                <input type="password" name="password" class="form-control mt-2 @error('password') is-invalid @enderror" id="inputPassword" placeholder="Password" required>
                <label for="inputPassword">Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="checkbox my-3">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <button class="btn btn-lg btn-primary w-100" type="submit">Sign in</button>

            <p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }}</p>
        </form>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
