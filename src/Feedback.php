<?php

require_once 'School.php';
require_once 'Course.php';
require_once 'Report.php';


class Feedback
{
    private $feedback_id;
    private $course_id;
    private $rating1;
    private $rating2;
    private $rating3;
    private $rating4;
    private $rating5;
    private $rating6;
    private $rating7;
    private $feedback_text;
    private $user_id;
    private $anonymous;
    private $createdAt;

    public function __construct($feedback_id, $course_id, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text, $user_id, $anonymous, $createdAt)
    {
        $this->feedback_id = $feedback_id;
        $this->course_id = $course_id;
        $this->rating1 = $rating1;
        $this->rating2 = $rating2;
        $this->rating3 = $rating3;
        $this->rating4 = $rating4;
        $this->rating5 = $rating5;
        $this->rating6 = $rating6;
        $this->rating7 = $rating7;
        $this->feedback_text = $feedback_text;
        $this->user_id = $user_id;
        $this->anonymous = $anonymous;
        $this->createdAt = $createdAt;
    }


    public function getFeedbackId()
    {
        return $this->feedback_id;
    }

    public static function createFeedback($course_id, $user_id, $is_anonymous, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text)
    {
        global $conn;

        $stmt = $conn->prepare('INSERT INTO Feedback (course_id, user_id, anonymous, rating1, rating2, rating3, rating4, rating5, rating6, rating7, feedback_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param("iiiiiiiiiss", $course_id, $user_id, $is_anonymous, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text);

        if (!$stmt->execute()) {
            throw new \Exception("SQL execution error: " . $conn->error);
        }
        $stmt->close();
    }

    public function getCourseId()
    {
        return $this->course_id;
    }

    public function getRating1()
    {
        return $this->rating1;
    }

    public function getRating2()
    {
        return $this->rating2;
    }

    public function getRating3()
    {
        return $this->rating3;
    }


    public function getRating4()
    {
        return $this->rating4;
    }

    public function getRating5()
    {
        return $this->rating5;
    }

    public function getRating6()
    {
        return $this->rating6;
    }


    public function getRating7()
    {
        return $this->rating7;
    }


    public function getFeedbackText()
    {
        return $this->feedback_text;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function isAnonymous()
    {
        return $this->anonymous;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // get all ratings as an array
    public function getRatings()
    {
        return array($this->rating1, $this->rating2, $this->rating3, $this->rating4, $this->rating5, $this->rating6, $this->rating7);
    }

    // additional useful methods you might need
    public function getAverageRating()
    {
        $ratings = $this->getRatings();
        return array_sum($ratings) / count($ratings);
    }

    public static function calculateAverageRating($feedbackData)
    {
        $ratings = array(
            $feedbackData['rating1'],
            $feedbackData['rating2'],
            $feedbackData['rating3'],
            $feedbackData['rating4'],
            $feedbackData['rating5'],
            $feedbackData['rating6'],
            $feedbackData['rating7']
        );
        return array_sum($ratings) / count($ratings);
    }

    public function getUser()
    {
        // Use User class's getUserById method
        return User::getUserById($this->userId);
    }

    public function getReporterName()
    {
        // Use User class's getFullNameById method
        $user = User::getUserById($this->userId);
        return $user ? $user->getFullName() : null;
    }

    public static function getFeedbacksByUserId($userId)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM feedback WHERE user_id = ?");
        $stmt->bind_param("i", $userId);

        $stmt->execute();
        $result = $stmt->get_result();

        $feedbacks = [];
        while ($data = $result->fetch_assoc()) {
            $feedbacks[] = new Feedback(
                $data['feedback_id'],
                $data['course_id'],
                $data['rating1'],
                $data['rating2'],
                $data['rating3'],
                $data['rating4'],
                $data['rating5'],
                $data['rating6'],
                $data['rating7'],
                $data['feedback_text'],
                $data['user_id'],
                $data['anonymous'],
                $data['createdAt']
            );
        }

        return $feedbacks;
    }


    public static function deleteFeedbacksByUserId($userId)
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM feedback WHERE user_id = ?");
        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) {
            throw new \Exception("SQL execution error: " . $conn->error);
        }
    }

    public static function getAllFeedbacks()
    {
        global $conn;

        $stmt = $conn->prepare("SELECT Feedback.*, Users.first_name, Users.last_name FROM Feedback JOIN Users ON Feedback.user_id = Users.user_id");
        $stmt->execute();
        $result = $stmt->get_result();

        $feedbacks = [];
        while ($data = $result->fetch_assoc()) {
            $feedback = new Feedback(
                $data['feedback_id'],
                $data['course_id'],
                $data['rating1'],
                $data['rating2'],
                $data['rating3'],
                $data['rating4'],
                $data['rating5'],
                $data['rating6'],
                $data['rating7'],
                $data['feedback_text'],
                $data['user_id'],
                $data['anonymous'],
                $data['createdAt']
            );

            $feedback->first_name = $data['first_name'];
            $feedback->last_name = $data['last_name'];

            $feedbacks[] = $feedback;
        }

        return $feedbacks;
    }

    public static function getFeedbackById($feedback_id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM Feedback WHERE feedback_id = ?");
        $stmt->bind_param("i", $feedback_id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        if ($data) {
            $feedback = new Feedback(
                $data['feedback_id'],
                $data['course_id'],
                $data['rating1'],
                $data['rating2'],
                $data['rating3'],
                $data['rating4'],
                $data['rating5'],
                $data['rating6'],
                $data['rating7'],
                $data['feedback_text'],
                $data['user_id'],
                $data['anonymous'],
                $data['createdAt']
            );
            return $feedback;
        }
        return null;
    }

    public static function updateFeedback($feedback_id, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text, $anonymous)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE Feedback SET anonymous = ?, rating1 = ?, rating2 = ?, rating3 = ?, rating4 = ?, rating5 = ?, rating6 = ?, rating7 = ?, feedback_text = ? WHERE feedback_id = ?");
        $stmt->bind_param("iiiiiiiisi", $anonymous, $rating1, $rating2, $rating3, $rating4, $rating5, $rating6, $rating7, $feedback_text, $feedback_id);
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            throw new \Exception("SQL execution error: " . $conn->error);
        }
        $stmt->store_result();
    }

    public static function deleteFeedback($feedback_id)
    {
        global $conn;

        // Prepare the statements
        $stmt1 = $conn->prepare("DELETE FROM report WHERE feedback_id = ?");
        $stmt2 = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");

        // Bind parameters
        $stmt1->bind_param("i", $feedback_id);
        $stmt2->bind_param("i", $feedback_id);

        // Execute queries
        if ($stmt1->execute() && $stmt2->execute()) {
            return true; // Successfully deleted
        } else {
            throw new \Exception("SQL execution error: " . $conn->error);
        }
    }

    public static function deleteFeedbackAndReports($feedback_id)
    {
        global $conn;

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Delete all reports associated with the feedback
            $stmt = $conn->prepare("DELETE FROM Report WHERE feedback_id = ?");
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();

            // Delete the feedback
            $stmt = $conn->prepare("DELETE FROM Feedback WHERE feedback_id = ?");
            $stmt->bind_param("i", $feedback_id);
            $stmt->execute();

            // If we made it this far, commit the changes
            $conn->commit();
        } catch (Exception $e) {
            // An error occurred; rollback the changes
            $conn->rollback();
            throw $e;
        }
    }

}

?>
