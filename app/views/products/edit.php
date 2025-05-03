<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/dashboard-nav.php'; ?>

    <h1>Edit Product</h1>

    <form method="POST" action="/products?action=update">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

        <label class="form-label">Name:</label><br>
        <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

        <label class="form-label">Price:</label><br>
        <input class="form-control" type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>
        
        <label class="form-label">Available Quantity:</label><br>
        <input class="form-control" type="text" name="quantity_available" value="<?= htmlspecialchars($product['quantity_available']) ?>" required><br><br>

        <button class="btn btn-warning" type="submit">Update</button>
    </form>

    <p><a href="/products">Back to Product List</a></p>
</body>
</html>
