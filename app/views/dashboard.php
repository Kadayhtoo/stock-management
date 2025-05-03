<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php require __DIR__ . '/partials/dashboard-nav.php'; ?>

  <div class="container mt-5">
    <div class="text-center">
      <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
      <p class="lead">You are logged in as <strong><?= $_SESSION['user']['role'] ?? 'User' ?></strong>.</p>
    </div>

    <div class="row mt-4">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Products</h5>
            <p class="card-text">Manage all products.</p>
            <a href="/products" class="btn btn-primary">Go to Products</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Users</h5>
            <p class="card-text">Manage all users.</p>
            <a href="/users" class="btn btn-primary">Go to Users</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <h5 class="card-title">Transactions</h5>
            <p class="card-text">Manage all transactions.</p>
            <a href="/transactions" class="btn btn-primary">Go to Transactions</a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
