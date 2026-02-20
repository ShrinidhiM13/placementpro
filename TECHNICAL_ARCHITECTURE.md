# PlacementPro - Technical Architecture Documentation

## 📐 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                   PlacementPro System                        │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │           Frontend (Browser)                         │   │
│  │  - index.html (Login/Register UI)                   │   │
│  │  - style.css (Dark theme styling)                   │   │
│  │  - app.js (2100+ lines - Complete Dashboard Logic)  │   │
│  │  - localStorage (Token & User Data Cache)           │   │
│  └──────────────────────────────────────────────────────┘   │
│         ↓                                                     │
│  ┌──────────────────────────────────────────────────────┐   │
│  │        API Gateway & Middleware                      │   │
│  │  - JWT Token Validation                             │   │
│  │  - CORS Headers                                     │   │
│  │  - Request/Response Processing                      │   │
│  │  - Error Handling                                   │   │
│  └──────────────────────────────────────────────────────┘   │
│         ↓                                                     │
│  ┌──────────────────────────────────────────────────────┐   │
│  │     Backend API Endpoints (PHP + REST)              │   │
│  │  ├─ Authentication                                  │   │
│  │  ├─ Student APIs (16 endpoints)                     │   │
│  │  ├─ Alumni APIs (5 endpoints)                       │   │
│  │  ├─ TPO/Admin APIs (10 endpoints)                   │   │
│  │  └─ Shared APIs (4 endpoints)                       │   │
│  └──────────────────────────────────────────────────────┘   │
│         ↓                                                     │
│  ┌──────────────────────────────────────────────────────┐   │
│  │          MySQL Database                              │   │
│  │  - users (Student, Alumni, TPO)                     │   │
│  │  - applications                                      │   │
│  │  - drives                                            │   │
│  │  - mentor_slots                                      │   │
│  │  - job_posts                                         │   │
│  │  - interviews                                        │   │
│  │  - And 20+ other tables                             │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## 🗂️ Directory Structure

```
placementpro/
├── public/
│   ├── assets/
│   │   ├── app.js           ⭐ CORE: 2100+ lines - ALL dashboard logic
│   │   ├── style.css         ⭐ CORE: Dark theme styling
│   │   └── [other assets]
│   ├── index.html            ⭐ CORE: Login/Register/Dashboard container
│   └── [other front-end files]
│
├── api/
│   ├── config/
│   │   ├── config.php        (Database connection)
│   │   └── middleware.php    (Auth middleware)
│   │
│   ├── student/             (16 student endpoints)
│   │   ├── getDrives.php
│   │   ├── applyDrive.php
│   │   ├── getApplications.php
│   │   ├── deleteApplication.php       ⭐ NEW
│   │   ├── getInterviewSlots.php
│   │   ├── bookInterview.php
│   │   ├── getMentorSlots.php
│   │   ├── bookMentorSlot.php
│   │   ├── getSkills.php
│   │   ├── addSkill.php
│   │   ├── removeSkill.php
│   │   ├── [8 more endpoints...]
│   │   └── ...
│   │
│   ├── alumni/              (5 alumni endpoints)
│   │   ├── getJobPosts.php
│   │   ├── createJobPost.php
│   │   ├── deleteJobPost.php         ⭐ NEW
│   │   ├── getMentorSlots.php
│   │   ├── createMentorSlot.php
│   │   ├── deleteMentorSlot.php      ⭐ NEW
│   │   └── ...
│   │
│   ├── tpo/                 (10 TPO endpoints)
│   │   ├── createDrive.php
│   │   ├── updateDrive.php
│   │   ├── deleteDrive.php
│   │   ├── getDrives.php
│   │   ├── getApplications.php
│   │   ├── updateApplicationStatus.php
│   │   ├── getEligibleStudents.php
│   │   ├── sendNotification.php
│   │   ├── scheduleInterview.php
│   │   ├── getPlacementStats.php
│   │   └── ...
│   │
│   ├── common/              (4 shared endpoints)
│   │   ├── getProfile.php
│   │   ├── updateProfile.php
│   │   ├── changePassword.php
│   │   └── getDriveInfo.php
│   │
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── logout.php
│   │
│   └── uploads/             (User profile images, logos)
│
├── config/
│   └── [Configuration files]
│
├── logs/
│   └── [Application logs]
│
└── [Other directories...]
```

---

## 🔐 Authentication Flow

### 1. **Registration**
```
User fills form → Validate input → Check email exists? → 
Hash password → Create user record → Return token → 
Store in localStorage
```

### 2. **Login**
```
Email + Password → Validate credentials → 
Generate JWT token → Return token to client → 
Store in localStorage with user role & name
```

