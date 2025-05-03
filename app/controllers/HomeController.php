<?php
require_once __DIR__ . '/../models/Product.php';

$products = Product::all();
require __DIR__ . '/../views/home.php';