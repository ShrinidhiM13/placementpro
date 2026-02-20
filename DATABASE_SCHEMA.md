# PlacementPro - Database Schema Documentation

## Complete Database Structure

All tables are in the database: `u508697926_form_db`

---

## Core User Tables

### users
Stores all user authentication and basic profile information for all three roles: Student, Alumni, and TPO.

```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('STUDENT', 'ALUMNI', 'TPO') NOT NULL,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(10),
  image_url VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| email | VARCHAR(255) | Unique email for login |
| password | VARCHAR(255) | Hashed password using bcrypt |
| role | ENUM | User type: STUDENT, ALUMNI, or TPO |
| name | VARCHAR(100) | Full name of user |
| phone | VARCHAR(10) | Contact number |
| image_url | VARCHAR(255) | Profile picture URL |
| created_at | TIMESTAMP | Account creation time |
| updated_at | TIMESTAMP | Last update time |

**Relationships:**
- One user has one student profile (if role = STUDENT)
- One user has one alumni profile (if role = ALUMNI)
- One user is TPO admin (if role = TPO)

---

### students
Stores student-specific information extending the users table.

```sql
CREATE TABLE students (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL UNIQUE,
  branch_id INT,
  cgpa DECIMAL(3,2),
  backlogs INT DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (branch_id) REFERENCES branches(id)
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | Foreign key to users table |
| branch_id | INT | Student's branch/department |
| cgpa | DECIMAL(3,2) | Cumulative GPA (e.g., 8.50) |
| backlogs | INT | Number of pending backlogs |

**Key Relationships:**
- Foreign Key to `users(id)` - Links to user account
- Foreign Key to `branches(id)` - Links to student's branch

---

### alumni
Stores alumni-specific information extending the users table.

```sql
CREATE TABLE alumni (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL UNIQUE,
  batch_year INT,
  company_name VARCHAR(100),
  designation VARCHAR(100),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | Foreign key to users table |
| batch_year | INT | Year of graduation |
| company_name | VARCHAR(100) | Current company |
| designation | VARCHAR(100) | Job title |

**Key Relationships:**
- Foreign Key to `users(id)` - Links to user account

---

## Placement Drive Tables

### drives
Stores all placement drives (job opportunities) created by TPO.

```sql
CREATE TABLE drives (
  id INT PRIMARY KEY AUTO_INCREMENT,
  company_id INT,
  job_title VARCHAR(100),
  min_cgpa DECIMAL(3,2),
  max_backlogs INT DEFAULT 0,
  image_url VARCHAR(255),
  status ENUM('OPEN', 'CLOSED') DEFAULT 'OPEN',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| company_id | INT | Foreign key to companies |
| job_title | VARCHAR(100) | Job position name |
| min_cgpa | DECIMAL(3,2) | Minimum CGPA requirement |
| max_backlogs | INT | Maximum allowed backlogs |
| image_url | VARCHAR(255) | Company logo URL |
| status | ENUM | OPEN or CLOSED for applications |
| created_at | TIMESTAMP | Drive creation date |
| updated_at | TIMESTAMP | Last modification date |

**Key Relationships:**
- Foreign Key to `companies(id)` - Links to company details

---

### applications
Stores student applications to placement drives.

```sql
CREATE TABLE applications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  drive_id INT NOT NULL,
  applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_application (student_id, drive_id),
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (drive_id) REFERENCES drives(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| drive_id | INT | Foreign key to drive |
| applied_date | TIMESTAMP | Application submission time |

**Key Relationships:**
- Foreign Key to `students(id)` - Links to applying student
- Foreign Key to `drives(id)` - Links to job drive

---

### application_status
Tracks the status updates of student applications through the selection process.

```sql
CREATE TABLE application_status (
  id INT PRIMARY KEY AUTO_INCREMENT,
  application_id INT NOT NULL,
  status ENUM('APPLIED', 'APTITUDE', 'CLEARED', 'INTERVIEW_SCHEDULED', 'SELECTED', 'REJECTED') DEFAULT 'APPLIED',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  remarks TEXT,
  FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| application_id | INT | Foreign key to application |
| status | ENUM | Current status in selection pipeline |
| updated_at | TIMESTAMP | Status update time |
| remarks | TEXT | Comments/feedback from TPO |

**Status Pipeline:**
1. APPLIED - Application submitted
2. APTITUDE - Awaiting aptitude test results
3. CLEARED - Aptitude passed
4. INTERVIEW_SCHEDULED - Interview scheduled
5. SELECTED - Offer extended
6. REJECTED - Application rejected

**Key Relationships:**
- Foreign Key to `applications(id)` - Links to application

---

### interview_slots
Stores interview time slots scheduled by TPO for specific drives.

```sql
CREATE TABLE interview_slots (
  id INT PRIMARY KEY AUTO_INCREMENT,
  drive_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  room VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (drive_id) REFERENCES drives(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| drive_id | INT | Foreign key to drive |
| start_time | DATETIME | Interview start time |
| end_time | DATETIME | Interview end time |
| room | VARCHAR(100) | Interview location/room |
| created_at | TIMESTAMP | Slot creation time |

**Key Relationships:**
- Foreign Key to `drives(id)` - Links to related drive

---

### placements
Stores final placement records when student is selected.

```sql
CREATE TABLE placements (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  company_id INT,
  job_title VARCHAR(100),
  salary DECIMAL(10,2),
  status ENUM('PLACED', 'UNPLACED') DEFAULT 'UNPLACED',
  placement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (company_id) REFERENCES companies(id)
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| company_id | INT | Foreign key to company |
| job_title | VARCHAR(100) | Position offered |
| salary | DECIMAL(10,2) | Package offered in LPA |
| status | ENUM | PLACED or UNPLACED |
| placement_date | TIMESTAMP | Placement confirmation date |

**Key Relationships:**
- Foreign Key to `students(id)` - Links to placed student
- Foreign Key to `companies(id)` - Links to company

---

## Student Profile Tables

### student_skills
Junction table linking students to skills they possess.

```sql
CREATE TABLE student_skills (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  skill_id INT NOT NULL,
  proficiency ENUM('BEGINNER', 'INTERMEDIATE', 'ADVANCED') DEFAULT 'INTERMEDIATE',
  years_exp INT DEFAULT 1,
  UNIQUE KEY unique_student_skill (student_id, skill_id),
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| skill_id | INT | Foreign key to skill |
| proficiency | ENUM | Proficiency level |
| years_exp | INT | Years of experience |

**Key Relationships:**
- Foreign Key to `students(id)`
- Foreign Key to `skills(id)`

---

### skills
Stores all available skills that can be added by students.

```sql
CREATE TABLE skills (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  category VARCHAR(50)
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(100) | Skill name (e.g., "Python") |
| description | TEXT | Skill description |
| category | VARCHAR(50) | Category (Programming, Database, etc.) |

---

### projects
Stores student projects and portfolio items.

```sql
CREATE TABLE projects (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  tech_stack VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| title | VARCHAR(150) | Project name |
| description | TEXT | Project details |
| tech_stack | VARCHAR(255) | Technologies used |
| created_at | TIMESTAMP | Creation date |
| updated_at | TIMESTAMP | Last update date |

**Key Relationships:**
- Foreign Key to `students(id)`

---

### education
Stores educational qualifications of students.

```sql
CREATE TABLE education (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  degree VARCHAR(100),
  institute VARCHAR(150),
  year INT,
  marks DECIMAL(5,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| degree | VARCHAR(100) | Degree name (B.Tech, M.Tech, etc.) |
| institute | VARCHAR(150) | Educational institution |
| year | INT | Year of passing |
| marks | DECIMAL(5,2) | Percentage or CGPA |
| created_at | TIMESTAMP | Record creation date |

**Key Relationships:**
- Foreign Key to `students(id)`

---

### certifications
Stores professional certifications obtained by students.

```sql
CREATE TABLE certifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_id INT NOT NULL,
  cert_name VARCHAR(150),
  issuer VARCHAR(100),
  year INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| student_id | INT | Foreign key to student |
| cert_name | VARCHAR(150) | Certification name |
| issuer | VARCHAR(100) | Issuing organization |
| year | INT | Year obtained |
| created_at | TIMESTAMP | Record creation date |

**Key Relationships:**
- Foreign Key to `students(id)`

---

## Mentoring & Networking Tables

### mentor_slots
Stores mentoring session slots created by alumni.

```sql
CREATE TABLE mentor_slots (
  id INT PRIMARY KEY AUTO_INCREMENT,
  alumni_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  is_booked BOOLEAN DEFAULT FALSE,
  booked_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (alumni_id) REFERENCES alumni(id) ON DELETE CASCADE,
  FOREIGN KEY (booked_by) REFERENCES students(id)
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| alumni_id | INT | Foreign key to alumni |
| start_time | DATETIME | Slot start time |
| end_time | DATETIME | Slot end time |
| is_booked | BOOLEAN | Booking status |
| booked_by | INT | Foreign key to booking student |
| created_at | TIMESTAMP | Slot creation date |

**Key Relationships:**
- Foreign Key to `alumni(id)`
- Foreign Key to `students(id)`

---

### job_posts
Stores job opportunities posted by alumni.

```sql
CREATE TABLE job_posts (
  id INT PRIMARY KEY AUTO_INCREMENT,
  alumni_id INT NOT NULL,
  company_name VARCHAR(150),
  job_title VARCHAR(100),
  description TEXT,
  salary VARCHAR(100),
  location VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (alumni_id) REFERENCES alumni(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| alumni_id | INT | Foreign key to alumni |
| company_name | VARCHAR(150) | Company name |
| job_title | VARCHAR(100) | Job position |
| description | TEXT | Job details |
| salary | VARCHAR(100) | Salary range |
| location | VARCHAR(100) | Work location |
| created_at | TIMESTAMP | Post creation date |

**Key Relationships:**
- Foreign Key to `alumni(id)`

---

## Support Tables

### branches
Stores academic branches/departments.

```sql
CREATE TABLE branches (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) UNIQUE NOT NULL,
  description VARCHAR(255)
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(50) | Branch name (CSE, ECE, ME, etc.) |
| description | VARCHAR(255) | Branch description |

**Example Data:**
- CSE - Computer Science and Engineering
- ECE - Electronics and Communication Engineering
- ME - Mechanical Engineering
- CE - Civil Engineering

---

### companies
Stores company information for placements and recruitment.

```sql
CREATE TABLE companies (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(150) UNIQUE NOT NULL,
  industry VARCHAR(100),
  website VARCHAR(255),
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(150) | Company name |
| industry | VARCHAR(100) | Industry type |
| website | VARCHAR(255) | Company website |
| description | TEXT | Company details |
| created_at | TIMESTAMP | Record creation date |

---

### job_roles
Stores job role/position templates.

```sql
CREATE TABLE job_roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100),
  description TEXT,
  skills_required VARCHAR(255),
  experience_required INT
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| title | VARCHAR(100) | Job title |
| description | TEXT | Role description |
| skills_required | VARCHAR(255) | Required skills |
| experience_required | INT | Years of experience |

---

## Notification & Communication Tables

### notifications
Stores all system notifications for users.

```sql
CREATE TABLE notifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  message TEXT NOT NULL,
  type ENUM('PLACEMENT', 'DRIVE', 'INTERVIEW', 'MENTORING', 'GENERAL') DEFAULT 'GENERAL',
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| user_id | INT | Foreign key to user |
| message | TEXT | Notification content |
| type | ENUM | Notification category |
| is_read | BOOLEAN | Read status |
| created_at | TIMESTAMP | Notification time |

**Key Relationships:**
- Foreign Key to `users(id)`

---

## Data Relationships & ER Diagram

```
users
├── students (1:1)
│   ├── applications (1:N)
│   │   ├── application_status (1:N)
│   │   └── drive (N:1)
│   ├── student_skills (M:N)
│   │   └── skills
│   ├── projects (1:N)
│   ├── education (1:N)
│   ├── certifications (1:N)
│   └── mentor_slots (1:N)
├── alumni (1:1)
│   ├── mentor_slots (1:N)
│   └── job_posts (1:N)
└── notifications (1:N)

drives (N:1)
├── companies
└── interview_slots (1:N)

placements
├── students (N:1)
└── companies (N:1)

branches
└── students (N:1)
```

---

## Sample Queries

### Get all eligible students for a drive
```sql
SELECT s.* FROM students s
JOIN users u ON s.user_id = u.id
WHERE s.cgpa >= (SELECT min_cgpa FROM drives WHERE id = ?)
AND s.backlogs <= (SELECT max_backlogs FROM drives WHERE id = ?)
AND NOT EXISTS (
  SELECT 1 FROM placements p WHERE p.student_id = s.id AND p.status = 'PLACED'
);
```

### Get student's applications with status
```sql
SELECT a.*, d.company_id, c.name as company_name, app_status.status
FROM applications a
JOIN drives d ON a.drive_id = d.id
JOIN companies c ON d.company_id = c.id
LEFT JOIN application_status app_status ON a.id = app_status.application_id
WHERE a.student_id = ?
ORDER BY a.applied_date DESC;
```

### Get interview slots for student's applied drives
```sql
SELECT ist.* FROM interview_slots ist
JOIN drives d ON ist.drive_id = d.id
JOIN applications a ON a.drive_id = d.id
WHERE a.student_id = ? AND ist.start_time > NOW();
```

### Get placement statistics
```sql
SELECT 
  COUNT(DISTINCT s.id) as total_students,
  COUNT(DISTINCT p.id) as placed_students,
  COUNT(DISTINCT s.id) - COUNT(DISTINCT p.id) as unplaced_students,
  (COUNT(DISTINCT p.id) * 100 / COUNT(DISTINCT s.id)) as placement_percentage,
  MAX(p.salary) as highest_package,
  AVG(p.salary) as average_package
FROM students s
LEFT JOIN placements p ON s.id = p.student_id AND p.status = 'PLACED';
```

---

## Constraints & Rules

1. **One-to-One Relationships:**
   - One user has one student profile (if role = STUDENT)
   - One user has one alumni profile (if role = ALUMNI)

2. **Unique Constraints:**
   - Email must be unique across users
   - Student can apply to same drive only once

3. **Cascading Deletes:**
   - Deleting user cascades to student/alumni profiles
   - Deleting student cascades to all related records

4. **Enumerations:**
   - User roles: STUDENT, ALUMNI, TPO
   - Drive status: OPEN, CLOSED
   - Application status: APPLIED, APTITUDE, CLEARED, INTERVIEW_SCHEDULED, SELECTED, REJECTED
   - Placement status: PLACED, UNPLACED
   - Notification types: PLACEMENT, DRIVE, INTERVIEW, MENTORING, GENERAL

---

## Indexing Strategy

Recommended indexes for performance:

```sql
-- User authentication
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- Application queries
CREATE INDEX idx_applications_student ON applications(student_id);
CREATE INDEX idx_applications_drive ON applications(drive_id);
CREATE INDEX idx_application_status_app ON application_status(application_id);

-- Drive queries
CREATE INDEX idx_drives_company ON drives(company_id);
CREATE INDEX idx_drives_status ON drives(status);

-- Interview queries
CREATE INDEX idx_interview_slots_drive ON interview_slots(drive_id);
CREATE INDEX idx_interview_slots_time ON interview_slots(start_time);

-- Student queries
CREATE INDEX idx_students_branch ON students(branch_id);
CREATE INDEX idx_student_skills_student ON student_skills(student_id);

-- Notification queries
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_created ON notifications(created_at);
```

---

## Backup & Recovery

Regular backups recommended:

```bash
# Full database backup
mysqldump -u root -p u508697926_form_db > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u root -p u508697926_form_db < backup_20240315.sql
```

---

**Last Updated:** February 2026
**Version:** 1.0
**Database Engine:** MySQL/MariaDB 5.7+
