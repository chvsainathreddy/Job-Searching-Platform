<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (isset($_SESSION['company_id'])) {
        $company_id = $_SESSION['company_id'];
        $job_role = filter_input(INPUT_POST, 'job_role', FILTER_SANITIZE_STRING);
        $job_desc = filter_input(INPUT_POST, 'job_desc', FILTER_SANITIZE_STRING);
        $skill_required = filter_input(INPUT_POST, 'skill_required', FILTER_SANITIZE_STRING);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    
        $pdo = Database::getDB();
        $stmt = $pdo->prepare("INSERT INTO job_opening (company_id, job_role, job_desc, skill_required, location) 
                              VALUES (:company_id, :job_role, :job_desc, :skill_required, :location)");
    
        $stmt->execute(array(
            ':company_id' => $company_id,
            ':job_role' => $job_role,
            ':job_desc' => $job_desc,
            ':skill_required' => $skill_required,
            ':location' => $location,
        ));
        header("Location:posted_jobs.php");
        exit();
    } else {
        header("Location:../company_page.php?action=login");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="bg-light p-4">

    <div class="container">
        <h1 class="mb-4">Add Job Opening</h1>

        <form action="post_job_form.php" method="post" class="p-3 rounded bg-white shadow">
            <input type="hidden" name="company_id" value="1">

            <div class="mb-3">
                <label for="job_role" class="form-label">Job Role:</label>
                <input type="text" id="job_role" name="job_role" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="job_desc" class="form-label">Job Description:</label>
                <textarea id="job_desc" name="job_desc" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label for="skill_required" class="form-label">Skill Required:</label>
                <input type="text" id="skill_required" name="skill_required" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location:</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js (for optional features) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-2sU1sPqjFqgqD6U2uV4tYcLeS5n0VB1NBO8Q+Kd5U9iPpvfwe/KDQ7LoYK+E0vmz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-EVSTQN3/azprGzTZi3kvmJWWQFNVHH8DAfAvmyblYXxGzq73T6ip4u7Knk5t8o6G" crossorigin="anonymous"></script>
</body>

</html>
