<?php
$isGenerated = ($_SERVER["REQUEST_METHOD"] == "POST");

if ($isGenerated) {

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $linkedin = htmlspecialchars($_POST['linkedin']);
    $summary = nl2br(htmlspecialchars($_POST['summary']));

    // Education
    $school10 = htmlspecialchars($_POST['school10']);
    $school10Year = htmlspecialchars($_POST['school10Year']);
    $school10Marks = htmlspecialchars($_POST['school10Marks']);

    $school12 = htmlspecialchars($_POST['school12']);
    $school12Year = htmlspecialchars($_POST['school12Year']);
    $school12Marks = htmlspecialchars($_POST['school12Marks']);

    $degree = htmlspecialchars($_POST['degree']);
    $degreeYear = htmlspecialchars($_POST['degreeYear']);
    $degreeCgpa = htmlspecialchars($_POST['degreeCgpa']);

    $masters = htmlspecialchars($_POST['masters']);
    $mastersYear = htmlspecialchars($_POST['mastersYear']);
    $mastersCgpa = htmlspecialchars($_POST['mastersCgpa']);

    $skills = explode(",", $_POST['skills']);
    $experience = nl2br(htmlspecialchars($_POST['experience']));
    $projects = nl2br(htmlspecialchars($_POST['projects']));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Resume Builder</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

.container {
    width: 900px;
    margin: 40px auto;
    background: white;
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    border-radius: 10px;
}

h1 {
    margin: 0;
    font-size: 30px;
    color: #2c3e50;
}

.header {
    text-align: center;
    margin-bottom: 25px;
    border-bottom: 2px solid #4e73df;
    padding-bottom: 10px;
}

.contact {
    margin-top: 8px;
    font-size: 14px;
    color: #555;
}

.section {
    margin-bottom: 22px;
}

.section-title {
    font-size: 15px;
    font-weight: bold;
    color: #4e73df;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
    margin-bottom: 8px;
}

.two-column {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 25px;
}

ul {
    padding-left: 18px;
}

form input, form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

form h3 {
    margin-top: 20px;
    color: #4e73df;
}

button {
    padding: 12px;
    background: linear-gradient(135deg,#4e73df,#6f42c1);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
}

.print-btn {
    text-align: center;
    margin-bottom: 20px;
}

.print-btn button {
    width: auto;
    padding: 10px 25px;
}

@media print {
    body { background: white; }
    .print-btn, form { display: none; }
    .container {
        box-shadow: none;
        margin: 0;
        width: 100%;
    }
}
</style>
</head>

<body>

<?php if (!$isGenerated): ?>

<div class="container">
    <h1 style="text-align:center;">Structured Resume Builder</h1>
    <form method="POST">

        <h3>Personal Information</h3>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="linkedin" placeholder="LinkedIn Profile">

        <h3>Professional Summary</h3>
        <textarea name="summary" placeholder="Write a short professional summary" required></textarea>

        <h3>Education - 10th</h3>
        <input type="text" name="school10" placeholder="School Name">
        <input type="text" name="school10Year" placeholder="Year">
        <input type="text" name="school10Marks" placeholder="Percentage / CGPA">

        <h3>Education - 12th</h3>
        <input type="text" name="school12" placeholder="College Name">
        <input type="text" name="school12Year" placeholder="Year">
        <input type="text" name="school12Marks" placeholder="Percentage / CGPA">

        <h3>Degree</h3>
        <input type="text" name="degree" placeholder="Degree Name (BCA / BTech etc)">
        <input type="text" name="degreeYear" placeholder="Year">
        <input type="text" name="degreeCgpa" placeholder="CGPA">

        <h3>Masters</h3>
        <input type="text" name="masters" placeholder="Masters Degree (MCA etc)">
        <input type="text" name="mastersYear" placeholder="Year">
        <input type="text" name="mastersCgpa" placeholder="CGPA">

        <h3>Skills</h3>
        <textarea name="skills" placeholder="Comma separated (Java, PHP, MySQL, AWS)" required></textarea>

        <h3>Experience</h3>
        <textarea name="experience" placeholder="Describe your work experience"></textarea>

        <h3>Projects</h3>
        <textarea name="projects" placeholder="Describe your projects"></textarea>

        <button type="submit">Generate Resume</button>

    </form>
</div>

<?php else: ?>

<div class="print-btn">
    <button onclick="window.print()">Save as PDF</button>
</div>

<div class="container">

    <div class="header">
        <h1><?php echo $name; ?></h1>
        <div class="contact">
            <?php echo $email; ?> | <?php echo $phone; ?><br>
            <?php echo $linkedin; ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Professional Summary</div>
        <p><?php echo $summary; ?></p>
    </div>

    <div class="section">
        <div class="section-title">Education</div>
        <p><strong>10th:</strong> <?php echo "$school10 ($school10Year) - $school10Marks"; ?></p>
        <p><strong>12th:</strong> <?php echo "$school12 ($school12Year) - $school12Marks"; ?></p>
        <p><strong>Degree:</strong> <?php echo "$degree ($degreeYear) - $degreeCgpa"; ?></p>
        <p><strong>Masters:</strong> <?php echo "$masters ($mastersYear) - $mastersCgpa"; ?></p>
    </div>

    <div class="section">
        <div class="section-title">Skills</div>
        <ul>
            <?php foreach ($skills as $skill): ?>
                <li><?php echo trim(htmlspecialchars($skill)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if(!empty($experience)): ?>
    <div class="section">
        <div class="section-title">Experience</div>
        <p><?php echo $experience; ?></p>
    </div>
    <?php endif; ?>

    <?php if(!empty($projects)): ?>
    <div class="section">
        <div class="section-title">Projects</div>
        <p><?php echo $projects; ?></p>
    </div>
    <?php endif; ?>

</div>

<?php endif; ?>

</body>
</html>