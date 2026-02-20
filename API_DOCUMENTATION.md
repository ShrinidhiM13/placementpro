# PlacementPro - Complete API Documentation

## API Base Path
All endpoints are accessed from: `http://localhost/placementpro/api/`

## Authentication
All endpoints except login and register require Bearer token:
```
Authorization: Bearer {token}
```

Token is obtained from login response and stored in `localStorage.authToken`

---

## Authentication Endpoints

### POST /auth/login.php
Authenticate user and get token

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "role": "STUDENT",
  "name": "John Doe",
  "id": 1,
  "message": "Login successful"
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid email or password"
}
```

---

### POST /auth/register.php
Register new user

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "pass123",
  "phone": "9876543210",
  "role": "STUDENT"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "id": 1
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Email already exists"
}
```

---

## Student API Endpoints

### GET /student/profile.php
Get current student's profile

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "cgpa": 8.5,
    "backlogs": 0,
    "branch": "CSE",
    "imageUrl": "/uploads/images/1.jpg"
  }
}
```

---

### POST /student/profile.php
Update student profile

**Request Body:**
```json
{
  "phone": "9876543210",
  "cgpa": 8.5,
  "backlogs": 0
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully"
}
```

---

### GET /student/skills.php
Get all skills for student

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "skillId": 1,
      "skillName": "Python",
      "skillDescription": "Python Programming"
    }
  ]
}
```

---

### POST /student/skills.php
Add new skill to student

**Request Body:**
```json
{
  "skillName": "Python"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Skill added successfully"
}
```

---

### DELETE /student/skills.php
Remove skill from student

**Query Parameters:**
```
?skillId=1
```

**Response (200):**
```json
{
  "success": true,
  "message": "Skill removed successfully"
}
```

---

### GET /student/projects.php
Get all student projects

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "projectId": 1,
      "title": "E-Commerce Website",
      "description": "Built with React",
      "techStack": "React, Node.js, MongoDB",
      "createdAt": "2024-01-15"
    }
  ]
}
```

---

### POST /student/projects.php
Add new project

**Request Body:**
```json
{
  "title": "E-Commerce Website",
  "description": "Built with React",
  "techStack": "React, Node.js, MongoDB"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Project added successfully"
}
```

---

### DELETE /student/projects.php
Delete project

**Query Parameters:**
```
?projectId=1
```

**Response (200):**
```json
{
  "success": true,
  "message": "Project deleted successfully"
}
```

---

### GET /student/education.php
Get education history

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "educationId": 1,
      "degree": "B.Tech",
      "institute": "ABC University",
      "year": 2024,
      "marks": 85.5
    }
  ]
}
```

---

### POST /student/education.php
Add education record

**Request Body:**
```json
{
  "degree": "B.Tech",
  "institute": "ABC University",
  "year": 2024,
  "marks": 85.5
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Education record added successfully"
}
```

---

### DELETE /student/education.php
Delete education record

**Query Parameters:**
```
?educationId=1
```

**Response (200):**
```json
{
  "success": true,
  "message": "Education record deleted successfully"
}
```

---

### GET /student/certifications.php
Get certifications

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "certId": 1,
      "certName": "AWS Solutions Architect",
      "issuer": "Amazon",
      "year": 2023
    }
  ]
}
```

---

### POST /student/certifications.php
Add certification

**Request Body:**
```json
{
  "certName": "AWS Solutions Architect",
  "issuer": "Amazon",
  "year": 2023
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Certification added successfully"
}
```

---

### DELETE /student/certifications.php
Delete certification

**Query Parameters:**
```
?certificationId=1
```

**Response (200):**
```json
{
  "success": true,
  "message": "Certification deleted successfully"
}
```

---

### GET /student/myApplications.php
Get student's drive applications

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "driveId": 1,
      "companyName": "Tech Corp",
      "jobTitle": "Software Engineer",
      "appliedDate": "2024-03-01",
      "status": "SELECTED"
    }
  ]
}
```

