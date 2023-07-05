<?php

class Report
{
    protected $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Create Report
    public function createReport($feedback_id, $user_id, $reason_option, $reason_text)
    {
        $sql = "INSERT INTO report (feedback_id, user_id, reason_option, reason_text) VALUES (:feedback_id, :user_id, :reason_option, :reason_text)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['feedback_id' => $feedback_id, 'user_id' => $user_id, 'reason_option' => $reason_option, 'reason_text' => $reason_text]);
    }

    //Get Report by ID
    public function getReport($report_id)
    {
        $sql = "SELECT * FROM report WHERE report_id = :report_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['report_id' => $report_id]);
        return $stmt->fetch();
    }

    //Get all Reports
    public function getAllReports()
    {
        $sql = "SELECT * FROM report";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    //Update Report
    public function updateReport($report_id, $feedback_id, $user_id, $reason_option, $reason_text)
    {
        $sql = "UPDATE report SET feedback_id = :feedback_id, user_id = :user_id, reason_option = :reason_option, reason_text = :reason_text WHERE report_id = :report_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['feedback_id' => $feedback_id, 'user_id' => $user_id, 'reason_option' => $reason_option, 'reason_text' => $reason_text, 'report_id' => $report_id]);
    }

    //Delete Report
    public function deleteReport($report_id)
    {
        $sql = "DELETE FROM report WHERE report_id = :report_id";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(['report_id' => $report_id])) {
            return true;
        } else {
            echo "Error: " . $stmt->errorInfo()[2]; // print the error
            return false;
        }
    }

    public static function deleteReportsByUserId($user_id)
    {
        $conn = $GLOBALS['conn']; // assuming a global $conn
        $sql = "DELETE FROM report WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            if (!$stmt->execute()) {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }

    //Get all reports by a specific user
    public static function getReportsByUserId($user_id)
    {
        $conn = $GLOBALS['conn']; // assuming a global $conn
        $sql = "SELECT * FROM report WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $reports = [];
                while ($row = $result->fetch_assoc()) {
                    $reports[] = $row;
                }
                return $reports;
            } else {
                throw new \Exception("SQL execution error: " . $conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $conn->error);
        }
    }

    public function deleteReportAndFeedback($report_id)
    {

    }


    // Get the feedback ID associated with the report
// Get the feedback ID associated with the report
    private function getFeedbackIdByReportId($report_id)
    {
        $sql = "SELECT feedback_id FROM report WHERE report_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$report_id]);
        $result = $stmt->fetch();

        if ($result && isset($result['feedback_id'])) {
            return $result['feedback_id'];
        }

        return null; // or handle the case when no feedback_id is found
    }

    // Fetch all reports with additional user info
    public function getAllReportsWithUserDetails()
    {
        $sql = "SELECT Report.*, Feedback.*, Users.first_name AS reported_first_name, Users.last_name AS reported_last_name, Reporter.first_name AS reporter_first_name, Reporter.last_name AS reporter_last_name FROM Report JOIN Feedback ON Report.feedback_id = Feedback.feedback_id JOIN Users ON Feedback.user_id = Users.user_id JOIN Users AS Reporter ON Report.user_id = Reporter.user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        return $reports;
    }

}

?>
