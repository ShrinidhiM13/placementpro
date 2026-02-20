# PlacementPro Dashboard - Quick Start Testing Guide

## 🚀 Quick Access

**URL**: `http://localhost/placementpro/public/index.html`

---

## 👥 Test Credentials

Use these credentials to test different roles:

### Student Account
```
Email: student@test.com
Password: password123
Role: STUDENT
```

### Alumni Account
```
Email: alumni@test.com
Password: password123
Role: ALUMNI
```

### TPO (Admin) Account
```
Email: tpo@test.com
Password: password123
Role: ADMIN/TPO
```

---

## ✅ Testing Checklist

### PHASE 1: Authentication & Dashboard Loading

**[ ] Test 1.1 - Student Login**
- [ ] Navigate to http://localhost/placementpro/public/index.html
- [ ] Click "Login" (if showing register)
- [ ] Email: `student@test.com`
- [ ] Password: `password123`
- [ ] Verify student dashboard loads
- [ ] Verify sidebar menu appears with all student options

**[ ] Test 1.2 - Alumni Login**
- [ ] Logout (click Logout button)
- [ ] Show login form
- [ ] Email: `alumni@test.com`
- [ ] Password: `password123`
- [ ] Verify alumni dashboard loads
- [ ] Verify different sidebar menu for alumni

**[ ] Test 1.3 - TPO Login**
- [ ] Logout
- [ ] Email: `tpo@test.com`
- [ ] Password: `password123`
- [ ] Verify TPO dashboard loads with statistics
- [ ] Verify admin-specific menu items

---

### PHASE 2: Student Features (14 items)

**[ ] Test 2.1 - Drives**
- [ ] Click "Drives" in sidebar
- [ ] Verify list of placement drives shows
- [ ] Verify drive cards show: Company, Title, CGPA requirement
- [ ] Click "Apply Now" on a drive
- [ ] Verify success message appears
- [ ] Verify applied status is stored

**[ ] Test 2.2 - My Applications**
- [ ] Click "My Applications"
- [ ] Verify list of applied drives shows
- [ ] Verify columns: Drive, Company, Status, Date
- [ ] Verify DELETE button appears for each application
- [ ] Click DELETE on one application
- [ ] Confirm deletion dialog appears
- [ ] Verify application is removed after confirmation

**[ ] Test 2.3 - My Interviews**
- [ ] Click "My Interviews"
- [ ] Verify interview slots show (if any exist)
- [ ] Verify details: Drive, Time, Location
- [ ] Verify "Book" button available if not booked

**[ ] Test 2.4 - Book Mentorship**
- [ ] Click "Book Mentorship"
- [ ] Verify list of mentor slots from alumni shows
- [ ] Verify display: Mentor name, email, slot time, availability status
- [ ] If slot is available, click "Book Session"
- [ ] Verify success message
- [ ] Verify button changes to "Already Booked"

**[ ] Test 2.5 - Skills**
- [ ] Click "Skills"
- [ ] Verify skill input form appears
- [ ] Enter skill name (e.g., "JavaScript")
- [ ] Click "Add Skill"
- [ ] Verify skill appears as tag
- [ ] Click X on skill tag to remove
- [ ] Verify skill is removed

**[ ] Test 2.6 - Projects**
- [ ] Click "Projects"
- [ ] Verify project form appears
- [ ] Fill: Title, Description, Tech Stack
- [ ] Click "Add Project"
- [ ] Verify project card appears in list
- [ ] Click "Delete" on project
- [ ] Verify project is removed

**[ ] Test 2.7 - Education**
- [ ] Click "Education"
- [ ] Verify education form
- [ ] Fill: Degree, Institute, Year, CGPA
- [ ] Click "Add Education"
- [ ] Verify education entry appears
- [ ] Click "Delete" to remove
- [ ] Verify entry is removed

**[ ] Test 2.8 - Certifications**
- [ ] Click "Certifications"
- [ ] Verify certification form appears
- [ ] Fill: Title, Issuer, Issue Year
- [ ] Click "Add Certification"
- [ ] Verify certification card appears
- [ ] Delete and verify removal

**[ ] Test 2.9 - Job Posts**
- [ ] Click "Job Posts"
- [ ] Verify list of alumni job postings appears
- [ ] Verify info: Company, Title, Description, Salary
- [ ] Verify no "Apply" yet (feature for future)

