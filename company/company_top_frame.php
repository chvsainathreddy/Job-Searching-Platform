
<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();

$email = $_SESSION['company'];

$stmt = $pdo->prepare("SELECT * FROM company WHERE  email = :email");
$stmt->execute(array(':email' => $email));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $name = $user['company_name'];
} else {
    echo "User not found";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Company Portal</title>
    <style>
        body {
            
            background: rgba(255, 255,255, 0.3);
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            background: rgba(220, 210, 200, 0.3);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px); 
            text-align: center;
            color: #3498db;
        }

        span {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <h1>
        Hello <span><?php echo $name?></span> ..!
    </h1>
</body>
</html>
