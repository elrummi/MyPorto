<?php
include('koneksi.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Mengambil user_id dari sesi
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_project = $_POST['judul_project'];
    $tanggal_project = $_POST['tanggal_project'];
    $kategori_project = $_POST['kategori_project'];
    $deskripsi_project = $_POST['deskripsi_project'];
    $link_project = $_POST['link_project'];

    // Handling file upload
    $target_dir = "uploads/";
    $file_name = basename($_FILES["foto_project"]["name"]);
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $target_file = $target_dir . time() . "_" . uniqid() . "." . $file_ext;
    $uploadOk = 1;
    $imageFileType = strtolower($file_ext);

    // Cek apakah file image merupakan file palsu atau bukan
    $check = getimagesize($_FILES["foto_project"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["foto_project"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // File yang di-upload hanyalah yang berformat JPG, JPEG, PNG
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk ter-set ke 0 karena error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["foto_project"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO daftar_project (user_id, judul_project, tanggal_project, kategori_project, deskripsi_project, link_project, foto_project) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssss", $user_id, $judul_project, $tanggal_project, $kategori_project, $deskripsi_project, $link_project, $target_file);

            if ($stmt->execute()) {
                header("Location: Homepage_zero.php");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>