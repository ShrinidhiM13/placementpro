# PlacementPro - Quick Start Guide

## 🚀 Getting Started

### Prerequisites
- PHP 7.2 or higher
- MySQL/MariaDB
- Apache with mod_rewrite enabled
- XAMPP (recommended)

### Setup Steps

#### 1. Database Setup
```bash
# Import the SQL file
mysql -u root -p < u508697926_form_db.sql
```

#### 2. Configuration
Check and update `config/database.php`:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u508697926_form_db";
```

#### 3. File Permissions
Create required directories with proper permissions:
```bash
mkdir -p uploads/resumes
mkdir -p uploads/images
mkdir -p logs
chmod -R 755 uploads
chmod -R 755 logs
```

#### 4. Access Application
```
http://localhost/placementpro
```

---

## 👤 Test Accounts

### Student Account
- Email: student@example.com
- Password: student123
- Role: STUDENT

### Alumni Account
- Email: alumni@example.com
- Password: alumni123
- Role: ALUMNI

### TPO Account
- Email: tpo@example.com
- Password: tpo123
- Role: TPO

**Note**: Create test accounts via registration page or directly in database

---

## 📋 Database Tables

### Core Tables
- `users` - Authentication and basic user info
- `students` - Student specific data
- `alumni` - Alumni specific data

### Placement Tables
- `drives` - Placement drives
- `applications` - Student applications
- `application_status` - Status tracking
- `interview_slots` - Interview scheduling
- `placements` - Final placements

### Student Info Tables
- `student_skills` - Skills mapping
- `projects` - Projects/portfolio
- `education` - Education history
- `certifications` - Certifications

### Alumni Tables
- `job_posts` - Job postings
- `mentor_slots` - Mentoring sessions

### Support Tables
- `branches` - Branches/departments
- `companies` - Companies list
- `job_roles` - Job descriptions
- `notifications` - Event notifications

---

## 🔗 API Workflow Examples

### Student Login & View Drives
```javascript
// Step 1: Login
const loginResponse = await fetch('/api/auth/login.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email: 'student@test.com', password: 'pass123' })
});
const { token, role, name } = await loginResponse.json();
localStorage.setItem('authToken', token);
localStorage.setItem('userRole', role);

// Step 2: Fetch Drives
const drivesResponse = await fetch('/api/common/drives.php', {
  headers: { 'Authorization': `Bearer ${token}` }
});
const drives = await drivesResponse.json();

// Step 3: Apply to Drive
await fetch('/api/student/applyDrive.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({ driveId: 1 })
});
```

### TPO Create Drive & Schedule Interview
```javascript
// Create Drive
const driveResponse = await fetch('/api/tpo/createDrive.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${tpoToken}`
  },
  body: JSON.stringify({
    companyName: 'Tech Corp',
    jobTitle: 'Software Engineer',
    minCGPA: 7.0,
    maxBacklogs: 2,
    status: 'OPEN'
  })
});

