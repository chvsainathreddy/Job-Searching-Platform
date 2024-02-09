<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Navigation</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="bg-light p-4">

    <div class="list-group">
        <a href="company_view.php" class="list-group-item list-group-item-action <?php echo ($activePage == 'profile') ? 'active' : ''; ?>" target="f3">Profile</a>
        <a href="posted_jobs.php" class="list-group-item list-group-item-action <?php echo ($activePage == 'posted_jobs') ? 'active' : ''; ?>" target="f3">Posted Jobs</a>
        <a href="applicant_list.php" class="list-group-item list-group-item-action <?php echo ($activePage == 'applicant_list') ? 'active' : ''; ?>" target="f3">Applicant List</a>
        <a href="applied_applicants.php" class="list-group-item list-group-item-action <?php echo ($activePage == 'applied_applicants') ? 'active' : ''; ?>" target="f3">Applied Applicants</a>
        <a href="../company_page.php?action=logout" class="list-group-item list-group-item-action text-danger" target="__parent">Logout</a>
    </div>

    <!-- Bootstrap JS and jQuery (for optional features) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        $('.list-group-item').on('click', function() {
            $('.list-group-item').removeClass('active');
            $(this).addClass('active');
        });
    </script>
</body>

</html>
