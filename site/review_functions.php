<?php
function displayReviews($StockItemID, $stmt) {
    $query = "SELECT r.rating, r.beschrijving, r.time, r.date, p.PersonID, p.PreferredName
                    FROM reviews r
                    JOIN people p ON r.PersonID = p.PersonID
                    WHERE r.StockItemID = ?;";
    $statement = $stmt->prepare($query);
    $statement->bind_param("i", $StockItemID);
    $statement->execute();
    $result = $statement->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    $statement->close();
    return $reviews;
}




function insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $conn) {

    if (!isset($StockItemID) || empty($StockItemID)) {
        echo "Error: StockItemID is missing or empty.";
        return;
    }


    $stmt = $conn->prepare("INSERT INTO reviews (StockItemID, rating, beschrijving, time, date, PersonID) VALUES (?, ?, ?, ?, ?, ?)");


    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        return;
    }


    $stmt->bind_param("iisssi", $StockItemID, $rating, $beschrijving, $time, $date, $personID);


    if ($stmt->execute()) {
        echo "Review added successfully.";
    } else {
        echo "Error adding review: " . $stmt->error;
    }


    $stmt->close();
}