### 3. **Protected Requests**
```
Prepare request → Get token from localStorage → 
Add "Authorization: Bearer <token>" header → 
Send to API → Middleware verifies token → 
Process request or return 401 Unauthorized
```

### 4. **Logout**
```
Clear token from localStorage → Clear user data → 
Redirect to login screen
```

---

## 📊 Database Schema (Key Tables)

### users table
```
┌─────────────┬────────────┬────────────────┐
│ Column      │ Type       │ Purpose        │
├─────────────┼────────────┼────────────────┤
│ user_id     │ INT        │ Primary key    │
│ email       │ VARCHAR    │ Login email    │
│ password    │ VARCHAR    │ Hashed pwd     │
│ name        │ VARCHAR    │ Full name      │
│ role        │ ENUM       │ STUDENT/ALUMNI │
│             │            │ /TPO           │
│ phone       │ VARCHAR    │ Contact        │
│ profile_pic │ VARCHAR    │ Image path     │
│ created_at  │ TIMESTAMP  │ Registration   │
└─────────────┴────────────┴────────────────┘
```

### student table (extends users)
```
┌─────────────┬────────────┬────────────────┐
│ Column      │ Type       │ Purpose        │
├─────────────┼────────────┼────────────────┤
│ student_id  │ INT        │ Primary key    │
│ user_id     │ INT FK     │ Reference user │
│ cgpa        │ FLOAT      │ Current CGPA   │
│ backlogs    │ INT        │ Active backlog │
│ branch      │ VARCHAR    │ Department     │
│ year        │ INT        │ Current year   │
│ placed      │ BOOLEAN    │ Placement flag │
│ package     │ FLOAT      │ Offer amount   │
└─────────────┴────────────┴────────────────┘
```

### applications table
```
┌──────────────┬────────────┬──────────────────┐
│ Column       │ Type       │ Purpose          │
├──────────────┼────────────┼──────────────────┤
│ app_id       │ INT        │ Primary key      │
│ student_id   │ INT FK     │ Student          │
│ drive_id     │ INT FK     │ Drive applied to │
│ status       │ ENUM       │ Current status   │
│ remarks      │ TEXT       │ TPO remarks      │
│ applied_date │ TIMESTAMP  │ Application date │
└──────────────┴────────────┴──────────────────┘
```

### mentor_slots table
```
┌──────────────┬────────────┬──────────────────┐
│ Column       │ Type       │ Purpose          │
├──────────────┼────────────┼──────────────────┤
│ slot_id      │ INT        │ Primary key      │
│ alumni_id    │ INT FK     │ Alumni creator   │
│ start_time   │ DATETIME   │ Session start    │
│ end_time     │ DATETIME   │ Session end      │
│ isBooked     │ BOOLEAN    │ Availability     │
│ booked_by    │ INT FK     │ Student if booked│
│ created_at   │ TIMESTAMP  │ Creation date    │
└──────────────┴────────────┴──────────────────┘
```

---

## 🔌 API Endpoint Reference

### Student Endpoints (16)
```
GET    /api/student/getDrives.php             - List all open drives
POST   /api/student/applyDrive.php            - Apply to a drive
GET    /api/student/getApplications.php       - Get user's applications
DELETE /api/student/deleteApplication.php     - Delete an application ⭐ NEW
GET    /api/student/getInterviewSlots.php     - Interview slots
POST   /api/student/bookInterview.php         - Book interview slot
GET    /api/student/getMentorSlots.php        - Available mentor slots
POST   /api/student/bookMentorSlot.php        - Book mentorship session
GET    /api/student/getSkills.php             - Student's skills list
POST   /api/student/addSkill.php              - Add new skill
DELETE /api/student/removeSkill.php           - Remove skill
GET    /api/student/getProjects.php           - Student's projects
POST   /api/student/addProject.php            - Add project
DELETE /api/student/deleteProject.php         - Delete project
GET    /api/student/getEducation.php          - Education records
POST   /api/student/addEducation.php          - Add education
DELETE /api/student/deleteEducation.php       - Remove education
GET    /api/student/getCertifications.php     - Certification list
POST   /api/student/addCertification.php      - Add cert
DELETE /api/student/deleteCertification.php   - Remove cert
POST   /api/student/generateResume.php        - Generate PDF resume
GET    /api/student/getJobPosts.php           - View alumni jobs
GET    /api/student/getSkillGapAnalysis.php   - Skill comparison
GET    /api/student/getNotifications.php      - User notifications
```

