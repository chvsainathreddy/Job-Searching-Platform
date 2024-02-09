<?php
require_once(__DIR__ . "/../model/pdo.php");

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $pdo = Database::getDB();
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
        $password =  $_POST['password'];
       
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid username format..! Username should be in email format.";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit(0);
        }

        $sql1 = "SELECT email FROM applicant WHERE email = :user";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute(array(
            ':user' => $_POST['username']
        ));

        $checkUser = $stmt1->fetch(PDO::FETCH_ASSOC);

        if ($stmt1->rowCount() > 0) {
            $_SESSION['error'] = "Username already exists, please choose another!";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit(0);
        }


        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $experience = filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING);
        $education = filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING);
        $skills = filter_input(INPUT_POST, 'skills', FILTER_SANITIZE_STRING);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
        $phone_no = filter_input(INPUT_POST, 'phone_no', FILTER_SANITIZE_NUMBER_INT);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
        $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
        $postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_NUMBER_INT);
    
        $sqlAddress = "INSERT INTO address (street, city, state, country, postal_code)
        VALUES (:street, :city, :state, :country, :postal_code)";

        $stmtAddress = $pdo->prepare($sqlAddress);
        $stmtAddress->execute(array(
        ':street' => $street,
        ':city' => $city,
        ':state' => $state,
        ':country' => $country,
        ':postal_code' => $postal_code,
        ));

        $addressId = $pdo->lastInsertId();



        $sql = "INSERT INTO applicant (firstname, lastname, email, password, experience, education, skills, gender, dob, phone_no ,address_id) 
                VALUES (:first_name, :last_name, :username, :password, :experience, :education, :skills, :gender, :dob, :phone_no, :address_id)";
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':first_name'=>$first_name,
            ':last_name'=>$last_name,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':experience'=>$experience,
            ':education'=>$education,
            ':skills'=>$skills,
            ':gender'=>$gender,
            ':dob'=>$dob,
            ':phone_no'=>$phone_no,
            ':address_id' => $addressId,
            )
        );
        if ($stmt) {
            $_SESSION['login'] = "You have Successfully registered..! Please Login.!";
            header("Location: ../applicant_page.php?action=login");
            exit(0);
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit(0);
        }
    }
} else {
$_SESSION['message']= "Please complete the registration form..";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Information Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-6 mx-auto">
            <h1 class="text-center text-primary">Personal Information Form</h1>
            <?php
                if (isset($_SESSION['message'])) {
                    echo '<div style="color: green;" class="text-center">' . $_SESSION['message'] . '</div>'; 
                    
                    unset($_SESSION['message']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<div style="color: red;" class="text-center">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
            ?>
            
            <form action="applicant_register.php" method="post" class="bg-white p-4 rounded shadow-sm" autocomplete="off">
 
                
                <div class="form-group">
                    <label for="username">Email:</label>
                    <input type="email" id="username" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <p id='msg'>Please enter the valid email address</p>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="experience">Experience:</label>
                    <input type="text" id="experience" name="experience" class="form-control" value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="education">Education:</label>
                    <input type="text" id="education" name="education" class="form-control" value="<?php echo isset($_POST['education']) ? htmlspecialchars($_POST['education']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" class="form-control"><?php echo isset($_POST['skills']) ? htmlspecialchars($_POST['skills']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="male" name="gender" value="male" class="custom-control-input" required>
                        <label for="male" class="custom-control-label">Male</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="female" name="gender" value="female" class="custom-control-input" required>
                        <label for="female" class="custom-control-label">Female</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" class="form-control" value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone_no">Phone Number:</label>
                    <input type="tel" id="phone_no" name="phone_no" maxlength="10" class="form-control" value="<?php echo isset($_POST['phone_no']) ? htmlspecialchars($_POST['phone_no']) : ''; ?>" required>
                </div>
                <p>Address :</p>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street" class="form-control" value="<?php echo isset($_POST['street']) ? htmlspecialchars($_POST['street']) : ''; ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" class="form-control" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="state">State:</label>
                        <input type="text" id="state" name="state" class="form-control" value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="country">Country:</label>
                        <input type="text" id="country" name="country" class="form-control" value="<?php echo isset($_POST['country']) ? htmlspecialchars($_POST['country']) : ''; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code:</label>
                    <input type="number" id="postal_code" name="postal_code" maxlength="5" class="form-control" maxlength=5 value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="../applicant_page.php?action=login" class="btn btn-link">Return to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="jquery-3.7.1.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#username').on('input', function() { 
            var email = $(this).val();
            $.getJSON('getjson.php', { email: email }, function(data) {
                if (data.exists) {
                    $('#msg').text('Email already exists! Please use another email').css('color', 'red');
                } else {
                    $('#msg').text('You can use this email').css('color', 'blue');
                }
            });
        });
    });
</script>

</boby>
</html>
    <!-- Bootstrap JS and jQuery (for optional features) -->
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR
