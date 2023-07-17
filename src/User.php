<?php

require_once 'School.php';
require_once 'Feedback.php';
require_once 'Course.php';
require_once 'Report.php';

class User
{
    private $properties = [
        'user_id' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'password' => null,
        'role' => null,
        'school_id' => null,
        'createdAt' => null
    ];

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->properties)) {
                $this->properties[$key] = $value;
            }
        }
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }
    }

    public function __set($property, $value)
    {
        if (array_key_exists($property, $this->properties)) {
            $this->properties[$property] = $value;
        }
        return $this;
    }

    public function getFullName(): string
    {
        return "{$this->properties['last_name']} {$this->properties['first_name']}";
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function changePassword($newPassword)
    {
        // Add some validation here, e.g., to check the strength of the password
        // Also, you should hash the password before storing it, never store passwords in plain text
        $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    public function changeSchoolId($newSchoolId)
    {
        // You may want to add some validation here to make sure the school id is valid
        // e.g., check if the school id exists in the schools table
        $this->properties['school_id'] = $newSchoolId;
    }

    public function saveSchoolId() {
        $conn = $GLOBALS['conn']; // assuming a global $conn

        $sql = "UPDATE Users SET school_id = ? WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ii", $this->properties['school_id'], $this->properties['user_id']);
            if (!$stmt->execute()) {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }



    public function getFeedbacks() {
        return Feedback::getFeedbacksByUserId($this->user_id);
    }

    public function getReports()
    {
        return Report::getReportsByUserId($this->user_id);
    }



    public function changeEmail($newEmail)
    {
        // Add some validation here, e.g., to check that the email is not already used by another user
        $this->properties['email'] = $newEmail;
    }

    public function changeLastName($newLastName)
    {
        // Add some validation here, e.g., to check that the email is not already used by another user
        $this->properties['last_name'] = $newLastName;
    }

    public function changeFirstName($newFirstName)
    {
        // Add some validation here, e.g., to check that the email is not already used by another user
        $this->properties['first_name'] = $newFirstName;
    }

    public function save() {
        $conn = $GLOBALS['conn']; // assuming a global $conn

        $sql = "UPDATE Users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $this->properties['first_name'], $this->properties['last_name'], $this->properties['email'], $this->properties['user_id']);
            if (!$stmt->execute()) {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }

    public static function getUserById($id) {
        $conn = $GLOBALS['conn']; // assuming a global $conn

        $sql = "SELECT * FROM users WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    return new User($row);
                } else {
                    throw new \Exception("User with this ID not found.");
                }
            } else {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }

    public function getSchool()
    {
        return School::getSchoolById($this->properties['school_id']);
    }

    public static function getFullNameById($id) {
        $user = self::getUserById($id);
        return $user->getFullName();
    }


    public static function getStudents($conn) {
        $result = $conn->query("SELECT * FROM Users WHERE role = 'user'");
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = new User($row);
        }
        return $students;
    }

    public static function getInstructors($conn) {
        $result = $conn->query("
            SELECT Users.*, COUNT(Courses.course_id) AS course_count 
            FROM Users 
            LEFT JOIN Courses ON Users.user_id = Courses.instructor_id 
            WHERE role = 'instructor' 
            GROUP BY Users.user_id
        ");
        $instructors = [];
        while ($row = $result->fetch_assoc()) {
            $instructors[] = new User($row);
        }
        return $instructors;
    }


    public function deleteUser() {
        global $conn; // Get the global database connection

        // Delete feedbacks associated with the user
        Feedback::deleteFeedbacksByUserId($this->properties['user_id'], $conn);

        // Delete reports associated with the user
        Report::deleteReportsByUserId($this->properties['user_id'], $conn); // assuming you have a similar method in Report class

        // Delete the user
        $sql = "DELETE FROM Users WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $this->properties['user_id']);
            if (!$stmt->execute()) {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }


    public function getCourseCount() {
        // Assuming a global $conn
        $conn = $GLOBALS['conn'];

        // Prepare SQL query to select all courses where the instructor_id equals the user's id
        $sql = "SELECT COUNT(*) as courseCount FROM courses WHERE instructor_id = ?";

        // Prepare a statement for execution
        if ($stmt = $conn->prepare($sql)) {
            // Bind the user's id to the statement
            $stmt->bind_param("i", $this->properties['user_id']);

            // Execute the statement
            if ($stmt->execute()) {
                // Get the result of the query
                $result = $stmt->get_result();

                // Fetch the data from the result
                $row = $result->fetch_assoc();

                // Return the count of the courses
                return $row['courseCount'];
            } else {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }

    public static function getUserByEmail($email, $conn)
    {
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = new MysqliResultWrapper($stmt->get_result());

        if ($result->num_rows() > 0) {
            $user = $result->fetch_assoc();
            return self::getUserById($user['user_id'], $conn);
        } else {
            return null;
        }
    }


    public static function authenticate($email, $password, $conn)
    {
        // Prepare the SQL query
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);

        // Execute the SQL query
        $stmt->execute();
        $result = $stmt->get_result();

        // If a record is found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // If the password is correct, return the user
                return new User($user);
            } else {
                // If the password is incorrect, return null
                return null;
            }
        } else {
            // If no user is found, return null
            return null;
        }
    }

}
?>