**[ ] Test 2.10 - Skill Gap Analysis**
- [ ] Click "Skill Gap Analytics"
- [ ] Verify analysis message appears
- [ ] Check if your skills vs placed students skills show

**[ ] Test 2.11 - Generate Resume**
- [ ] Click "Generate Resume"
- [ ] Click "Download Resume PDF"
- [ ] Verify PDF downloads successfully
- [ ] Open PDF and verify content (skills, education, etc.)

**[ ] Test 2.12 - Notifications**
- [ ] Click "Notifications"
- [ ] Verify notification list appears or message if none
- [ ] Should show: Application updates, messages

**[ ] Test 2.13 - Profile**
- [ ] Click "Profile"
- [ ] Verify form with current info
- [ ] Click "Update Profile"
- [ ] Verify changes are saved

**[ ] Test 2.14 - Change Password**
- [ ] Click "Change Password"
- [ ] Fill old password, new password, confirm
- [ ] Click "Update Password"
- [ ] Verify success message
- [ ] Logout and login with new password to verify

---

### PHASE 3: Alumni Features (7 items)

**[ ] Test 3.1 - Alumni Dashboard**
- [ ] Login as alumni
- [ ] Verify dashboard statistics box shows:
  - Job Posts Count
  - Mentorship Slots Count
  - Booked Sessions

**[ ] Test 3.2 - My Job Posts**
- [ ] Click "My Job Posts"
- [ ] Verify list of job posts created
- [ ] Verify DELETE button on each post
- [ ] Click DELETE to remove a post
- [ ] Verify post is removed from list

**[ ] Test 3.3 - Post Job**
- [ ] Click "Post Job"
- [ ] Fill form: Title, Company, Description, Location, Salary
- [ ] Click "Post Job"
- [ ] Verify job appears in "My Job Posts"
- [ ] Verify students can see it in "Job Posts" section

**[ ] Test 3.4 - Mentorship Slots**
- [ ] Click "Mentorship Slots"
- [ ] Verify list of created slots with times
- [ ] Verify "Booked" status for occupied slots
- [ ] Click DELETE on a slot
- [ ] Verify slot is removed

**[ ] Test 3.5 - Create Mentorship Slot**
- [ ] Click "Create Mentorship Slot"
- [ ] Select start time (datetime)
- [ ] Select end time (must be after start time)
- [ ] Click "Create Slot"
- [ ] Verify slot appears in "Mentorship Slots"
- [ ] Verify students can book it

**[ ] Test 3.6 - Alumni Profile**
- [ ] Click "Profile"
- [ ] Update information
- [ ] Click "Update"
- [ ] Verify changes saved

**[ ] Test 3.7 - Change Password**
- [ ] Click "Change Password"
- [ ] Update password
- [ ] Logout and login with new password

---

### PHASE 4: TPO (Admin) Features (8 items)

**[ ] Test 4.1 - TPO Dashboard**
- [ ] Login as TPO
- [ ] Verify statistics widget shows:
  - Total Drives
  - Total Applications
  - Total Students
  - Placed Students
  - Placement Percentage
  - Average Package

**[ ] Test 4.2 - Create Drive**
- [ ] Click "Create Drive"
- [ ] Fill form:
  - Company Name: "TCS"
  - Job Title: "Software Engineer"
  - Description: "Details here"
  - Min CGPA: 7.0
  - Max Backlogs: 2
  - Upload a logo image
- [ ] Click "Create Drive"
- [ ] Verify drive appears in "Manage Drives"
- [ ] Verify students can see it in their "Drives"

**[ ] Test 4.3 - Manage Drives**
- [ ] Click "Manage Drives"
- [ ] Verify list of all created drives
- [ ] Click "Edit" on a drive
- [ ] Modify details (CGPA, backlogs, status)
- [ ] Click "Update"
- [ ] Verify changes saved
- [ ] Click "Delete" to remove a drive
- [ ] Verify drive is removed

**[ ] Test 4.4 - Applications Management**
- [ ] Click "Applications"
- [ ] Verify table of all student applications
- [ ] Columns: Student Name, Drive, Email, Status
- [ ] Click "Update Status" button
- [ ] Select new status from dropdown
  - APPLIED → APTITUDE
  - APTITUDE → CLEARED
  - CLEARED → INTERVIEW_SCHEDULED
  - INTERVIEW_SCHEDULED → SELECTED or REJECTED
