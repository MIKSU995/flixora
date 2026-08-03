<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Flixora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/flixora.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: radial-gradient(circle at center, #171f30 0%, #0b0f19 100%);">

    <div class="glass-panel" style="width: 100%; max-width: 420px; padding: 2.5rem; border-color: rgba(229,9,20,0.3);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ route('home') }}" class="brand-logo" style="justify-content: center; font-size: 2rem; margin-bottom: 0.5rem;">
                FLIXORA
            </a>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Login Hak Akses Admin Panel</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email Admin *</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@flixora.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password" class="form-label">Password *</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-muted);">
                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                    <input type="checkbox" name="remember" style="accent-color: var(--accent-red);"> Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.8rem;">
                Masuk ke Dashboard Admin
            </button>
        </form>

        <div style="margin-top: 2rem; border-top: 1px solid var(--border-glass); padding-top: 1rem; text-align: center;">
            <a href="{{ route('home') }}" class="nav-link" style="font-size: 0.85rem;">← Kembali ke Beranda User</a>
        </div>
    </div>

</body>
</html>
