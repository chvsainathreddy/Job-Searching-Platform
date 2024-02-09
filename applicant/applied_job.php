<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registration_id'])) {
    $registrationId = $_POST['registration_id'];
    $stmt = $pdo->prepare("DELETE FROM registration WHERE registration_id = :registration_id");
    $stmt->execute([':registration_id' => $registrationId]);
    $_SESSION['message'] = "Deleted Successfully";
}

if (!isset($_SESSION['applicant_id'])) {
    $_SESSION['message'] = "Session expired.. Login Again..!";
    header("Location: applicant_login.php");
    exit();
}

$applicantId = $_SESSION['applicant_id'];
$stmt = $pdo->prepare("SELECT r.registration_id, r.status ,j.*, c.company_name
                      FROM registration r
                      JOIN job_opening j ON r.opening_id = j.opening_id
                      JOIN company c ON j.company_id = c.company_id
                      WHERE r.applicant_id = :applicant_id");
$stmt->execute([':applicant_id' => $applicantId]);
$appliedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Jobs</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <p><?php echo $_SESSION['message'] ?? " " ?></p><br>
    <h1>Applied Jobs</h1>

    <?php if (!empty($appliedJobs)) : ?>
        <div class="table-responsive bg-light">
            <table class="table table-bordered table-hover">
                <thead class="bg-info text-light">
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Job Role</th>
                        <th>Job Description</th>
                        <th>Skills Required</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="bg-light">
                    <?php foreach ($appliedJobs as $index => $appliedJob) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $appliedJob['company_name']; ?></td>
                            <td><?php echo $appliedJob['job_role']; ?></td>
                            <td><?php echo $appliedJob['job_desc']; ?></td>
                            <td><?php echo $appliedJob['skill_required']; ?></td>
                            <td><?php echo $appliedJob['location']; ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($appliedJob['status']) {
                                    case 'accepted':
                                        $statusClass = 'badge-success';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'badge-danger';
                                        break;
                                    case 'pending':
                                        $statusClass = 'badge-warning';
                                        break;
                                }
                                echo '<span class="badge ' . $statusClass . '">' . $appliedJob['status'] . '</span>';
                                ?>
                            </td>

                            <td>
                                <form action="applied_job.php" method="post">
                                    <input type="hidden" name="registration_id" value="<?php echo $appliedJob['registration_id']; ?>">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p>No applied jobs.</p>
    <?php endif; ?>
    </body>
    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</html>
