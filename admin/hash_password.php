<?php

$password = 'test123';

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

echo "Plaintext Password: " . $password . "\n";
echo "Hashed Password:    " . $hashed_password . "\n";
?>