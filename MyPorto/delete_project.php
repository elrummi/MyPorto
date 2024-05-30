<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    $sql = "DELETE FROM daftar_project WHERE project_id='$project_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Project deleted successfully";
        header("Location: Homepage_zero.php");
    } else {
        echo "Error deleting project: " . $conn->error;
    }
}
?>
