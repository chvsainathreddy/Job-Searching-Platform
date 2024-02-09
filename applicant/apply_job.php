<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['company_id'], $_GET['opening_id'])) {
    if (isset($_SESSION['applicant_id'])) {
        $applicantId = $_SESSION['applicant_id'];
        $companyId = $_GET['company_id'];
        $openingId = $_GET['opening_id'];


        $pdo = Database::getDB();
        $stmt = $pdo->prepare("SELECT * FROM registration WHERE applicant_id = :applicant_id AND opening_id = :opening_id");
        $stmt->execute([':applicant_id' => $applicantId, ':opening_id' => $openingId]);

        if ($stmt->rowCount() > 0) {
            header("Location:job_openings.php");
            exit(0);
            echo "You have already applied for this job opening.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO registration (applicant_id, company_id, opening_id)
                                VALUES (:applicant_id, :company_id ,:opening_id)");

            $stmt->execute([
                ':applicant_id' => $applicantId,
                ':company_id' => $companyId,
                ':opening_id' => $openingId,
            ]);

            if ($stmt->rowCount() > 0) {
                header("Location:job_openings.php");
                exit(0);
                echo "Application submitted successfully";
            } else {
                echo "Failed to submit application";
            }
        }
    } else {
        header("Location:../company_page.php?action=login");
        exit();
    }
} else {
    http_response_code(400);
    echo "Invalid request";
}
?>