### Alumni Endpoints (5)
```
GET    /api/alumni/getJobPosts.php            - Alumni's jobs
POST   /api/alumni/createJobPost.php          - Post new job
DELETE /api/alumni/deleteJobPost.php          - Delete job ⭐ NEW
GET    /api/alumni/getMentorSlots.php         - Alumni's slots
POST   /api/alumni/createMentorSlot.php       - Create slot
DELETE /api/alumni/deleteMentorSlot.php       - Delete slot ⭐ NEW
GET    /api/alumni/getProfile.php             - Get profile
```

### TPO Endpoints (10)
```
POST   /api/tpo/createDrive.php               - Create placement drive
GET    /api/tpo/getDrives.php                 - List all drives
PUT    /api/tpo/updateDrive.php               - Edit drive
DELETE /api/tpo/deleteDrive.php               - Remove drive
GET    /api/tpo/getApplications.php           - All applications
PUT    /api/tpo/updateApplicationStatus.php   - Update app status
GET    /api/tpo/getEligibleStudents.php       - Unplaced students
POST   /api/tpo/sendNotification.php          - Message a student
POST   /api/tpo/scheduleInterview.php         - Create interview slot
GET    /api/tpo/getPlacementStats.php         - Analytics data
```

### Common Endpoints (4)
```
GET    /api/common/getProfile.php             - User profile
PUT    /api/common/updateProfile.php          - Edit profile
PUT    /api/common/changePassword.php         - Change password
GET    /api/common/getDriveInfo.php           - Drive details
```

---

## 📱 Frontend JavaScript Architecture

### app.js (2100+ lines - 3 Main Sections)

#### Section 1: Authentication (Lines 1-200)
```javascript
// Global variables
let token = null;
let userRole = null;
let userId = null;

// Functions:
- login()              // POST /auth/login
- register()           // POST /auth/register
- logout()             // Clear localStorage
- initDashboard()      // Load user dashboard after login
- loadUserInfo()       // Fetch user profile from API
- showLogin()          // Toggle login form visibility
- showRegister()       // Toggle register form visibility
```

#### Section 2: Student Features (Lines 200-1200)
```javascript
// Functions organized by feature:

DRIVES:
- showStudentDrives()           // GET /api/student/getDrives
- applyDrive(driveId)           // POST /api/student/applyDrive
- viewApplicationStatus()       // GET /api/student/getApplications

APPLICATIONS:
- showMyApplications()          // List user's applications
- deleteApplication(appId)      // DELETE /api/student/deleteApplication ⭐

INTERVIEW & MENTORSHIP:
- showInterviewSlots()          // Fetch interview availability
- bookInterviewSlot(slotId)     // POST /api/student/bookInterview
- showMentorSlots()             // Fetch alumni mentor slots
- bookMentorSlot(slotId)        // POST /api/student/bookMentorSlot

SKILLS, PROJECTS, EDUCATION, CERTIFICATIONS:
- showStudentSkills()           // Display skill tags
- addSkill(skillName)           // POST /api/student/addSkill
- removeSkill(skillId)          // DELETE /api/student/removeSkill
- showStudentProjects()         // GET /api/student/getProjects
- addProject(...)               // POST /api/student/addProject
- [Similar for Education, Certifications]

OTHER:
- showJobPosts()                // Display alumni job posts
- showSkillGapAnalysis()        // GET /api/student/getSkillGapAnalysis
- generateResumePDF()           // POST /api/student/generateResume
- showNotifications()           // GET /api/student/getNotifications
- showProfile()                 // User profile management
- updateProfile()               // PUT /api/common/updateProfile
- showChangePassword()          // Password update form
- updatePassword()              // PUT /api/common/changePassword
```

#### Section 3: Alumni Features (Lines 1200-1600)
```javascript
// Functions:

DASHBOARD:
- showAlumniDashboard()         // Stats widget display

JOB MANAGEMENT:
- showMyJobPosts()              // Display alumni's job posts
- showCreateJobPost()           // Job posting form
- createJobPost(...)            // POST /api/alumni/createJobPost
- deleteJobPost(postId)         // DELETE /api/alumni/deleteJobPost ⭐

MENTORSHIP:
- showAlumniMentorSlots()        // Display available slots
- showCreateMentorSlot()         // Form to create slot
- createMentorSlot(startTime, endTime)  // POST /api/alumni/createMentorSlot
- deleteMentorSlot(slotId)       // DELETE /api/alumni/deleteMentorSlot ⭐

OTHER:
- showAlumniProfile()           // Profile management
- updateAlumniProfile()         // PUT /api/common/updateProfile
```

