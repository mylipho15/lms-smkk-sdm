<div class="auth-container">
    <div class="auth-card">
        <h2>Reset Password</h2>
        <form method="POST" action="/reset-password">
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>
    </div>
</div>
