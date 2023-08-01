<?php
class School {

    private $id;
    private $name;
    private $createdAt;
    private $conn;

    // Constructor
    public function __construct($id, $conn) {
        $this->id = $id;
        $this->conn = $conn;
        $this->setSchoolDetails();
    }

    // Fetch school details from DB and set them
    private function setSchoolDetails()
    {
        if ($this->id === null) {
            // If ID is null, set default values
            $this->name = "Unknown School";
            $this->createdAt = null;
            return;
        }

        $sql = "SELECT * FROM school WHERE school_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $this->id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $this->name = $row['school_name'];
                    $this->createdAt = $row['createdAt'];
                } else {
                    // If school not found, set default values
                    $this->name = "School Not Found";
                    $this->createdAt = null;
                }
            } else {
                throw new \Exception("SQL execution error: " . $this->conn->error);
            }
        } else {
            throw new \Exception("SQL prepare error: " . $this->conn->error);
        }
    }


    // Get school ID
    public function getID() {
        return $this->id;
    }

    // Get school name
    public function getName() {
        return $this->name;
    }

    // Get creation date
    public function getCreatedAt() {
        return $this->createdAt;
    }

    // Static method to get a School by id
    public static function getSchoolById($id, $conn) {
        return new School($id, $conn);
    }

    // Static method to get all schools
    public static function getAllSchools($conn) {
        $sql = "SELECT * FROM school";
        $result = $conn->query($sql);
        $schools = array();

        while ($row = $result->fetch_assoc()) {
            $schools[] = new School($row['school_id'], $conn);
        }

        return $schools;
    }

    public function changeName($newName) {
        $sql = "UPDATE school SET school_name = ? WHERE school_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $newName, $this->id);
            if (!$stmt->execute()) {
                throw new \Exception("SQL execution error: " . $this->conn->error);
            }
            $this->name = $newName;
        } else {
            throw new \Exception("SQL prepare error: " . $this->conn->error);
        }
    }

    public function insertSchool($school_name)
    {
        global $conn;

        $sql = "INSERT INTO school (school_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $school_name);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new \Exception("SQL execution error: " . $stmt->error);
        }
    }

    public static function deleteSchoolById($school_id, $conn)
    {
        $sql = "DELETE FROM school WHERE school_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $school_id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new \Exception("SQL execution error: " . $stmt->error);
        }
    }

}
?>