---

### GET /student/applicationStatus.php
Get application status (single or all)

**Query Parameters (Optional):**
```
?applicationId=1  (for single application)
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "applicationId": 1,
      "status": "CLEARED",
      "updatedAt": "2024-03-10",
      "remarks": "Passed technical round"
    }
  ]
}
```

---

### POST /student/applyDrive.php
Apply to placement drive

**Request Body:**
```json
{
  "driveId": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Applied to drive successfully"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Your CGPA/Backlogs don't meet criteria"
}
```

---

### GET /student/listInterviewSlots.php
List interview slots for applied drives

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "slotId": 1,
      "driveId": 1,
      "companyName": "Tech Corp",
      "startTime": "2024-03-15 10:00:00",
      "endTime": "2024-03-15 11:00:00",
      "room": "Conference A",
      "status": "AVAILABLE"
    }
  ]
}
```

---

### POST /student/bookInterviewSlot.php
Book interview slot

**Request Body:**
```json
{
  "slotId": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Interview slot booked successfully"
}
```

---

### GET /student/viewMentorSlots.php
Get available mentor slots

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "slotId": 1,
      "mentorName": "Jane Smith",
      "startTime": "2024-03-20 15:00:00",
      "endTime": "2024-03-20 16:00:00",
      "isBooked": false
    }
  ]
}
```

---

### POST /student/bookMentorSlot.php
Book mentor slot

**Request Body:**
```json
{
  "slotId": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Mentor slot booked successfully"
}
```

---

### GET /student/getJobPosts.php
Get all job posts from alumni

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "postId": 1,
      "companyName": "Tech Corp",
      "jobTitle": "Senior Developer",
      "description": "We are hiring...",
      "alumniName": "Alumni Name",
      "alumniPhone": "9876543210"
    }
  ]
}
```

---

### GET /student/skillGapAnalysis.php
Get skill gap analysis

**Response (200):**
```json
{
  "success": true,
  "data": {
    "studentSkills": ["Python", "Java"],
    "placedStudentSkills": ["Python", "Java", "React", "AWS"],
    "missingSkills": ["React", "AWS"],
    "recommendation": "Focus on learning React and AWS"
  }
}
```

---

### GET /student/generateResume.php
Generate PDF resume

**Response (200):**
```json
{
  "success": true,
  "message": "Resume generated successfully",
  "resumeUrl": "/uploads/resumes/1_resume.pdf"
}
```

---

### GET /student/getNotifications.php
Get user notifications

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "notificationId": 1,
      "message": "Your application was selected",
      "type": "PLACEMENT",
      "createdAt": "2024-03-15 10:30:00",
      "isRead": false
    }
  ]
}
```

---

## Alumni API Endpoints

### POST /alumni/postJob.php
Post new job opportunity

**Request Body:**
```json
{
  "companyName": "Tech Corp",
  "jobTitle": "Senior Developer",
  "description": "We are looking for experienced developers",
  "salary": "15-20 LPA",
  "location": "Bangalore"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Job posted successfully"
}
```

---

### POST /alumni/createMentorSlot.php
Create mentoring slot

**Request Body:**
```json
{
  "startTime": "2024-03-20 15:00:00",
  "endTime": "2024-03-20 16:00:00"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Mentor slot created successfully"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Time slot overlaps with existing slot"
}
```

---

