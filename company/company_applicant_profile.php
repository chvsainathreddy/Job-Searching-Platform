<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

$pdo = Database::getDB();

$applicant_id = $_GET['applicant_id'];


$stmt = $pdo->prepare("SELECT * FROM applicant WHERE  applicant_id = :id");
$stmt->execute(array(':id' => $applicant_id));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $email = $user['email'];
    $first_name = $user['firstname'];
    $last_name = $user['lastname'];
    $experience = $user['experience'];
    $education = $user['education'];
    $skills = $user['skills'];
    $gender = $user['gender'];
    $dob = $user['dob'];
    $phone_no = $user['phone_no'];
} else {
    echo "User not found";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant User Profile</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
           
            background: rgba(180, 180, 180, 0.7); 
        }

        .container {
            background: rgba(25, 216, 100, 0.2); 
            border-radius: 15px;
            padding: 20px;
            margin-top: 50px;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="row">

            <div class="col-md-6">
                <h3>Applicant User Profile</h3>

                <?php if (isset($email)): ?>
                    <form method="post" action="">
                    <div style="position: fixed; right: 100px;">
                        <a class="btn btn-info" href="applicant_list.php">Back to Applicant's List</a>
                    </div>
                        <div class="form-group row">
                            <label for="first_name" class="col-sm-3 col-form-label">First Name:</label>
                            <div class="col-sm-9">
                                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-sm-3 col-form-label">Last Name:</label>
                            <div class="col-sm-9">
                                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Last Name:</label>
                            <div class="col-sm-9">
                                <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="experience" class="col-sm-3 col-form-label">Experience:</label>
                            <div class="col-sm-9">
                                <input type="text" id="experience" name="experience" value="<?php echo $experience; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="education" class="col-sm-3 col-form-label">Education:</label>
                            <div class="col-sm-9">
                                <input type="text" id="education" name="education" value="<?php echo $education; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="skills" class="col-sm-3 col-form-label">Skills:</label>
                            <div class="col-sm-9">
                                <input type="text" id="skills" name="skills" value="<?php echo $skills; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-sm-3 col-form-label">Gender:</label>
                            <div class="col-sm-9">
                                <input type="text" id="gender" name="gender" value="<?php echo $gender; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-sm-3 col-form-label">Date of Birth:</label>
                            <div class="col-sm-9">
                                <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_no" class="col-sm-3 col-form-label">Phone Number:</label>
                            <div class="col-sm-9">
                                <input type="tel" id="phone_no" name="phone_no" value="<?php echo $phone_no; ?>" class="form-control" readonly>
                            </div>
                        </div>
                        
                    </form>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>