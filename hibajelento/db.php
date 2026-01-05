<?php
$conn = new mysqli("localhost", "root", "", "trombitarez");

if ($conn->connect_error) {
    die("Adatbázis hiba");
}
?>