<?php
require_once 'D:/A- College/CSC4350SoftwareEngineer/course/src/User.php';
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase {

    public function testAuthenticate() {
        // Initialize or Mock the database connection here.
        $conn = new mysqli('localhost', 'root', '', 'coursefeedbackdb');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Successful user authentication test
        $user = User::authenticate('student@gmail.com', '12345678', $conn);
        $this->assertInstanceOf(User::class, $user);

        // Successful authentication test
        $user = User::authenticate('admin@gmail.com', '12345678', $conn);
        $this->assertInstanceOf(User::class, $user);

        // Wrong email test
        $user = User::authenticate('wrongemail@example.com', 'testpassword', $conn);
        $this->assertNull($user);

        // Wrong password test
        $user = User::authenticate('student@gmail.com', 'wrongpassword', $conn);
        $this->assertNull($user);
    }
}
