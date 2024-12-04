<?php
// Define the image path
$imagePath = '../images/admin.jpg';

// Check if the file exists
if (file_exists($imagePath)) {
    echo "<img src='$imagePath' alt='Example Image'>";
} else {
    echo "Image not found.";
}
?>
