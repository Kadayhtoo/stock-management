<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php require __DIR__ . '/partials/home-nav.php'; ?>

  <div class="container-fluid">
        <div class="row">
            <!-- Left: Product List -->
<div class="col-md-8">
    <h4 class="my-3">Products</h4>
    <div style="max-height: 80vh; overflow-y: auto; padding-right: 10px;">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card product-card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text">Price: $<?= $product['price'] ?><br>Stock: <?= $product['quantity_available'] ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(<?= $product['id'] ?>)">-</button>
                            <button class="btn btn-sm btn-success" onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>)">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

            <!-- Right: Invoice Summary -->
<div class="col-md-4">
    <h4 class="my-3">Invoice</h4>
    <div class="card shadow">
        <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <!-- Items will be added here dynamically -->
                </tbody>
            </table>

            <hr>
            <form method="POST" action="/purchases?action=store" onsubmit="return handleFormSubmit(event)">
              <input type="hidden" name="payload" id="purchase-payload">


              <div class="d-flex justify-content-between fw-bold">
                  <span>Total:</span>
                  <span id="cart-total">$0.00</span>
              </div>

              <button type="submit" class="btn btn-success mt-3">Confirm Purchase</button>
            </form>
        </div>
    </div>
</div>
        </div>
    </div>

    <script>
let cart = {};

function addToCart(id, name, price) {
    if (!cart[id]) {
        cart[id] = { name, price, quantity: 0 };
    }
    cart[id].quantity++;
    renderCart();
}

function removeFromCart(id) {
    if (cart[id]) {
        cart[id].quantity--;
        if (cart[id].quantity <= 0) {
            delete cart[id];
        }
        renderCart();
    }
}

function renderCart() {
    const cartBody = document.getElementById('cart-body');
    const cartTotal = document.getElementById('cart-total');
    cartBody.innerHTML = '';
    let total = 0;

    Object.values(cart).forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        cartBody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>${item.quantity}</td>
                <td>$${itemTotal.toFixed(2)}</td>
            </tr>
        `;
    });

    cartTotal.textContent = `$${total.toFixed(2)}`;
}

function handleFormSubmit(event) {
        if (Object.keys(cart).length === 0) {
            alert('Your cart is empty. Please add items to your cart.');
            event.preventDefault();
            return false;
        }

        const transactionItems = Object.entries(cart).map(([productId, item]) => ({
            product_id: productId,
            // name: item.name,
            quantity: item.quantity,
            price: item.price,
            total_price: item.price * item.quantity
        }));

        // Inject JSON into hidden field
        document.getElementById('purchase-payload').value = JSON.stringify({
            items: transactionItems
        });

        // Let form submit
        return true;
    }
</script>


</div>
</body>
</html>

