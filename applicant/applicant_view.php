<?php
session_start();
require_once(__DIR__ . "/../model/pdo.php");

// Function to check if the form is submitted
function isFormSubmitted()
{
    return isset($_POST['submit']);
}

// Function to update user information in the database
function updateApplicantProfile($pdo, $username, $newData, $newAddressData)
{
    $stmt = $pdo->prepare("UPDATE applicant SET 
        firstname = :firstname,
        lastname = :lastname,
        experience = :experience,
        education = :education,
        skills = :skills,
        gender = :gender,
        dob = :dob,
        phone_no = :phone_no
        WHERE email = :username");

    $stmt->execute(array_merge([':username' => $username], $newData));

    $stmtGetAddressId = $pdo->prepare("SELECT address_id FROM applicant WHERE email = :username");
    $stmtGetAddressId->execute([':username' => $username]);
    $addressId = $stmtGetAddressId->fetchColumn();

    // Update address information
    $stmtAddress = $pdo->prepare("UPDATE address SET 
        street = :street,
        city = :city,
        state = :state,
        country = :country,
        postal_code = :postal_code
        WHERE address_id = :address_id");

    $stmtAddress->execute(array_merge([':address_id' => $addressId], $newAddressData));
}

if (isset($_SESSION['user'])) {
    $pdo = Database::getDB();
    $username = $_SESSION['user'];

    if (isFormSubmitted()) {
        $newData = [
            ':firstname' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
            ':lastname' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
            ':experience' => filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING),
            ':education' => filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING),
            ':skills' => filter_input(INPUT_POST, 'skills', FILTER_SANITIZE_STRING),
            ':gender' =>filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING),
            ':dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),
            ':phone_no' => filter_input(INPUT_POST, 'phone_no', FILTER_SANITIZE_NUMBER_INT),
        ];
        $newAddressData = [
            ':street' => filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING),
            ':city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING),
            ':state' => filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING),
            ':country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING),
            ':postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_NUMBER_INT),
        ];

        updateApplicantProfile($pdo, $username, $newData, $newAddressData);
    }

    $stmt = $pdo->prepare("SELECT * FROM applicant JOIN Address ON applicant.address_id = Address.address_id WHERE email = :username");
    $stmt->execute(array(':username' => $username));
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
        $address_id = $user['address_id'];
        $street = $user['street'];
        $city = $user['city'];
        $state = $user['state'];
        $country = $user['country'];
        $postal_code = $user['postal_code'];
    } else {
        echo "User not found";
    }
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
            background: rgba(0, 216, 230, 0.2);
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
                                <input type="tel" id="phone_no" name="phone_no" pattern="[0-9]{10}" value="<?php echo $phone_no; ?>" class="form-control" readonly>
                            </div>
                        </div>

                        <p>Address :</p>
                    <address>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="street">Street:</label>
                                <input type="text" id="street" name="street" class="form-control" value="<?php echo $street; ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="city">City:</label>
                                <input type="text" id="city" name="city" class="form-control" value="<?php echo $city; ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="state">State:</label>
                                <input type="text" id="state" name="state" class="form-control" value="<?php echo $state; ?>" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="country">Country:</label>
                                <input type="text" id="country" name="country" class="form-control" value="<?php echo $country; ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code:</label>
                            <input type="number" id="postal_code" name="postal_code" class="form-control" value="<?php echo $postal_code; ?>" readonly>
                        </div>
                        </address>
                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="button" id="editBtn" class="btn btn-primary">Edit</button>
                                <input type="submit" name="submit" value="Save Changes" class="btn btn-success" style="display: none;">
                                <button type="button" id="cancelBtn" class="btn btn-secondary" style="display: none;">Cancel</button>
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

    <script>
        document.getElementById('editBtn').addEventListener('click', function() {
            var formInputs = document.querySelectorAll('form input:not([type="submit"]), form textarea');
            var saveChangesButton = document.querySelector('form [type="submit"]');
            
            for (var i = 0; i < formInputs.length; i++) {
                formInputs[i].readOnly = !formInputs[i].readOnly;
            }
            saveChangesButton.style.display = saveChangesButton.style.display === 'none' ? '' : 'none';
        });


        $(document).ready(function() {
            $("#editBtn").click(function() {
                $("input, textarea").prop("readonly", false);
                $(".btn-primary, .btn-secondary").toggle();
            });

            $("#cancelBtn").click(function() {
                $("input, textarea").prop("readonly", true);
                $(".btn-primary, .btn-secondary").toggle();
            });
        });
    </script>
</body>
</html>
