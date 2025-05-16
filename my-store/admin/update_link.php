<?php
include('../includes/config.php');

if (isset($_POST['product_id']) && isset($_POST['buy_now_link'])) {
    $product_id = $_POST['product_id'];
    $buy_now_link = $_POST['buy_now_link'];

    $sql = "UPDATE products SET buy_now_link='$buy_now_link' WHERE id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Link updated successfully.";
    } else {
        echo "Error updating link: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>