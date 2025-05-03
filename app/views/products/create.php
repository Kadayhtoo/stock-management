<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/dashboard-nav.php'; ?>

    <h1>Create Product</h1>

    <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="/products?action=store">
        <label class="form-label">Name:</label><br>
        <input class="form-control" type="text" name="name" required><br><br>
        <label class="form-label">Price:</label><br>
        <input class="form-control" type="text" name="price" required><br><br>
        <label class="form-label">Available Quantity:</label><br>
        <input class="form-control" type="number" name="quantity_available" required><br><br>

        <button class="btn btn-success" type="submit">Save</button>
    </form>

    <p><a href="/products">Back to Product List</a></p>
</body>
</html>