- [ ] Add remarks if desired
- [ ] Click "Update"
- [ ] Verify status updates in table
- [ ] Verify student can see updated status

**[ ] Test 4.5 - View Eligible Students**
- [ ] Click "Eligible Students (Unplaced)"
- [ ] Verify list of unplaced students
- [ ] Verify info: Name, Email, CGPA, Backlogs
- [ ] Click "Send Notification" button
- [ ] Type a message (e.g., "New drive available for JS developers")
- [ ] Click "Send"
- [ ] Verify success message
- [ ] Student should see notification in their "Notifications"

**[ ] Test 4.6 - Schedule Interviews**
- [ ] Click "Schedule Interviews"
- [ ] Select a drive from dropdown
- [ ] Set start time (datetime)
- [ ] Set end time (must be after start)
- [ ] Enter interview room/location
- [ ] Click "Create Slot"
- [ ] Verify interview slot appears
- [ ] Verify students see it in "My Interviews"

**[ ] Test 4.7 - View Analytics**
- [ ] Click "Analytics" or "Placement Stats"
- [ ] Verify statistics show:
  - Total Students
  - Placed Students
  - Unplaced Students
  - Placement Percentage (%)
  - Highest Package
  - Average Package

**[ ] Test 4.8 - Change Password**
- [ ] Click "Change Password"
- [ ] Update password
- [ ] Logout and re-login with new password

---

### PHASE 5: Complete Workflow Test

**[ ] Test 5.1 - End-to-End: Student Placement Flow**
1. **TPO Creates Drive**
   - [ ] Login as TPO
   - [ ] Create new drive "Tech Company - 2025"
   - [ ] Set CGPA: 7.5, Backlogs: 1

2. **Student Applies**
   - [ ] Login as student
   - [ ] Go to "Drives"
   - [ ] Apply to the drive created by TPO
   - [ ] Verify application appears in "My Applications"

3. **TPO Manages Application**
   - [ ] Login as TPO
   - [ ] Go to "Applications"
   - [ ] Find the student's application
   - [ ] Update status: APPLIED → APTITUDE
   - [ ] Add remark: "Shortlisted for written test"

4. **Student Sees Update**
   - [ ] Login as student
   - [ ] Go to "My Applications"
   - [ ] Verify status shows "APTITUDE"
   - [ ] Verify remark is visible if implemented

5. **TPO Schedules Interview**
   - [ ] Login as TPO
   - [ ] Go to "Schedule Interviews"
   - [ ] Select the drive
   - [ ] Create interview slot for tomorrow 2:00 PM
   - [ ] Set location: "Room 101"

6. **Student Books Interview**
   - [ ] Login as student
   - [ ] Go to "My Interviews"
   - [ ] Click "Book" on the interview slot
   - [ ] Verify booking confirmed

7. **TPO Updates Final Status**
   - [ ] Login as TPO
   - [ ] Go to "Applications"
   - [ ] Update status: APTITUDE → CLEARED → INTERVIEW_SCHEDULED → SELECTED
   - [ ] Add remarks: "Selected! Offer: 6.5 LPA"

8. **Student Sees Final Status**
   - [ ] Login as student
   - [ ] Check "My Applications"
   - [ ] Verify SELECTED status
   - [ ] Check "Notifications" for placement update

**[ ] Test 5.2 - End-to-End: Mentorship Booking**
1. **Alumni Creates Slot**
   - [ ] Login as alumni
   - [ ] Go to "Create Mentorship Slot"
   - [ ] Create slot for tomorrow 10:00 AM - 11:00 AM

2. **Student Books Slot**
   - [ ] Login as student
   - [ ] Go to "Book Mentorship"
   - [ ] Verify alumni's slot appears in list
   - [ ] Verify "AVAILABLE" status
   - [ ] Click "Book Session"
   - [ ] Verify status changes to "BOOKED"

3. **Alumni Sees Booking**
   - [ ] Login as alumni
   - [ ] Go to "Mentorship Slots"
   - [ ] Verify slot shows as "BOOKED"
   - [ ] Verify student name shown (if implemented)

---

### PHASE 6: UI/UX Tests

