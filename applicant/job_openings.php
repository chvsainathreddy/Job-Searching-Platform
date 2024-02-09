<?php
session_start();

if (!isset($_SESSION['applicant_id'])) {
    header("Location: applicant_login.php");
    exit();
}

require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();
$stmt = $pdo->prepare("SELECT j.*, c.company_name 
                      FROM job_opening j
                      JOIN company c ON j.company_id = c.company_id");
$stmt->execute();
$jobOpenings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$appliedJobOpenings = [];
if (isset($_SESSION['applicant_id'])) {
    $applicantId = $_SESSION['applicant_id'];
    $stmt = $pdo->prepare("SELECT opening_id FROM registration WHERE applicant_id = :applicant_id");
    $stmt->execute([':applicant_id' => $applicantId]);
    $appliedJobOpenings = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="mt-3 mb-4">Job Postings</h1>

        <?php if (!empty($jobOpenings)) : ?>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Posted Date</th>
                        <th>Company Name</th>
                        <th>Job Role</th>
                        <th>Job Description</th>
                        <th>Skills Required</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobOpenings as $index => $job) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $job['opening_date']; ?></td>
                            <td><?php echo $job['company_name']; ?></td>
                            <td><?php echo $job['job_role']; ?></td>
                            <td><?php echo $job['job_desc']; ?></td>
                            <td><?php echo $job['skill_required']; ?></td>
                            <td><?php echo $job['location']; ?></td>
                            <td>
                                <?php if (in_array($job['opening_id'], $appliedJobOpenings)) : ?>

                                    <span class="btn btn-success">Applied</span>
                                <?php else : ?>
                                    <!-- Use a Bootstrap button for applying -->
                                    <a href="apply_job.php?company_id=<?php echo $job['company_id']; ?>&opening_id=<?php echo $job['opening_id']; ?>" class="btn btn-primary">Apply</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No job openings available.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
