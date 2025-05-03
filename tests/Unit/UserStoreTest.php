<?php

require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/core/Database.php';

class UserStoreTest {
    private $pdo;
    private $testEmail = 'testuser@example.com';
    private $testUsername = 'testuser';
    private $testPassword = 'password123';

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function setUp() {
        // Clean up any existing test data
        $this->pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$this->testEmail]);
    }

    public function tearDown() {
        // Clean up after tests
        $this->pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$this->testEmail]);
    }

    public function testStoreRegularUser() {
        $this->setUp();
        
        $data = [
            'username' => $this->testUsername,
            'email' => $this->testEmail,
            'password' => $this->testPassword,
            'role' => 'user'
        ];
        
        $result = User::store($data);
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
        $user = $stmt->fetch();

        if ($result && $user && 
            $user['username'] === $this->testUsername && 
            password_verify($this->testPassword, $user['password']) &&
            $user['role'] === 'user') {
            echo "✅ testStoreRegularUser passed.\n";
        } else {
            echo "❌ testStoreRegularUser failed.\n";
        }

        $this->tearDown();
    }

    public function testStoreAdminUser() {
        $this->setUp();
        
        $data = [
            'username' => $this->testUsername,
            'email' => $this->testEmail,
            'password' => $this->testPassword,
            'role' => 'admin'
        ];
        
        $result = User::store($data);
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
        $user = $stmt->fetch();

        if ($result && $user && 
            $user['username'] === $this->testUsername && 
            password_verify($this->testPassword, $user['password']) &&
            $user['role'] === 'admin') {
            echo "✅ testStoreAdminUser passed.\n";
        } else {
            echo "❌ testStoreAdminUser failed.\n";
        }

        $this->tearDown();
    }

    public function testStoreDuplicateEmail() {
        $this->setUp();
        
        // First store
        $data1 = [
            'username' => $this->testUsername,
            'email' => $this->testEmail,
            'password' => $this->testPassword,
            'role' => 'user'
        ];
        User::store($data1);
        
        // Try to store with same email
        $data2 = [
            'username' => 'anotheruser',
            'email' => $this->testEmail,
            'password' => 'anotherpass',
            'role' => 'user'
        ];
        
        try {
            $result = User::store($data2);
            echo "❌ testStoreDuplicateEmail failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === "Email already exists") {
                echo "✅ testStoreDuplicateEmail passed.\n";
            } else {
                echo "❌ testStoreDuplicateEmail failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testStoreDuplicateUsername() {
        $this->setUp();
        
        // First store
        $data1 = [
            'username' => $this->testUsername,
            'email' => $this->testEmail,
            'password' => $this->testPassword,
            'role' => 'user'
        ];
        User::store($data1);
        
        // Try to store with same username
        $data2 = [
            'username' => $this->testUsername,
            'email' => 'another@example.com',
            'password' => 'anotherpass',
            'role' => 'user'
        ];
        
        try {
            $result = User::store($data2);
            echo "❌ testStoreDuplicateUsername failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if ($e->getMessage() === 'User Name already exists') {
                echo "✅ testStoreDuplicateUsername passed.\n";
            } else {
                echo "❌ testStoreDuplicateUsername failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testStoreInvalidRole() {
        $this->setUp();
        
        $data = [
            'username' => $this->testUsername,
            'email' => $this->testEmail,
            'password' => $this->testPassword,
            'role' => 'invalid_role'
        ];
        
        try {
            $result = User::store($data);
            echo "❌ testStoreInvalidRole failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Data truncated') !== false) {
                echo "✅ testStoreInvalidRole passed.\n";
            } else {
                echo "❌ testStoreInvalidRole failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function runAllTests() {
        echo "Running User Store Tests...\n";
        echo "--------------------------------\n";
        
        $this->testStoreRegularUser();
        $this->testStoreAdminUser();
        $this->testStoreDuplicateEmail();
        $this->testStoreDuplicateUsername();
        $this->testStoreInvalidRole();
        
        echo "--------------------------------\n";
        echo "Tests completed.\n";
    }
}

// Run the tests
$test = new UserStoreTest();
$test->runAllTests();
