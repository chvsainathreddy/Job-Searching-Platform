<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];

    $pdo = Database::getDB();
    $stmt = $pdo->prepare("DELETE FROM job_opening WHERE opening_id = :job_id");
    $stmt->execute([':job_id' => $jobId]);
}


if (isset($_SESSION['company_id'])) {
    $company_id = $_SESSION['company_id'];

    $pdo = Database::getDB();
    $stmt = $pdo->prepare("SELECT * FROM job_opening WHERE company_id = :company_id");
    $stmt->execute([':company_id' => $company_id]);

    $jobOpenings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location:../company_page.php?action=login");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posted Jobs</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="bg-light p-4">

    <div class="container">
        <h1>Job Openings</h1>

        <?php if (!empty($jobOpenings)) : ?>
            <div class="table-responsive">
            <table class="table table-bordered table-hover mt-4">
                <thead class="thead-dark">
                        <tr>
                            <th>Job Role</th>
                            <th>Job Description</th>
                            <th>Skill Required</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobOpenings as $job) : ?>
                            <tr>
                                <td><?php echo $job['job_role']; ?></td>
                                <td><?php echo $job['job_desc']; ?></td>
                                <td><?php echo $job['skill_required']; ?></td>
                                <td><?php echo $job['location']; ?></td>
                                <td>
                                    <form action="posted_jobs.php" method="post">
                                        <input type="hidden" name="job_id" value="<?php echo $job['opening_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p>No job openings available.</p>
        <?php endif; ?>

        <a href="post_job_form.php" target="f3" class="btn btn-primary">Add Job Opening</a>
    </div>

    <!-- Bootstrap JS and Popper.js (for optional features) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-2sU1sPqjFqgqD6U2uV4tYcLeS5n0VB1NBO8Q+Kd5U9iPpvfwe/KDQ7LoYK+E0vmz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-EVSTQN3/azprGzTZi3kvmJWWQFNVHH8DAfAvmyblYXxGzq73T6ip4u7Knk5t8o6G" crossorigin="anonymous"></script>
</body>

</html>
