<?php
include "db.php";

$tanar = $_POST["tanar"];
$gep   = $_POST["gep"];
$hiba  = $_POST["hiba"];

$sql = "INSERT INTO hibak (tanar, gep, hiba) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $tanar, $gep, $hiba);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "HIBA";
}
