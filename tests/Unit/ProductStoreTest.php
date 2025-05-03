<?php

require_once __DIR__ . '/../../app/models/Product.php';
require_once __DIR__ . '/../../app/core/Database.php';

class ProductStoreTest {
    private $pdo;
    private $testProductName = 'Test Product';
    private $testProductPrice = 99.99;
    private $testProductQuantity = 10;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function setUp() {
        // Clean up any existing test data
        $this->pdo->prepare("DELETE FROM products WHERE name = ?")->execute([$this->testProductName]);
    }

    public function tearDown() {
        // Clean up after tests
        $this->pdo->prepare("DELETE FROM products WHERE name = ?")->execute([$this->testProductName]);
    }

    public function testSuccessfulProductStore() {
        $this->setUp();
        
        $data = [
            'name' => $this->testProductName,
            'price' => $this->testProductPrice,
            'quantity_available' => $this->testProductQuantity
        ];
        
        $result = Product::store($data);
        
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE name = ?");
        $stmt->execute([$this->testProductName]);
        $product = $stmt->fetch();

        if ($result && $product && 
            $product['name'] === $this->testProductName && 
            $product['price'] == $this->testProductPrice &&
            $product['quantity_available'] == $this->testProductQuantity) {
            echo "✅ testSuccessfulProductStore passed.\n";
        } else {
            echo "❌ testSuccessfulProductStore failed.\n";
        }

        $this->tearDown();
    }

    public function testDuplicateProductName() {
        $this->setUp();
        
        // First product creation
        $data1 = [
            'name' => $this->testProductName,
            'price' => $this->testProductPrice,
            'quantity_available' => $this->testProductQuantity
        ];
        Product::store($data1);
        
        // Try to create product with same name
        $data2 = [
            'name' => $this->testProductName,
            'price' => 199.99,
            'quantity_available' => 20
        ];
        
        try {
            $result = Product::store($data2);
            echo "❌ testDuplicateProductName failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "A product with this name already exists") {
                echo "✅ testDuplicateProductName passed.\n";
            } else {
                echo "❌ testDuplicateProductName failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testInvalidPrice() {
        $this->setUp();
        
        $data = [
            'name' => $this->testProductName,
            'price' => -10.00, // Invalid negative price
            'quantity_available' => $this->testProductQuantity
        ];
        
        try {
            $result = Product::store($data);
            echo "❌ testInvalidPrice failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "Price must be a positive number") {
                echo "✅ testInvalidPrice passed.\n";
            } else {
                echo "❌ testInvalidPrice failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testInvalidQuantity() {
        $this->setUp();
        
        $data = [
            'name' => $this->testProductName,
            'price' => $this->testProductPrice,
            'quantity_available' => -5 // Invalid negative quantity
        ];
        
        try {
            $result = Product::store($data);
            echo "❌ testInvalidQuantity failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "Quantity must be a positive number") {
                echo "✅ testInvalidQuantity passed.\n";
            } else {
                echo "❌ testInvalidQuantity failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testMissingRequiredFields() {
        $this->setUp();
        
        // Test missing name
        $data1 = [
            'price' => $this->testProductPrice,
            'quantity_available' => $this->testProductQuantity
        ];
        
        try {
            $result = Product::store($data1);
            echo "❌ testMissingRequiredFields failed: Expected exception not thrown for missing name.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "Missing required fields") {
                echo "✅ testMissingRequiredFields passed for missing name.\n";
            } else {
                echo "❌ testMissingRequiredFields failed: Unexpected error for missing name: " . $e->getMessage() . "\n";
            }
        }

        // Test missing price
        $data2 = [
            'name' => $this->testProductName,
            'quantity_available' => $this->testProductQuantity
        ];
        
        try {
            $result = Product::store($data2);
            echo "❌ testMissingRequiredFields failed: Expected exception not thrown for missing price.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "Missing required fields") {
                echo "✅ testMissingRequiredFields passed for missing price.\n";
            } else {
                echo "❌ testMissingRequiredFields failed: Unexpected error for missing price: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function runAllTests() {
        echo "Running Product Store Tests...\n";
        echo "--------------------------------\n";
        
        $this->testSuccessfulProductStore();
        $this->testDuplicateProductName();
        $this->testInvalidPrice();
        $this->testInvalidQuantity();
        $this->testMissingRequiredFields();
        
        echo "--------------------------------\n";
        echo "Tests completed.\n";
    }
}

// Run the tests
$test = new ProductStoreTest();
$test->runAllTests();
