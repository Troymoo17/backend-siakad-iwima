<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD IWP Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #1a3a5c 0%, #2d6a9f 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { background: #fff; border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,.3); padding: 2.5rem; width: 100%; max-width: 420px; }
        .login-logo { background: linear-gradient(135deg, #1a3a5c, #2d6a9f); border-radius: .75rem; width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <div class="login-card">
                <div class="login-logo">
                    <i class="fas fa-graduation-cap text-white fa-2x"></i>
                </div>
                <h4 class="text-center fw-bold mb-1" style="color:#1a3a5c">SIAKAD IWP</h4>
                <p class="text-center text-muted mb-4">Panel Administrator</p>

                @if($errors->has('login'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first('login') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" placeholder="Username admin" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="pwd" class="form-control" placeholder="Password" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2" style="background:#1a3a5c;border-color:#1a3a5c">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                <p class="text-center text-muted mt-3 small">
                    Institut Widya Pratama Pekalongan &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd() {
    const pwd = document.getElementById('pwd');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else { pwd.type = 'password'; icon.className = 'fas fa-eye'; }
}
</script>
</body>
</html>
