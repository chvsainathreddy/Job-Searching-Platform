<?php
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: company_login.php");
    exit();
}

require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();


$companyId = $_SESSION['company_id'];
$stmt = $pdo->prepare("SELECT r.registration_id,r.status, a.*, j.job_role, j.location
                      FROM registration r
                      JOIN applicant a ON r.applicant_id = a.applicant_id
                      JOIN job_opening j ON r.opening_id = j.opening_id
                      WHERE j.company_id = :company_id AND r.status = 'accepted'");
$stmt->execute([':company_id' => $companyId]);
$acceptedApplicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Applicants</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h1>Accepted Applicants</h1>

        <?php if (!empty($acceptedApplicants)) : ?>
            <table class="table table-bordered table-hover mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Job Role</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($acceptedApplicants as $index => $acceptedApplicant) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $acceptedApplicant['firstname'] . ' ' . $acceptedApplicant['lastname']; ?></td>
                            <td><?php echo $acceptedApplicant['email']; ?></td>
                            <td><?php echo $acceptedApplicant['job_role']; ?></td>
                            <td><?php echo $acceptedApplicant['location']; ?></td>
                            <td>
                                <span class="badge badge-success"><?php echo $acceptedApplicant['status']; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No accepted applicants.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
