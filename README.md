# CourseFeedbackWeb

CourseFeedbackWeb is an innovative platform designed to bridge users and the database, focusing primarily on streamlining the process of accessing and providing course feedback. Developed using PHP, HTML/CSS, JavaScript (with AJAX), and MySQL, it guarantees a seamless and effective user experience. Bootstrap and Google API further enhance the UI design and user authentication respectively.

![image](https://github.com/huygiatrng/CourseFeedbackWeb/assets/67343196/78a5e112-6cef-415c-82f6-3d64f1cc4ed9)

## Features 🌟

![image](https://github.com/huygiatrng/CourseFeedbackWeb/assets/67343196/8d187852-cb40-4d04-8fd8-bbae51df19a2) 

1. **Users**:
    - Caters to general users (students, instructors) and administrators.
    - Allows affiliations with individual schools.

2. **Course Feedback Website**:
    - Efficient search engine for course search (by semester, year, course name, CRN, instructor).
    - View and provide feedback on courses.

3. **Privacy Module**:
    - Enables anonymous feedback submission.
    - Safeguards against misuse of the anonymity feature.

4. **Course Database**:
    - Detailed info about courses across different schools.
    - Auto-generated long title from course code.

5. **Feedback Database**:
    - Repository for all user feedback.
    - Structured data format for efficient analysis.

6. **Feedback Module**:
    - Likert scale questionnaire for consistency.
    - Free-form section for additional insights.
    - Report system to ensure feedback integrity.

7. **School Database**:
    - Management of courses and feedback at the school level.

8. **Authentication System**:
    - Integrated with Google API.
    - Secure login with Google credentials.

9. **Report System**:
    - Flag inappropriate feedback.
    - Ensures a respectful feedback environment.

10. **Third-party Services/APIs**:
    - Potential for integration with university databases.
    - Enables social media sharing.

## System Requirements 🖥️

- PHP server environment.
- MySQL database setup.
- Google API credentials for authentication.
- Modern web browsers such as Chrome, Firefox, or Edge.

## Setup and Installation 🛠️

1. Clone the repository:
   ```bash
   git clone github.com/huygiatrng/CourseFeedbackWeb
2. Navigate to the project folder:
   ```bash
   cd CourseFeedbackWeb
3. Set up the database:
    - Create a MySQL database named [coursefeedback].
    - Import the provided .sql file to set up the tables.

![image](https://github.com/huygiatrng/CourseFeedbackWeb/assets/67343196/a2f12b7a-de58-4091-9f75-01cb5b018d04)
    
4. Configure the Google API:
    - Visit [Google Developer Console](https://console.cloud.google.com/apis/dashboard) and set up an OAuth 2.0 credential.
    - Update the project's authentication configuration with the acquired credentials.
5. Start the server and access the platform on your preferred browser.

## Contribute 🤝

Contributions, issues, and feature requests are welcome! 

## License 📄

This project is licensed under MIT - see the LICENSE.md file for details.

## Acknowledgments 🙏

 - [Bootstrap](https://getbootstrap.com/)
 - [Google API](https://developers.google.com/)

