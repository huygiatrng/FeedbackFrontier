<?php

require_once 'School.php';
require_once 'User.php';
require_once 'Feedback.php';

class Course
{
    private $course_id;
    private $conn;

    public function __construct($course_id)
    {
        global $conn;

        $this->course_id = $course_id;
        $this->conn = $conn;
    }

    public function getID()
    {
        return $this->course_id;
    }

    public function getInstructorName()
    {
        $query = "SELECT instructor_name FROM courses WHERE course_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['instructor_name'];
    }

    public function getSchool()
    {
        $query = "SELECT school_id FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['school_id'];
    }


    public function getSubject()
    {
        $query = "SELECT course_subject FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['course_subject'];
    }

    public function getCourseNumber()
    {
        $query = "SELECT course_number FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['course_number'];
    }

    public function getCourseName(): string
    {
        $subject = $this->getSubject();
        $number = $this->getCourseNumber();

        return "{$subject} {$number}";
    }

    public function getCourseTitle($course_number, $course_subject)
    {
        $stmt = $this->conn->prepare("SELECT course_title FROM course_titles WHERE course_number = ? AND course_subject = ?");
        $stmt->bind_param("ss", $course_number, $course_subject);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['course_title'];
    }


    public function getCRN()
    {
        $query = "SELECT CRN FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['CRN'];
    }

    public function getSemester()
    {
        $query = "SELECT season FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['season'];
    }

    public function getYear()
    {
        $query = "SELECT year FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['year'];
    }

    public function getCreatedTime()
    {
        $query = "SELECT createdAt FROM courses WHERE course_id = $this->course_id";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();

        return $row['createdAt'];
    }

    public function getFeedbackCount() {
        $query = "SELECT COUNT(feedback_id) as feedback_count FROM feedback WHERE course_id = '".$this->course_id."'";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['feedback_count'];
    }

    public static function addCourse($course_subject, $course_number, $CRN, $school_id, $instructor_name, $season, $year)
    {
        global $conn;

        // Prepare the INSERT statement
        $stmt = $conn->prepare("INSERT INTO Courses (course_subject, course_number, CRN, school_id, instructor_name, season, year) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisisss", $course_subject, $course_number, $CRN, $school_id, $instructor_name, $season, $year);

        // Execute the statement
        if ($stmt->execute()) {
            // Return the Course object of the newly created course
            $new_course_id = $conn->insert_id;
            return new Course($new_course_id);
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    }

    public static function calculateAverageRatingOfCourse($course)
    {
        global $conn;
        // get all feedbacks for this course.
        $course_id = $course["course_id"];
        $query = "SELECT * FROM feedback WHERE course_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            return 'No feedback';
        }

        $totalAverageRating = 0;
        $feedbackCount = 0;
        while ($row = $result->fetch_assoc()) {
            $avgRating = Feedback::calculateAverageRating($row);
            if (!is_numeric($avgRating)) {
                throw new \Exception('Feedback::calculateAverageRating did not return a numeric value. Instead got: ' . var_export($avgRating, true));
            }
            $totalAverageRating += $avgRating;
            $feedbackCount++;
        }

        // Calculate average, round to 2 decimal places, and return as string
        $averageRating = $totalAverageRating / $feedbackCount;
        return number_format($averageRating, 2, '.', '');
    }



    public static function deleteCourse($course_id)
    {
        global $conn;

        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Delete the reports associated with the feedbacks for this course
            $stmt1 = $conn->prepare("DELETE r FROM report r INNER JOIN feedback f ON f.feedback_id = r.feedback_id WHERE f.course_id = ?");
            $stmt1->bind_param("i", $course_id);
            $stmt1->execute();

            // Delete the feedbacks for this course
            $stmt2 = $conn->prepare("DELETE FROM feedback WHERE course_id = ?");
            $stmt2->bind_param("i", $course_id);
            $stmt2->execute();

            // Delete the course
            $stmt3 = $conn->prepare("DELETE FROM Courses WHERE course_id = ?");
            $stmt3->bind_param("i", $course_id);
            $stmt3->execute();

            // If everything is fine, commit the changes
            $conn->commit();
        } catch (Exception $e) {
            // An error occurred; rollback the changes
            $conn->rollback();

            // Throw the exception so it can be caught and handled outside of this method
            throw $e;
        }
    }


}

?>
