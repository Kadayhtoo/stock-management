<!DOCTYPE html>
<html>
<head>
    <title>Transaction List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/dashboard-nav.php'; ?>
  
    <h1>Transaction List</h1>


    <table border="1" cellpadding="10" class="table">
        <tr>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
        </tr>

        <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?= htmlspecialchars($transaction['product_name']) ?></td>
            <td><?= htmlspecialchars($transaction['product_price']) ?></td>
            <td><?= htmlspecialchars($transaction['quantity']) ?></td>
            <td><?= htmlspecialchars($transaction['total_price']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
</body>
</html>