### GET /alumni/viewMentorSlots.php
Get all mentor slots created by alumni

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "slotId": 1,
      "startTime": "2024-03-20 15:00:00",
      "endTime": "2024-03-20 16:00:00",
      "isBooked": false
    }
  ]
}
```

---

## TPO API Endpoints

### POST /tpo/createDrive.php
Create new placement drive

**Request Body:**
```json
{
  "companyName": "Tech Corp",
  "jobTitle": "Software Engineer",
  "minCGPA": 7.0,
  "maxBacklogs": 2,
  "imageUrl": "/uploads/images/techcorp.jpg",
  "status": "OPEN"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Drive created successfully",
  "driveId": 1
}
```

---

### POST /tpo/updateDrive.php
Update drive details

**Request Body:**
```json
{
  "driveId": 1,
  "companyName": "Tech Corp",
  "jobTitle": "Software Engineer",
  "minCGPA": 7.5,
  "maxBacklogs": 1,
  "status": "CLOSED"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Drive updated successfully"
}
```

---

### POST /tpo/deleteDrive.php
Delete drive

**Request Body:**
```json
{
  "driveId": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Drive deleted successfully"
}
```

---

### GET /tpo/viewApplications.php
Get all applications (with optional drive filter)

**Query Parameters (Optional):**
```
?driveId=1
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "applicationId": 1,
      "studentName": "John Doe",
      "email": "john@example.com",
      "driveId": 1,
      "companyName": "Tech Corp",
      "appliedDate": "2024-03-01",
      "status": "SELECTED"
    }
  ]
}
```

---

### POST /tpo/updateApplicationStatus.php
Update application status

**Request Body:**
```json
{
  "applicationId": 1,
  "status": "SELECTED",
  "remarks": "Offer extended"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Application status updated"
}
```

---

### GET /tpo/eligibleStudents.php
Get all eligible students (unplaced)

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "studentId": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "cgpa": 8.5,
      "backlogs": 0,
      "branch": "CSE"
    }
  ]
}
```

---

### POST /tpo/notifyEligibleStudents.php
Send notification to student

**Request Body:**
```json
{
  "studentId": 1,
  "message": "Drive for Tech Corp is live. Apply now!"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Notification sent successfully"
}
```

---

### POST /tpo/scheduleInterview.php
Schedule interview slot

**Request Body:**
```json
{
  "driveId": 1,
  "startTime": "2024-03-15 10:00:00",
  "endTime": "2024-03-15 11:00:00",
  "room": "Conference Room A"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Interview scheduled successfully"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Time slot conflicts with existing interview"
}
```

---

### GET /tpo/placementStats.php
Get placement statistics

**Response (200):**
```json
{
  "success": true,
  "data": {
    "totalStudents": 100,
    "placedStudents": 85,
    "unplacedStudents": 15,
    "placementPercentage": 85.0,
    "highestPackage": 50.0,
    "averagePackage": 12.5,
    "topCompanies": ["Tech Corp", "Software Inc", "Digital Services"]
  }
}
```

---

## Common API Endpoints

### GET /common/drives.php
Get all open drives

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "driveId": 1,
      "companyName": "Tech Corp",
      "jobTitle": "Software Engineer",
      "minCGPA": 7.0,
      "maxBacklogs": 2,
      "imageUrl": "/uploads/images/techcorp.jpg",
      "status": "OPEN"
    }
  ]
}
```

---

### GET /common/branches.php
Get all branches

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "branchId": 1,
      "branchName": "CSE",
      "branchDescription": "Computer Science and Engineering"
    }
  ]
}
```

---

### GET /common/companies.php
Get all companies

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "companyId": 1,
      "companyName": "Tech Corp",
      "industry": "IT"
    }
  ]
}
```

---

### POST /common/changePassword.php
Change user password

**Request Body:**
```json
{
  "oldPassword": "oldpass123",
  "newPassword": "newpass123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Old password is incorrect"
}
```

---

## HTTP Status Codes

- **200 OK** - Request successful
- **201 Created** - Resource created successfully
- **400 Bad Request** - Invalid input
- **401 Unauthorized** - Missing or invalid token
- **403 Forbidden** - Access denied
- **404 Not Found** - Resource not found
- **500 Internal Server Error** - Server error

---

## Error Response Format

All error responses follow this format:
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## Rate Limiting

Currently no rate limiting implemented. Can be added in middleware.

---

## CORS

CORS is not applicable as frontend and backend are on same origin.

---

## Version

API Version: 1.0
Last Updated: February 2026

---

**For more information, refer to the source code comments in each API file.**
