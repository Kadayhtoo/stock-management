<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/dashboard-nav.php'; ?>

    <h1>Create User</h1>

    <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/users?action=store">
        <label class="form-label">Name:</label><br>
        <input class="form-control" type="text" name="username" required><br><br>
        <label class="form-label">Email:</label><br>
        <input class="form-control" type="email" name="email" required><br><br>
        <label class="form-label">Password:</label><br>
        <input class="form-control" type="password" name="password" required><br><br>
        <label class="form-label">Role:</label><br>
        <select class="form-select" aria-label="Default select example" name="role">
            <option value="admin">Admin</option>
            <option value="user" selected>User</option>
        </select><br><br>
        <button class="btn btn-success" type="submit">Save</button>
    </form>

    <p><a href="/users">Back to User List</a></p>
</body>
</html>
