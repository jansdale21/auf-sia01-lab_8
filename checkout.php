<?php

require 'vendor/autoload.php';

$config = require 'config.php';
\Stripe\Stripe::setApiKey($config['secret_key']);

if (!isset($_GET['price'])) {
    die('No product selected.');
}

$price = (int) ($_GET['price'] * 100);

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Gizmo Product',
                    ],
                    'unit_amount' => $price,
                ],
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => 'http://localhost/stripe-php-app/success.php',
        'cancel_url' => 'http://localhost/stripe-php-app/cancel.php',
    ]);
    header('Location: ' . $session->url);
    exit();
} catch (Exception $e) {
    die('Error creating checkout session: ' . $e->getMessage());
}