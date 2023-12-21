<?php

function getUserData($userId) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nerdygadgets";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM people WHERE PersonID = ?";
    $statement = $conn->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $result = $statement->get_result();
    $userData = $result->fetch_assoc();

    $conn->close();

    return $userData;
}
?>
