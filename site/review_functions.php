<?php
function displayReviews($StockItemID, $stmt) {
    $StockItemID = $_GET['id'];
    $query = "SELECT r.id, r.rating, r.beschrijving, r.time, r.date, r.PersonID, r.Anoniem, p.PersonID, p.PreferredName
                    FROM reviews r
                    JOIN people p ON r.PersonID = p.PersonID
                    WHERE r.StockItemID = ?";
    $statement = $stmt->prepare($query);
    $statement->bind_param("i", $StockItemID);
    $statement->execute();
    $result = $statement->get_result();
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    $statement->close();
    return $reviews;
}
function generateStarRating($rating) {
    $html = '';
    // Loop through each rating point
    for ($i = 1; $i <= 10; $i++) {
        // If the current rating point is less than or equal to the given rating, display a filled star, otherwise display an empty star
        $html .= ($i <= $rating) ? '&#9733;' : '&#9734;';
    }
    return $html;
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
function hasUserReviewedProduct($customer_id, $product_id, $conn) {
    // Query to check if the user has already posted a review for the product
    $query = "SELECT * FROM reviews WHERE PersonID = $customer_id AND StockItemID = $product_id";
    $result = mysqli_query($conn, $query);
    // Check if any rows are returned (indicating the user has already reviewed the product)
    if (mysqli_num_rows($result) > 0) {
        return true; // User has already reviewed the product
    } else {
        return false; // User has not reviewed the product
    }
}
function isMyReview($reviewID, $userID, $connection) {
    $query = "SELECT * FROM reviews WHERE id = $reviewID AND PersonID = $userID";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result === false) {
        return false; // Query failed
    }
    if (mysqli_num_rows($result) > 0) {
        return true; // Review belongs to the logged-in customer
    } else {
        return false; // Review does not belong to the logged-in customer
    }
}
function updateReview($review_id, $rating, $beschrijving, $conn) {
    $review_id = mysqli_real_escape_string($conn, $review_id);
    $rating = mysqli_real_escape_string($conn, $rating);
    $beschrijving = mysqli_real_escape_string($conn, $beschrijving);
    $sql = "UPDATE reviews SET rating = '$rating', beschrijving = '$beschrijving' WHERE id = '$review_id'";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error updating review: " . mysqli_error($conn);
        return false;
    }
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
?>
<script>
function submitFormDate() {
    document.getElementById("sortFormDate").submit();
}

function submitFormRating() {
    document.getElementById("sortFormRating").submit();
}
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit review buttons
    var editReviewBtns = document.querySelectorAll('.edit-review-btn');

    // Add click event listener to each button
    editReviewBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Toggle visibility of the associated edit review form
            var editForm = this.nextElementSibling;
            var additionalDiv = editForm.nextElementSibling;
            if (editForm.style.display === 'none' || editForm.style.display === '') {
                editForm.style.display = 'block';
                additionalDiv.style.display = 'block'
                // Hide the edit button when the form is displayed
                this.style.display = 'none';
            } else {
                editForm.style.display = 'none';
            }
        });
    });
});
</script>