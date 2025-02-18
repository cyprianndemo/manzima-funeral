<?php

$conn = mysqli_connect("localhost", "root", "", "manzima_db");

$id = $_GET['id']; // get id through query string

$del = mysqli_query($conn, "DELETE FROM bodies WHERE body_id = " . $id); // delete query

if($del)
{
    mysqli_close($conn); // Close connection
    header("location:claimed.php"); // redirects to all records page
    exit;	
}
else
{
    echo "Error deleting record" . $conn->error; // display error message if not delete
}
?>