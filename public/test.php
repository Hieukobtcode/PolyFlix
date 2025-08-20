<?php
echo "Web server is working!<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test if Laravel is accessible
if (file_exists('../bootstrap/app.php')) {
    echo "Laravel bootstrap found!<br>";
} else {
    echo "Laravel bootstrap NOT found!<br>";
}

// Test .htaccess
if (file_exists('.htaccess')) {
    echo ".htaccess file exists<br>";
} else {
    echo ".htaccess file NOT found<br>";
}
?>
