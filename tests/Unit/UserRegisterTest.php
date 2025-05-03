<?php

require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/core/Database.php';

class UserRegisterTest {
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

    public function testSuccessfulRegistration() {
        $this->setUp();
        
        $result = User::register($this->testUsername, $this->testEmail, $this->testPassword);
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
        $user = $stmt->fetch();

        if ($result && $user && 
            $user['username'] === $this->testUsername && 
            password_verify($this->testPassword, $user['password']) &&
            $user['role'] === 'user') {
            echo "✅ testSuccessfulRegistration passed.\n";
        } else {
            echo "❌ testSuccessfulRegistration failed.\n";
        }

        $this->tearDown();
    }

    public function testDuplicateEmailRegistration() {
        $this->setUp();
        
        // First registration
        User::register($this->testUsername, $this->testEmail, $this->testPassword);
        
        // Try to register with same email
        try {
            $result = User::register('anotheruser', $this->testEmail, 'anotherpass');
            echo "❌ testDuplicateEmailRegistration failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "✅ testDuplicateEmailRegistration passed.\n";
            } else {
                echo "❌ testDuplicateEmailRegistration failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testDuplicateUsernameRegistration() {
        $this->setUp();
        
        // First registration
        User::register($this->testUsername, $this->testEmail, $this->testPassword);
        
        // Try to register with same username
        try {
            $result = User::register($this->testUsername, 'another@example.com', 'anotherpass');
            echo "❌ testDuplicateUsernameRegistration failed: Expected exception not thrown.\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "✅ testDuplicateUsernameRegistration passed.\n";
            } else {
                echo "❌ testDuplicateUsernameRegistration failed: Unexpected error: " . $e->getMessage() . "\n";
            }
        }

        $this->tearDown();
    }

    public function testPasswordHashing() {
        $this->setUp();
        
        $result = User::register($this->testUsername, $this->testEmail, $this->testPassword);
        
        $stmt = $this->pdo->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->execute([$this->testEmail]);
        $user = $stmt->fetch();

        if ($result && $user && 
            password_verify($this->testPassword, $user['password']) && 
            $user['password'] !== $this->testPassword) {
            echo "✅ testPasswordHashing passed.\n";
        } else {
            echo "❌ testPasswordHashing failed.\n";
        }

        $this->tearDown();
    }

    public function runAllTests() {
        echo "Running User Registration Tests...\n";
        echo "--------------------------------\n";
        
        $this->testSuccessfulRegistration();
        $this->testDuplicateEmailRegistration();
        $this->testDuplicateUsernameRegistration();
        $this->testPasswordHashing();
        
        echo "--------------------------------\n";
        echo "Tests completed.\n";
    }
}

// Run the tests
$test = new UserRegisterTest();
$test->runAllTests();
