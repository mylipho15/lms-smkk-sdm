<div class="auth-container">
    <div class="auth-card">
        <h2>Lupa Password</h2>
        <form method="POST" action="/forgot-password">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Kirim Link Reset</button>
        </form>
        <div class="auth-links">
            <a href="/login">Kembali ke Login</a>
        </div>
    </div>
</div>
