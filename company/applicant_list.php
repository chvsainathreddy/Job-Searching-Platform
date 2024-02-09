<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registration_id'], $_POST['action'])) {
    $registrationId = $_POST['registration_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'accept':
            $status = 'accepted';
            break;

        case 'reject':
            $status = 'rejected';
            break;
    }

    $stmt = $pdo->prepare("UPDATE registration SET status = :status WHERE registration_id = :registration_id");
    $stmt->execute([':status' => $status, ':registration_id' => $registrationId]);

    if ($stmt->rowCount() > 0) {
        if ($status === 'accepted') {
            header("Location: applicant_list.php");
            exit();
        } else {
            header("Location: applicant_list.php");
            exit();
        }
    }
}

if (!isset($_SESSION['company_id'])) {
    header("Location: company_login.php");
    exit();
}

$companyId = $_SESSION['company_id'];
$stmt = $pdo->prepare("SELECT r.registration_id,r.status, a.*, j.job_role, j.location
                      FROM registration r
                      JOIN applicant a ON r.applicant_id = a.applicant_id
                      JOIN job_opening j ON r.opening_id = j.opening_id
                      WHERE j.company_id = :company_id AND r.status = 'pending'");
$stmt->execute([':company_id' => $companyId]);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant List</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h1>Applicant List</h1>

        <?php if (!empty($applicants)) : ?>
            <table class="table table-bordered table-hover mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Applicant Name</th>
                        <th>Email</th>
                        <th>Job Role</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applicants as $index => $applicant) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><a href="company_applicant_profile.php?applicant_id=<?php echo $applicant['applicant_id']; ?>">
                                <?php echo $applicant['firstname'] . ' ' . $applicant['lastname']; ?>
                            </a></td>
                            <td><?php echo $applicant['email']; ?></td>
                            <td><?php echo $applicant['job_role']; ?></td>
                            <td><?php echo $applicant['location']; ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($applicant['status']) {
                                    case 'accepted':
                                        $statusClass = 'text-success';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'text-danger';
                                        break;
                                    default:
                                        $statusClass = 'text-warning';
                                        break;
                                }
                                ?>
                                <span class="<?php echo $statusClass; ?>"><?php echo $applicant['status']; ?></span>
                            </td>
                            <td>
                                <form action="applicant_list.php" method="post">
                                    <input type="hidden" name="registration_id" value="<?php echo $applicant['registration_id']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No pending applicants.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
