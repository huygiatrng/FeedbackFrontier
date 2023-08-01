<?php

use PHPUnit\Framework\TestCase;

include_once 'public/authentication/login.php';

class UserLoginTest extends TestCase
{
    /**
     * @var PDO
     */
    private $conn;

    protected function setUp(): void
    {
        // Set up the database connection
        $this->conn = new PDO('mysql:host=localhost;dbname=coursefeedbackdb', 'root', '');
    }

    protected function tearDown(): void
    {
        // Close the database connection
        $this->conn = null;
    }


    /*   Test ID: 1  

        Purpose of Test: To test if the user can login with valid credentials
        
        Test Environment: The test is conducted in the login page of the application.
         The test is conducted on the local machine.
          The test is conducted on the Google Chrome browser.
           The test is conducted on the Windows 10 operating system.
            The test is conducted on the XAMPP server.
             The test is conducted on the MySQL database.
              The test is conducted on the PHP programming language. 
              The test is conducted on the PHPUnit testing framework.
               The test is conducted on the Selenium testing framework.
                The test is conducted on the Eclipse IDE.
                 The test is conducted on the Windows PowerShell.
                  The test is conducted on the Windows Command Prompt. 
                  The test is conducted on the Git version control system. 

        
                  //concise, accurate and unambiguous instructions describing the precise
        steps the Tester must take to execute the test, including navigation
        through the AUT as well as any inputs and outputs
        Test Steps: 
        1. Navigate to the login page of the application.
        2. Enter the email address of the user.
        3. Enter the password of the user.
        4. Click the login button.
        5. Check if the user is redirected to the user dashboard page.
        6. Check if the user is logged in.
        7. Check if the user is not redirected to the login page.
        8. Check if the user is not logged out.
        9. Check if the user is not redirected to the login page.
        10. Check if the user is not logged in.

        //you will need specific concrete inputs, such as user name and
        password
        Test Input: 
        1. Email: 
        2. Password:
        3. Login Button:
        4. User Dashboard Page:
        5. Login Page:

        //a brief and unambiguous description of the expected result for
        passing of a test subsequent to test execution
        Likely Problems/Bugs Revealed //likely outcomes of testing such as feature not wo
        Expected Result: 
        1. The user is redirected to the user dashboard page.
        2. The user is logged in.
        3. The user is not redirected to the login page.
        4. The user is not logged out.

 */
    public function testLoginWithValidCredentials(): void
    {
        // Prepare the test data
        $email = 'student@mail.com';
        $password = '12345678';

        // Call the login function
        $result = login($email, $password, $this->conn);

        // Assert that the result is the expected URL
        $this->assertEquals('../pubic/user/user_dashboard.php', $result);
    }

    /* 
    TEST ID: 2

    Purpose of Test: To test if the user can login with invalid credentials

    Test Environment: The test is conducted in the login page of the application.
     The test is conducted on the local machine.
      The test is conducted on the Google Chrome browser.
       The test is conducted on the Windows 10 operating system.
        The test is conducted on the XAMPP server.
         The test is conducted on the MySQL database.
          The test is conducted on the PHP programming language. 
          The test is conducted on the PHPUnit testing framework.
           The test is conducted on the Selenium testing framework.
            The test is conducted on the Eclipse IDE.
             The test is conducted on the Windows PowerShell.
              The test is conducted on the Windows Command Prompt. 
              The test is conducted on the Git version control system.

    Test Steps:
    1. Navigate to the login page of the application.
    2. Enter the email address of the user.
    3. Enter the password of the user.
    4. Click the login button.
    5. Check if the user is redirected to the login page.
    6. Check if the user is not logged in.
    7. Check if the user is not redirected to the user dashboard page.
    8. Check if the user is not logged out.
    9. Check if the user is not redirected to the login page.
    10. Check if the user is not logged in.

    Test Input:
    1. Email:
    2. Password:
    3. Login Button:
    4. Login Page:
    
    Expected Result:
    1. The user is redirected to the login page.
    2. The user is not logged in.
    3. The user is not redirected to the user dashboard page.
    4. The user is logged out.

    */
    public function testLoginWithInvalidPassword(): void
    {
        // Prepare the test data
        $email = 'testing@mail.com';
        $password = 'wrongpassword';

        // Call the login function
        $result = login($email, $password, $this->conn);

        // Assert that the result is the expected error message
        $this->assertEquals('Invalid password.', $result);
    }

    /*
    TEST ID: 3

    Purpose of Test: To test if the user can login with invalid credentials

    Test Environment: The test is conducted in the login page of the application.
     The test is conducted on the local machine.
      The test is conducted on the Google Chrome browser.
       The test is conducted on the Windows 10 operating system.
        The test is conducted on the XAMPP server.
         The test is conducted on the MySQL database.
          The test is conducted on the PHP programming language. 
          The test is conducted on the PHPUnit testing framework.
           The test is conducted on the Selenium testing framework.
            The test is conducted on the Eclipse IDE.
             The test is conducted on the Windows PowerShell.
              The test is conducted on the Windows Command Prompt. 
              The test is conducted on the Git version control system.

    Test Steps:
    1. Navigate to the login page of the application.
    2. Enter the email address of the user.
    3. Enter the password of the user.
    4. Click the login button.
    5. Check if the user is redirected to the login page.
    6. Check if the user is not logged in.
    7. Check if the user is not redirected to the user dashboard page.
    8. Check if the user is not logged out.
    9. Check if the user is not redirected to the login page.
    10. Check if the user is not logged in.

    Test Input:
    1. Email:
    2. Password:
    3. Login Button:
    4. Login Page:

    Expected Result:
    1. The user is redirected to the login page.
    2. The user is not logged in.
    3. The user is not redirected to the user dashboard page.
    4. The user is logged out.

    */
    public function testLoginWithNonExistingUser(): void
    {
        // Prepare the test data
        $email = 'nonexisting@example.com';
        $password = 'password';

        // Call the login function
        $result = login($email, $password, $this->conn);

        // Assert that the result is the expected error message
        $this->assertEquals('User not found.', $result);
    }
}
