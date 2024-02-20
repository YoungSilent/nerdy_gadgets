<?php
function displayReviews($StockItemID, $stmt) {
    $StockItemID = $_GET['id'];
    $query = "SELECT r.rating, r.beschrijving, r.time, r.date, r.PersonID, r.Anoniem, p.PersonID, p.PreferredName
                    FROM reviews r
                    JOIN people p ON r.PersonID = p.PersonID
                    WHERE r.StockItemID = ?";
    $statement = $stmt->prepare($query);
    $statement->bind_param("i", $StockItemID);
    $statement->execute();
    $result = $statement->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    $statement->close();
//    var_dump($reviews);
//    var_dump($query);
    return $reviews;
}


function can_leave_review($conn, $customer_id, $product_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE CustomerID = ? AND StockItemID = ?");
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return count($orders) > 0;
}


function insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem, $conn) {

    if (!isset($StockItemID) || empty($StockItemID)) {
        echo "Error: StockItemID is missing or empty.";
        return;
    }
    $stmt = $conn->prepare("INSERT INTO reviews (StockItemID, rating, beschrijving, time, date, PersonID, Anoniem) VALUES (?, ?, ?, ?, ?, ?, ?)");


    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        return;
    }


    $stmt->bind_param("iisssii", $StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem);


    if ($stmt->execute()) {
        echo "Review succesvol toegevoegd" . "<br>";
    } else {
        echo "Error adding review: " . $stmt->error;
    }


    $stmt->close();
}


