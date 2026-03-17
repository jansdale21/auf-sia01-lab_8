<?php

require 'vendor/autoload.php';

$config = require 'config.php';
\Stripe\Stripe::setApiKey($config['secret_key']);

$productsData = [];

try {
    $products = \Stripe\Product::all(['limit' => 10]);

    foreach ($products->data as $product) {
        $prices = \Stripe\Price::all([
            'product' => $product->id,
            'limit' => 1,
        ]);

        $price = isset($prices->data[0])
            ? $prices->data[0]->unit_amount / 100
            : 0;

        $productsData[] = [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $price,
        ];
    }
} catch (Exception $e) {
    echo 'Error fetching products: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gizmo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <nav class="navbar">
        <span class="navbar-brand">🛒 Gizmo Shop</span>
    </nav>

    <div class="container">
        <div class="row">
            <?php foreach ($productsData as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($product['name']); ?></h5>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <p class="price">$<?= number_format($product['price'], 2); ?></p>
                            <a href="checkout.php?price=<?= $product['price']; ?>" class="btn btn-buy">Buy Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>