// Schedule Interview
await fetch('/api/tpo/scheduleInterview.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${tpoToken}`
  },
  body: JSON.stringify({
    driveId: 1,
    startTime: '2024-03-15 10:00:00',
    endTime: '2024-03-15 11:00:00',
    room: 'Conference Room A'
  })
});
```

### Alumni Create Mentor Slot
```javascript
await fetch('/api/alumni/createMentorSlot.php', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${alumniToken}`
  },
  body: JSON.stringify({
    startTime: '2024-03-20 15:00:00',
    endTime: '2024-03-20 16:00:00'
  })
});
```

---

## 🛠️ Common Tasks

### Add New Student Skill
1. Login as Student
2. Go to "Skills" section
3. Enter skill name (e.g., "Python", "React")
4. Click "Add Skill"

### Create New Drive
1. Login as TPO
2. Go to "Create Drive"
3. Fill in company name, job title
4. Set minimum CGPA and maximum backlogs
5. Click "Create Drive"

### Apply to Drive
1. Login as Student
2. Go to "Open Drives"
3. View drive details
4. Click "Apply" button
5. Check status in "My Applications"

### Generate Resume
1. Login as Student
2. Go to "Generate Resume"
3. Click "Generate PDF"
4. Download generated resume

### Schedule Interview
1. Login as TPO
2. Go to "Schedule Interview"
3. Select drive and time
4. Set interview room
5. Submit

---

## 🔍 File Structure
```
placementpro/
├── public/
│   ├── assets/
│   │   ├── app.js (Main JavaScript)
│   │   ├── style.css (Styling)
│   │   └── ...
│   ├── index.html (Login/Register)
│   └── dashboard.html (Main interface)
├── api/
│   ├── student/ (Student endpoints)
│   ├── alumni/ (Alumni endpoints)
│   ├── tpo/ (TPO endpoints)
│   ├── auth/ (Authentication)
│   └── common/ (Shared endpoints)
├── config/
│   ├── database.php
│   └── middleware.php
├── uploads/
│   ├── resumes/ (Generated PDFs)
│   └── images/ (User images)
├── logs/
├── config.php
├── middleware.php
└── IMPLEMENTATION_COMPLETE.md
```

---

## 🐛 Troubleshooting

### Issue: Login not working
- Check database connection in `config/database.php`
- Verify user exists in `users` table
- Check console for error messages

### Issue: API returns 401 Unauthorized
- Token expired - login again
- Token not in Authorization header with "Bearer" prefix
- Check middleware.php for token validation

### Issue: Cannot create folder error with Resume
- Create `uploads/resumes/` directory manually
- Set write permissions: `chmod 755 uploads/resumes`

### Issue: Database connection failed
- Check MySQL is running
- Verify database credentials
- Check database name matches

### Issue: Images not uploading
- Verify `uploads/` directory exists
- Check file permissions (at least 755)
- Check file size limits in PHP

---

## 📱 Mobile Friendly Notes

The application is responsive and works on:
- Desktop browsers (Chrome, Firefox, Safari, Edge)
- Tablet browsers
- Mobile browsers (iOS Safari, Chrome Mobile)

### Viewport
User interface automatically scales for different screen sizes.

---

## 📚 Database Schema Overview

### Users Table
```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role ENUM('STUDENT','ALUMNI','TPO'),
  name VARCHAR(100),
  phone VARCHAR(10),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Students Table
```sql
CREATE TABLE students (
  id INT PRIMARY KEY,
  userId INT,
  branchId INT,
  cgpa DECIMAL(3,2),
  backlogs INT,
  imageUrl VARCHAR(255),
  FOREIGN KEY (userId) REFERENCES users(id)
);
```

### Drives Table
```sql
CREATE TABLE drives (
  id INT PRIMARY KEY AUTO_INCREMENT,
  companyName VARCHAR(100),
  jobTitle VARCHAR(100),
  minCGPA DECIMAL(3,2),
  maxBacklogs INT,
  imageUrl VARCHAR(255),
  status ENUM('OPEN','CLOSED'),
  createdAt TIMESTAMP
);
```

---

## ⚙️ Configuration

### PHP Settings (recommended)
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 128M
```

### Apache .htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.html [QSA,L]
</IfModule>
```

---

## 🔑 Important Notes

1. **Security**: In production, use HTTPS
2. **Tokens**: Default token expiration is not set - add in middleware
3. **Passwords**: Always hash with bcrypt or similar
4. **Database**: Regular backups recommended
5. **Logs**: Monitor logs directory for errors
6. **CORS**: Not applicable (same origin)

---

## 📞 Support Resources

- Check API endpoint comments for request/response format
- Review SQL schema for data validation rules
- Check JavaScript console for client-side errors
- Check PHP error logs for server-side issues

---

**Happy Placement Season! 🎉**
