<?php
session_start();
require_once(__DIR__."/../model/pdo.php");

function isFormSubmitted()
{
    return isset($_POST['submit']);
}

function updateCompanyProfile($pdo, $username, $newCompanyData, $newAddressData)
{
    $stmtCompany = $pdo->prepare("UPDATE company SET 
        company_name = :company_name,
        company_desc = :company_desc,
        phone_no = :phone_no
        WHERE email = :username");

    $stmtCompany->execute(array_merge([':username' => $username], $newCompanyData));

    $stmtGetAddressId = $pdo->prepare("SELECT address_id FROM company WHERE email = :username");
    $stmtGetAddressId->execute([':username' => $username]);
    $addressId = $stmtGetAddressId->fetchColumn();

    $stmtAddress = $pdo->prepare("UPDATE address SET 
        street = :street,
        city = :city,
        state = :state,
        country = :country,
        postal_code = :postal_code
        WHERE address_id = :address_id");

    $stmtAddress->execute(array_merge([':address_id' => $addressId], $newAddressData));
}

if (isset($_SESSION['company'])) {
    $pdo = Database::getDB();
    $username = $_SESSION['company'];

    if (isFormSubmitted()) {
        $newCompanyData = [
            ':company_name' => filter_input(INPUT_POST, 'company_name', FILTER_SANITIZE_STRING),
            ':company_desc' => filter_input(INPUT_POST, 'company_desc', FILTER_SANITIZE_STRING),
            ':phone_no' => filter_input(INPUT_POST, 'phone_no', FILTER_SANITIZE_NUMBER_INT),
        ];

        $newAddressData = [
            ':street' => filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING),
            ':city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING),
            ':state' => filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING),
            ':country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING),
            ':postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_NUMBER_INT),
        ];

        updateCompanyProfile($pdo, $username, $newCompanyData, $newAddressData);
    }



    $stmt = $pdo->prepare("SELECT * FROM company JOIN Address ON company.address_id = Address.address_id WHERE email = :username");
    $stmt->execute(array(':username' => $username));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $email = $user['email'];
        $company_name = $user['company_name'];
        $company_desc = $user['company_desc'];
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
    <title>Company User Profile</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        body {
            background: url('your-background-image.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            background: rgba(255, 255, 180, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 50px;
        }

        .form-control {
            background: rgba(100, 255, 25, 0.5);
            border: 1px;
            border-radius: 5px;
        }

        #editBtn {
            background: rgba(255, 255, 255, 0.5);
            border: 1px;
            border-radius: 5px;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="text-center mb-4">Company User Profile</h1>

        <?php if (isset($email)) : ?>
            <form method="post" action="" class="row">
                <div class="col-md-6">
                    <label for="company_name">Company Name:</label>
                    <input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" class="form-control" readonly>
                    <br>

                    <label for="company_desc">Company Description:</label>
                    <input type="text" id="company_desc" name="company_desc" value="<?php echo $company_desc; ?>" class="form-control" readonly>
                    <br>

                    <label for="phone_no">Phone Number:</label>
                    <input type="tel" id="phone_no" name="phone_no" value="<?php echo $phone_no; ?>" class="form-control" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" readonly>

                    <br>
                </div>

                <div class="col-md-6">
                    <address>
                        <label for="street">Street:</label>
                        <input id="street" name="street" value="<?php echo $street; ?>" class="form-control" readonly>
                        <br>

                        <label for="city">City:</label>
                        <input id="city" name="city" value="<?php echo $city; ?>" class="form-control" readonly>
                        <br>

                        <label for="state">State:</label>
                        <input id="state" name="state" value="<?php echo $state; ?>" class="form-control" readonly>
                        <br>

                        <label for="country">Country:</label>
                        <input id="country" name="country" value="<?php echo $country; ?>" class="form-control" readonly>
                        <br>

                        <label for="zip_code">Zip Code:</label>
                        <input id="zip_code" name="postal_code" value="<?php echo $postal_code; ?>" class="form-control" readonly>
                        <br>
                    </address>
                </div>

                <button type="button" id="editBtn" class="btn btn-secondary col-md-12 mt-3">Edit</button>
                <input type="submit" name="submit" value="Save Changes" class="btn btn-primary col-md-12 mt-3" style="display: none;">
            </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        $('#editBtn').on('click', function() {
            var formInputs = $('form input:not([type="submit"])');
            var saveChangesButton = $('form [type="submit"]');

            formInputs.prop('readonly', function(_, value) {
                return !value;
            });

            saveChangesButton.toggle();
        });
    </script>
</body>

</html>
