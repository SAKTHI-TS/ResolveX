# 🎓 Student Outpass Management System

A digital solution to automate and streamline student outpass requests, approvals, and gate verification using a role-based workflow. The system supports automated notifications, multi-level approval (Class Advisor and HoD), and gate-level verification through RFID or manual methods.

## 🚀 Features

- Student raises outpass requests with reason and duration.
- Class Advisor verifies and approves/rejects the request.
- HoD provides final approval.
- Mentor receives automated email notifications when a student raises a request.
- Student dashboard shows real-time status updates.
- Gate-level verification using:
  - ✅ Manual approval system
  - 📶 RFID card scan (optional integration)
- Role-based login system.

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap, Ajax
- **Backend**: PHP
- **Database**: MySQL 
- **Email Notifications**: PHPMailer 
- **RFID Integration**: Arduino + RFID Reader (Optional)

## 🔐 Sample Login Credentials

| Role           | Username       | Password     |
|----------------|----------------|--------------|
| Student        | 22bcb028       | 12345        |
| Class Advisor  | 112233         | 123456       |
| HoD            | 77777          | 123456       |


> 🔒 **Note**: These are demo credentials for testing purposes only. Replace with secured credentials in production.


## 🔧 Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/Madhan7708/Smart_OutPass_Generator.git
   cd Smart_OutPass_Generator
   ```

2. Import the SQL file into your database:
   - Go to `phpMyAdmin` or use CLI.
   - Import `outpass.sql`.