**[ ] Test 6.1 - Menu Navigation**
- [ ] Verify sidebar menus are different for each role
- [ ] All menu items click correctly
- [ ] No broken links
- [ ] Sidebar responsive on mobile

**[ ] Test 6.2 - Forms**
- [ ] All form fields accept input
- [ ] Submit buttons work correctly
- [ ] Error messages appear for invalid input
- [ ] Success messages appear after submission
- [ ] Forms clear after successful submission

**[ ] Test 6.3 - Status Badges**
- [ ] OPEN drives show in green
- [ ] CLOSED drives show in red
- [ ] APPLIED applications show in gray
- [ ] SELECTED applications show in green
- [ ] REJECTED applications show in red
- [ ] AVAILABLE slots show in green
- [ ] BOOKED slots show in orange

**[ ] Test 6.4 - Tables**
- [ ] Tables display all data correctly
- [ ] Columns align properly
- [ ] Data is readable
- [ ] Buttons function in tables
- [ ] Responsive on smaller screens

**[ ] Test 6.5 - Responsive Design**
- [ ] Open DevTools (F12)
- [ ] Test on mobile width (375px)
- [ ] Test on tablet width (768px)
- [ ] Test on desktop width (1024px)
- [ ] Verify layout adjusts correctly
- [ ] Verify all buttons clickable on mobile
- [ ] Verify sidebar collapses on mobile (if implemented)

---

### PHASE 7: Error Handling

**[ ] Test 7.1 - Network Errors**
- [ ] Turn off internet connection
- [ ] Try to load dashboard
- [ ] Verify error message appears (optional)
- [ ] Or show loading indicator
- [ ] Re-enable internet and refresh

**[ ] Test 7.2 - API Errors**
- [ ] Check browser DevTools Network tab
- [ ] Verify all API calls return 2xx status
- [ ] No 404 errors for endpoints
- [ ] No 401 Unauthorized errors
- [ ] No 500 Server errors

**[ ] Test 7.3 - Form Validation**
- [ ] Try submitting empty forms
- [ ] Verify error messages appear
- [ ] Try invalid emails
- [ ] Verify validation works
- [ ] Try special characters in fields

**[ ] Test 7.4 - Authorization**
- [ ] Login as student
- [ ] Try accessing TPO features (shouldn't work)
- [ ] Verify correct error or redirect
- [ ] Verify no sensitive data exposed

---

## 📊 Test Results Summary

### Manual Testing
After completing all tests, fill in results:

```
PHASE 1 (Authentication): [ ] PASS [ ] FAIL
PHASE 2 (Student Features): [ ] PASS [ ] FAIL
PHASE 3 (Alumni Features): [ ] PASS [ ] FAIL  
PHASE 4 (TPO Features): [ ] PASS [ ] FAIL
PHASE 5 (Complete Workflows): [ ] PASS [ ] FAIL
PHASE 6 (UI/UX): [ ] PASS [ ] FAIL
PHASE 7 (Error Handling): [ ] PASS [ ] FAIL

Overall Status: [ ] PASS [ ] FAIL

Issues Found: ___________________________
Recommendations: _______________________
```

---

## 🐛 Common Issues & Solutions

### Issue: "Cannot POST /api/..." 
**Solution**: Verify API endpoint file exists in correct directory

### Issue: Buttons not working
**Solution**: 
- Check browser console (F12)
- Verify function names in app.js
- Check for JavaScript errors

### Issue: Styles look broken
**Solution**:
- Hard refresh: Ctrl+Shift+R
- Clear cache: Ctrl+Shift+Delete
- Check style.css is linked in index.html

### Issue: Token expires quickly
**Solution**:
- Check API returns valid JWT token
- Verify token expiry time in backend

### Issue: Data not saving
**Solution**:
- Check API response in DevTools Network tab
- Verify database is connected
- Check API request has Authorization header

---

## 📝 Test Report Template

```
Date: _______________
Tester: _______________
Browser: _______________
OS: _______________

Feature Tested: _______________
Expected Behavior: _______________
Actual Behavior: _______________

Status: [ ] PASS [ ] FAIL

If FAIL:
  Error Message: _______________
  Steps to Reproduce: _______________
  Severity: [ ] Low [ ] Medium [ ] High
  
Notes: _______________
```

---

**Status**: Ready for Testing  
**Test Date**: _______________  
**Tester**: _______________
