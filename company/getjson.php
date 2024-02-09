<?php
require_once(__DIR__ . "/../model/pdo.php");

if (isset($_GET['email'])) {
    $pdo = Database::getDB();
    $email = $_GET["email"];

    $sql1 = "SELECT email FROM company WHERE email = :email";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute(array(':email' => $email));

    $checkUser = $stmt1->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['exists' => $checkUser ? true : false]);
}
?>