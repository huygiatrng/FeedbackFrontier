<?php
use PHPUnit\Framework\TestCase;

class UserFeedbackTest extends TestCase {
    public function testInsertUserFeedbackSuccess() {
        // Mock the necessary dependencies
        $mockConn = $this->getMockBuilder('mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        $mockStmt = $this->getMockBuilder('mysqli_stmt')
            ->disableOriginalConstructor()
            ->getMock();

        // Set up the mock behavior
        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockConn->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        // Call the function
        $result = insertUserFeedback(1, 5, 4, 3, 5, 4, 3, 4, 'Great course!', false);

        // Assert the result
        $this->assertEquals('Success: Feedback inserted.', $result);
    }

    public function testInsertUserFeedbackMissingFields() {
        // Call the function without providing required fields
        $result = insertUserFeedback(1, null, 4, 3, 5, 4, 3, 4, '', false);

        // Assert the result
        $this->assertEquals('Error: Required fields are missing.', $result);
    }

    public function testInsertUserFeedbackAnonymousUserNotFound() {
        // Mock the necessary dependencies
        $mockConn = $this->getMockBuilder('mysqli')
            ->disableOriginalConstructor()
            ->getMock();
        $mockStmt = $this->getMockBuilder('mysqli_stmt')
            ->disableOriginalConstructor()
            ->getMock();
        $mockResult = $this->getMockBuilder('mysqli_result')
            ->disableOriginalConstructor()
            ->getMock();

        // Set up the mock behavior
        $mockResult->expects($this->once())
            ->method('num_rows')
            ->willReturn(0);

        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockStmt->expects($this->once())
            ->method('get_result')
            ->willReturn($mockResult);

        $mockConn->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        // Call the function
        $result = insertUserFeedback(1, 5, 4, 3, 5, 4, 3, 4, 'Great course!', false);

        // Assert the result
        $this->assertEquals('Error: User not found.', $result);
    }
}
?>





<!-- DOCUMENTATION -->

<!-- 
    Test ID                         | TC-UT-01
Purpose of Test                 | Testing the successful insertion of user feedback.
Test Environment                | PHP Unit test environment with a mocked database connection.
Test Steps                      | - Provide valid input values for the user feedback (course_id, ratings, feedback_text, is_anonymous).
                                | - Call the insertUserFeedback function with the provided input.
                                | - Assert that the function returns the success message: 'Success: Feedback inserted.'

Test Input                      | course_id: 1
                                | rating1: 5
                                | rating2: 4
                                | rating3: 3
                                | rating4: 5
                                | rating5: 4
                                | rating6: 3
                                | rating7: 4
                                | feedback_text: 'Great course!'
                                | is_anonymous: false

Expected Result                 | The assertion is true, and the user feedback is successfully inserted into the database.

Likely Problems/Bugs Revealed   | Failure of assertion may indicate an issue with the database connection or the insert query.

---

Test ID                         | TC-UT-02
Purpose of Test                 | Testing the case where some required fields are missing.
Test Environment                | PHP Unit test environment with a mocked database connection.
Test Steps                      | - Provide incomplete input values for the user feedback (missing some required fields).
                                | - Call the insertUserFeedback function with the provided input.
                                | - Assert that the function returns the error message: 'Error: Required fields are missing.'

Test Input                      | course_id: 1
                                | rating1: null
                                | rating2: 4
                                | rating3: 3
                                | rating4: 5
                                | rating5: 4
                                | rating6: 3
                                | rating7: 4
                                | feedback_text: ''
                                | is_anonymous: false

Expected Result                 | The assertion is true, and the function returns the expected error message.

Likely Problems/Bugs Revealed   | Failure of assertion may indicate incorrect handling of missing fields in the insert function.

---

Test ID                         | TC-UT-03
Purpose of Test                 | Testing the case where the author's name is not found for a non-anonymous user.
Test Environment                | PHP Unit test environment with a mocked database connection.
Test Steps                      | - Provide input values for the user feedback with a non-anonymous user.
                                | - Mock the database connection to simulate not finding the author's name.
                                | - Call the insertUserFeedback function with the provided input.
                                | - Assert that the function returns the error message: 'Error: User not found.'

Test Input                      | course_id: 1
                                | rating1: 5
                                | rating2: 4
                                | rating3: 3
                                | rating4: 5
                                | rating5: 4
                                | rating6: 3
                                | rating7: 4
                                | feedback_text: 'Great course!'
                                | is_anonymous: false

Expected Result                 | The assertion is true, and the function returns the expected error message.

Likely Problems/Bugs Revealed   | Failure of assertion may indicate a bug in retrieving the author's name from the database.

 -->








    
<!-- BUG TABLE -->

 <!-- 

Bug                             | The user feedback is not inserted into the database.

The test that uncovered the bug | TC-UT-01

Description of the bug          | The insertUserFeedback function fails to insert the user feedback into the database.

Action was taken to fix the bug | The insertUserFeedback function is updated to ensure proper execution of the insert query and handling of database errors. Additionally, the function is tested with valid and invalid inputs to verify the insertion process.

---

Bug                             | The insertUserFeedback function does not handle missing required fields correctly.

The test that uncovered the bug | TC-UT-02

Description of the bug          | The insertUserFeedback function does not provide appropriate error messages when required fields are missing in the user feedback.

Action was taken to fix the bug | The insertUserFeedback function is updated to include specific validation for required fields and return an error message when they are missing. The function is then tested with incomplete input to ensure proper error handling.

---

Bug                             | The author's name is not retrieved correctly for non-anonymous users.

The test that uncovered the bug | TC-UT-03

Description of the bug          | The insertUserFeedback function fails to retrieve the author's name for non-anonymous users, resulting in incorrect data insertion.

Action was taken to fix the bug | The insertUserFeedback function is updated to properly retrieve the author's name from the database for non-anonymous users. The function is then tested with non-anonymous user input to verify the correct retrieval and insertion.



  -->
