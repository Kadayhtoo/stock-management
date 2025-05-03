<?php

require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/core/Database.php';

class UserLoginTest {
    private $pdo;
    private $testEmail = 'testuser@example.com';
    private $testUsername = 'testuser';
    private $testPassword = 'password123';
    private $testAdminEmail = 'admin@example.com';
    private $testAdminPassword = 'admin123';

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function setUp() {
        // Clean up any existing test data
        $this->pdo->prepare("DELETE FROM users WHERE email IN (?, ?)")->execute([$this->testEmail, $this->testAdminEmail]);
        
        // Create test user
        User::register($this->testUsername, $this->testEmail, $this->testPassword);
        
        // Create test admin user
        $this->pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)")->execute([
            'admin',
            $this->testAdminEmail,
            password_hash($this->testAdminPassword, PASSWORD_DEFAULT),
            'admin'
        ]);
    }

    public function tearDown() {
        // Clean up after tests
        $this->pdo->prepare("DELETE FROM users WHERE email IN (?, ?)")->execute([$this->testEmail, $this->testAdminEmail]);
    }

    public function testSuccessfulUserLogin() {
        $this->setUp();
        
        $user = User::findByEmail($this->testEmail);
        $result = $user && password_verify($this->testPassword, $user['password']);

        if ($result && $user['role'] === 'user') {
            echo "✅ testSuccessfulUserLogin passed.\n";
        } else {
            echo "❌ testSuccessfulUserLogin failed.\n";
        }

        $this->tearDown();
    }

    public function testSuccessfulAdminLogin() {
        $this->setUp();
        
        $user = User::findByEmail($this->testAdminEmail);
        $result = $user && password_verify($this->testAdminPassword, $user['password']);

        if ($result && $user['role'] === 'admin') {
            echo "✅ testSuccessfulAdminLogin passed.\n";
        } else {
            echo "❌ testSuccessfulAdminLogin failed.\n";
        }

        $this->tearDown();
    }

    public function testInvalidEmailLogin() {
        $this->setUp();
        
        $user = User::findByEmail('nonexistent@example.com');
        
        if (!$user) {
            echo "✅ testInvalidEmailLogin passed.\n";
        } else {
            echo "❌ testInvalidEmailLogin failed.\n";
        }

        $this->tearDown();
    }

    public function testInvalidPasswordLogin() {
        $this->setUp();
        
        $user = User::findByEmail($this->testEmail);
        $result = $user && password_verify('wrongpassword', $user['password']);

        if (!$result) {
            echo "✅ testInvalidPasswordLogin passed.\n";
        } else {
            echo "❌ testInvalidPasswordLogin failed.\n";
        }

        $this->tearDown();
    }

    public function testCaseInsensitiveEmailLogin() {
        $this->setUp();
        
        $user = User::findByEmail(strtoupper($this->testEmail));
        $result = $user && password_verify($this->testPassword, $user['password']);

        if ($result) {
            echo "✅ testCaseInsensitiveEmailLogin passed.\n";
        } else {
            echo "❌ testCaseInsensitiveEmailLogin failed.\n";
        }

        $this->tearDown();
    }

    public function runAllTests() {
        echo "Running User Login Tests...\n";
        echo "--------------------------------\n";
        
        $this->testSuccessfulUserLogin();
        $this->testSuccessfulAdminLogin();
        $this->testInvalidEmailLogin();
        $this->testInvalidPasswordLogin();
        $this->testCaseInsensitiveEmailLogin();
        
        echo "--------------------------------\n";
        echo "Tests completed.\n";
    }
}

// Run the tests
$test = new UserLoginTest();
$test->runAllTests();