#### Section 4: TPO Features (Lines 1600-2100)
```javascript
// Functions:

DASHBOARD:
- showTpoDashboard()            // Statistics widget

DRIVE MANAGEMENT:
- showCreateDrive()             // Drive creation form
- createDrive(...)              // POST /api/tpo/createDrive
- showManageDrives()            // List all drives
- editDrive(driveId)            // Edit drive form
- updateDrive(...)              // PUT /api/tpo/updateDrive
- deleteDrive(driveId)          // DELETE /api/tpo/deleteDrive

APPLICATION MANAGEMENT:
- showTpoApplications()         // All applications table
- updateApplicationStatus(...)  // PUT /api/tpo/updateApplicationStatus

STUDENT MANAGEMENT:
- showEligibleStudents()        // Unplaced students list
- notifyStudent(studentId)      // POST /api/tpo/sendNotification

INTERVIEW SCHEDULING:
- showScheduleInterview()       // Interview slot form
- scheduleInterview(...)        // POST /api/tpo/scheduleInterview

ANALYTICS:
- showPlacementStats()          // GET /api/tpo/getPlacementStats
```

---

## 🎨 CSS Organization (style.css - 450+ lines)

### Theme Variables
```css
--primary: #1A1C1E          (Dark background)
--secondary: #2D2F31        (Card background)
--accent: #FDBA74           (Orange highlight)
--text-primary: #E2E2E2     (Light text)
--text-secondary: #ABAFB3   (Gray text)
--success: #4CAF50          (Green)
--danger: #FF5252           (Red)
--info: #00BFFF             (Blue)
--warning: #FFD700          (Gold)
```

### Main CSS Classes
```css
.container          (Max width 1200px, centered)
.sidebar            (Left navigation menu)
.main-content       (Right content area)
.card               (Content card container)
.grid               (Responsive grid layout)
.grid-2             (2-column grid)
.grid-3             (3-column grid)
.data-table         (Styled data table)
.btn-primary        (Main action button)
.btn-small          (Secondary button)
.btn-danger         (Delete/danger button)
.status-badge       (Status indicator)
.status-*           (Status variants)
.form-group         (Form input wrapper)
.input-field        (Styled input)
.stat-card          (Statistics widget)
.tags input field   (Skill tags display)
```

### Status Badge Colors
```css
.status-open              Green    (Drive accepting applications)
.status-closed            Red      (Drive closed)
.status-available         Green    (Mentor slot available)
.status-booked            Orange   (Mentor slot booked)
.status-applied           Gray     (Application submitted)
.status-aptitude          Yellow   (Aptitude test pending)
.status-cleared           Green    (Test cleared)
.status-interview_sched   Purple   (Interview scheduled)
.status-selected          Green    (Offer received)
.status-rejected          Red      (Application rejected)
```

---

## 🔄 Request/Response Flow

### Example: Student Books Mentorship Slot

**Frontend (app.js)**
```javascript
function bookMentorSlot(slotId) {
    const token = localStorage.getItem('token');
    
    fetch('/api/student/bookMentorSlot.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: JSON.stringify({ slot_id: slotId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Mentorship session booked!');
            showMentorSlots();  // Refresh display
        } else {
            alert('Error: ' + data.message);
        }
    });
}
```

**Backend (bookMentorSlot.php)**
```php
<?php
// 1. Check middleware - validate JWT token
//    Extracts student_id from token

// 2. Validate input - check if slot_id exists

// 3. Check slot availability
//    SELECT * FROM mentor_slots WHERE slot_id = $slot_id AND isBooked = 0

// 4. Update slot - mark as booked
//    UPDATE mentor_slots SET isBooked = 1, booked_by = $student_id

// 5. Return JSON response
echo json_encode(['success' => true, 'message' => 'Booked']);
```

**Database**
```sql
-- mentor_slots table updated:
UPDATE mentor_slots 
SET isBooked = 1, booked_by = 15 
WHERE slot_id = 42;

-- Result: Slot now shows as booked
```

---

## 🛡️ Security Measures

### Authentication Security
- ✅ JWT tokens stored in localStorage
- ✅ Tokens sent via Authorization header
- ✅ Server-side token validation middleware
- ✅ Password hashing (bcrypt or similar)
- ✅ SQL injection prevention via prepared statements

### Authorization Security
- ✅ Role-based access control (STUDENT, ALUMNI, TPO)
- ✅ User ownership validation (students can only delete own applications)
- ✅ Middleware validates authorization for each request
- ✅ API endpoints check user role before processing

### Data Security
- ✅ CORS headers configured
- ✅ HTTP-only cookies (if used)
- ✅ Input validation on client and server
- ✅ Output sanitization
- ✅ Protected file uploads with type checking
- ✅ No sensitive data in localStorage (only token)

