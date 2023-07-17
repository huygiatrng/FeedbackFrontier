include_once 'D:\A- College\CSC4350SoftwareEngineer\course\unittest\vendor\autoload.php';
include_once 'login.php';

class LoginTest extends PHPUnit_Framework_TestCase {
    public function setUp(): void {
        // Mocking $_POST superglobal
        $_POST['email'] = 'testemail@example.com';
        $_POST['password'] = 'password';
        $_POST['submit'] = true;

        // Mocking User class and its static method authenticate
        $mockUser = $this->getMockBuilder('User')
                         ->disableOriginalConstructor()
                         ->setMethods(['authenticate'])
                         ->getMock();
        $mockUser->method('authenticate')
                 ->willReturn(new User('123', 'admin'));
        $this->mockUser = $mockUser;

        // Mocking session_start function
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function tearDown(): void {
        session_unset();
        session_destroy();
    }

    public function testValidLogin(): void {
        // Mock successful login
        $_SESSION['user_id'] = $this->mockUser->user_id;
        $_SESSION['role'] = $this->mockUser->role;

        $this->assertEquals($_SESSION['user_id'], '123');
        $this->assertEquals($_SESSION['role'], 'admin');
    }

    public function testInvalidLogin(): void {
        // Mock failed login
        $mockUser = $this->getMockBuilder('User')
                         ->disableOriginalConstructor()
                         ->setMethods(['authenticate'])
                         ->getMock();
        $mockUser->method('authenticate')
                 ->willReturn(false);
        $this->mockUser = $mockUser;

        $this->assertFalse($this->mockUser->authenticate($_POST['email'], $_POST['password']));
    }
}
?>