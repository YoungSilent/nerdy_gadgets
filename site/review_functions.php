<?php
function displayReviews($StockItemID, $stmt) {
    // Prepare the SQL query to select reviews for the specified StockItemID
    $query = "SELECT rating, beschrijving, time, date FROM reviews WHERE StockItemID = ?";

    // Prepare the statement
    $statement = $stmt->prepare($query);

    // Bind parameters
    $statement->bind_param("i", $StockItemID);

    // Execute the statement
    $statement->execute();

    // Get the result
    $result = $statement->get_result();

    // Fetch reviews as an associative array
    $reviews = $result->fetch_all(MYSQLI_ASSOC);

    // Close the statement
    $statement->close();

    // Return the reviews
    return $reviews;
}



// Function to insert a review into the database
function insertReview($StockItemID, $rating, $beschrijving, $time, $date, $conn) {
    // Check if the StockItemID is provided and not empty
    if (!isset($StockItemID) || empty($StockItemID)) {
        echo "Error: StockItemID is missing or empty.";
        return;
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO reviews (StockItemID, rating, beschrijving, time, date) VALUES (?, ?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        return;
    }

    // Bind parameters
    $stmt->bind_param("iisss", $StockItemID, $rating, $beschrijving, $time, $date);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Review added successfully.";
    } else {
        echo "Error adding review: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}