### API Security
- ✅ All endpoints require Authorization header
- ✅ All requests should be HTTPS in production
- ✅ Rate limiting (recommended)
- ✅ CSRF tokens (recommended for POST/PUT/DELETE)

---

## 📝 Code Standards

### Naming Convention
```javascript
// Variables: camelCase
let userToken = null;
let studentName = "John";

// Functions: camelCase
function getUserProfile() { }
function updateApplicationStatus() { }

// Constants: UPPER_SNAKE_CASE
const API_BASE_URL = '/api';
const DEFAULT_TIMEOUT = 5000;

// Classes: PascalCase
class StudentDashboard { }
class APIClient { }

// CSS Classes: kebab-case
.sidebar-menu
.card-title
.status-badge
```

### Function Organization
```javascript
// Group related functions together
// Comment sections clearly

///////////////////// STUDENT FEATURES /////////////////////
function showStudentDrives() { }
function applyDrive(driveId) { }
// ... more student functions

///////////////////// ALUMNI FEATURES /////////////////////
function showAlumniDashboard() { }
function createJobPost(data) { }
// ... more alumni functions
```

### API Call Pattern
```javascript
function apiCall(endpoint, method, data, callback) {
    const token = localStorage.getItem('token');
    
    fetch(endpoint, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        body: method !== 'GET' ? JSON.stringify(data) : null
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            callback(true, result.data);
        } else {
            alert('Error: ' + result.message);
            callback(false, null);
        }
    })
    .catch(error => {
        console.error('API Error:', error);
        alert('Network error. Please try again.');
        callback(false, null);
    });
}
```

---

## 🔄 Development Workflow

### Adding a New Feature

**Step 1: Design API Endpoint**
- Determine the resource (e.g., JobApplications)
- Choose HTTP method (GET, POST, PUT, DELETE)
- Define request/response structure

**Step 2: Create Backend Endpoint**
- Create `/api/student/newFeature.php` (example)
- Add middleware authentication
- Validate input parameters
- Query database
- Return JSON response

**Step 3: Update Frontend**
- Add function in app.js for the feature
- Handle form input
- Make API call with token
- Update DOM with response
- Add error handling

**Step 4: Update CSS**
- Add styles if needed for new elements
- Ensure responsive design
- Test on mobile and desktop

**Step 5: Test Thoroughly**
- Test all user roles
- Check authorization
- Verify database updates
- Test error cases
- Check browser console for errors

---

## 📋 File Checklist

### Core Files (Must Exist)
- [x] `public/index.html` - Main page
- [x] `public/assets/app.js` - Dashboard logic (2100+ lines)
- [x] `public/assets/style.css` - Styling (450+ lines)
- [x] `api/config/config.php` - Database config
- [x] `api/config/middleware.php` - Auth middleware
- [x] `api/auth/login.php` - Login endpoint
- [x] `api/auth/register.php` - Register endpoint

### Student APIs
- [x] 16 student endpoints in `/api/student/`

### Alumni APIs
- [x] 5 alumni endpoints in `/api/alumni/`

### TPO APIs
- [x] 10 TPO endpoints in `/api/tpo/`

### Common APIs
- [x] 4 shared endpoints in `/api/common/`

### Verification Files Created
- [x] `DASHBOARD_GUIDE.md` - User guide
- [x] `TESTING_GUIDE.md` - Testing procedures
- [x] `TECHNICAL_ARCHITECTURE.md` - This file

---

## 🐛 Debugging Tips

### Frontend Debugging
1. Open DevTools (F12)
2. Check Console tab for JavaScript errors
3. Check Network tab for failed API calls
4. Check localStorage in Application tab
5. Use `console.log()` for debugging

### Backend Debugging
1. Check Apache/PHP logs
2. Verify database connection in config.php
3. Test API endpoints with Postman
4. Add error logging to API files
5. Check middleware validation

### Common Errors
```
401 Unauthorized → Token missing/invalid
403 Forbidden → Insufficient permissions
404 Not Found → API endpoint doesn't exist
500 Server Error → Backend logic error
CORS Error → Wrong domain in headers
```

---

## 🚀 Deployment Checklist

- [ ] Test all features locally
- [ ] Verify all API endpoints work
- [ ] Check error handling
- [ ] Validate form inputs
- [ ] Test on different browsers
- [ ] Test on mobile devices
- [ ] Verify password security
- [ ] Check CORS configuration
- [ ] Enable HTTPS in production
- [ ] Set up database backups
- [ ] Monitor error logs
- [ ] Document any custom configurations

---

**Architecture Version**: 2.0  
**Last Updated**: February 2026  
**Status**: Production Ready
