<?php
include "db.php";

$result = $conn->query("SELECT * FROM hibak ORDER BY id DESC");
$hibak = [];

while ($row = $result->fetch_assoc()) {
    $hibak[] = $row;
}

echo json_encode($hibak);
