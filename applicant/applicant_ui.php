<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        iframe {
            width: 100%;
            height: 100%;
            border: 1px solid #ccc; 
        }
    </style>
</head>
<body>

    <iframe src="applicant/applicant_top_frame.php" name="f1"></iframe>

    <div style="display: flex; height: 76vh;">
        <iframe src="applicant/applicant_tools.php" name="f2" style="width: 20%;"></iframe>
        <iframe src="applicant/applicant_profile.php" name="f3" style="width: 80%;"></iframe>
    </div>

</body>
</html>
