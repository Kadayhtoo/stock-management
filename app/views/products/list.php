<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/dashboard-nav.php'; ?>
  
    <h1>All Products</h1>

    <a href="/products?action=create">Create New Product</a>

    <table border="1" cellpadding="10" class="table">
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity Available</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['price']) ?></td>
            <td><?= htmlspecialchars($product['quantity_available']) ?></td>
            <td>
                <a href="/products?action=edit&id=<?= $product['id'] ?>">Edit</a> |
                <a href="/products?action=delete&id=<?= $product['id'] ?>" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
</body>
</html>

