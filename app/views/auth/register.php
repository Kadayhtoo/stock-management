<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <h3 class="mb-4 text-center">Register</h3>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="/register">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input name="username" id="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" type="email" id="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" id="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Register</button>
        </form>
        <div class="mt-3 text-center">
          <a href="/login">Already have an account? Login</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
