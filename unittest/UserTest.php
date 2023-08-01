<?php

require_once 'D:/A- College/CSC4350SoftwareEngineer/course/src/User.php';
use PHPUnit\Framework\TestCase;

class MysqliResultWrapper {
    private $mysqli_result;

    public function __construct($mysqli_result) {
        $this->mysqli_result = $mysqli_result;
    }

    public function num_rows() {
        return $this->mysqli_result->num_rows;
    }

    public function fetch_assoc() {
        return $this->mysqli_result->fetch_assoc();
    }
}


class UserTest extends TestCase {

    public function testConstructorAndGetters() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);

        $this->assertEquals(1, $user->getUserID());
        $this->assertEquals('user', $user->getRole());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('Doe John', $user->getFullName());
    }

    public function testChangePassword() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);
        $user->changePassword('newPassword');

        $this->assertTrue(password_verify('newPassword', $user->password));
    }

    public function testChangeEmail() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);
        $user->changeEmail('john.new@example.com');

        $this->assertEquals('john.new@example.com', $user->email);
    }

    public function testChangeLastName() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);
        $user->changeLastName('Smith');

        $this->assertEquals('Smith', $user->last_name);
    }

    public function testChangeFirstName() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);
        $user->changeFirstName('James');

        $this->assertEquals('James', $user->first_name);
    }

    public function testFullName() {
        $data = [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'user',
            'school_id' => 1,
            'createdAt' => '2023-07-11'
        ];

        $user = new User($data);
        $this->assertEquals('Doe John', $user->getFullName());
    }

}
