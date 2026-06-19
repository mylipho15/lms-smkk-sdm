<div class="auth-container">
    <div class="auth-card">
        <h2>Daftar Akun LMS SMKK SDM</h2>
        <form method="POST" action="/register">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>
        <div class="auth-links">
            <a href="/login">Sudah punya akun? Login</a>
        </div>
    </div>
</div>
