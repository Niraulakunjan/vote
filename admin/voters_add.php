<?php
    include 'includes/session.php';

    if (isset($_POST['add'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];

        // Generate a random plain password for the user
        $password_plain = substr(str_shuffle('asdfghjklqwertyuiopzxcvbnm1234567890'), 0, 8);

        // Hash the generated password
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        $filename = $_FILES['photo']['name'];

        // Photo upload logic remains unchanged
        if (!empty($filename)) {
            move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
        }

        // Generate a random voter ID
        $voter_id = substr(str_shuffle('123456789'), 0, 5);

        // Insert voter data into the database
        $sql = "INSERT INTO voters (voters_id, password, firstname, lastname, email, photo) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $voter_id, $password_hashed, $firstname, $lastname, $email, $filename);

        if ($stmt->execute()) {
            // Call the Python script to send the email
            $command = escapeshellcmd("python3 send_email.py '$email' '$voter_id' '$password_plain'");
            $output = shell_exec($command);

            if ($output) {
                $_SESSION['success'] = 'Voter added successfully and email sent.';
            } else {
                $_SESSION['error'] = 'Voter added, but failed to send email.';
            }
        } else {
            $_SESSION['error'] = 'Database error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = 'Fill up add form first.';
    }

    header('location: voters.php');
    exit();
?>
