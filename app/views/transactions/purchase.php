<!DOCTYPE html>
<html>
<head>
    <title>Purchase List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/../partials/home-nav.php'; ?>
  
    <h1>All Purchase Records</h1>

    <table border="1" cellpadding="10" class="table">
        <tr>
            <th>Date</th>
            <th>Total Price</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($purchases as $purchase): ?>
        <tr>
            <td><?= htmlspecialchars($purchase['created_at']) ?></td>
            <td><?= htmlspecialchars($purchase['total_price']) ?></td>
            <td>
                <a href="/purchase-records?action=detail&id=<?= $purchase['id'] ?>">Detail</a>
                
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
</body>
</html>

