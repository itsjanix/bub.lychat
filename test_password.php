<?php
$password = 'test';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Original password: $password<br>";
echo "Hashed password: $hashed_password<br>";

if (password_verify($password, $hashed_password)) {
    echo "Password verification successful.";
} else {
    echo "Password verification failed.";
}
?>
