<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['project_id'])) {
    header("Location: Homepage_zero.php");
    exit;
}

$project_id = $_POST['project_id'];

$sql = "SELECT * FROM daftar_project WHERE user_id='$user_id' AND project_id='$project_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: Homepage_zero.php");
    exit;
}

$project = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="edit_project.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>Edit Project</title>
</head>
<body>
    <div class="form-container">
        <form action="edit_project.php" method="post" enctype="multipart/form-data">
            <div class="input">
                <img src="<?= $project['foto_project']; ?>" class="tampilan" id="dispict" alt="Project Image">
                <label for="inifile" class="inifile"> Upload Image</label>
                <input id="inifile" type="file" accept="image/png, image/jpeg, image/jpg" name="foto_project">
                <input type="hidden" name="project_id" value="<?= $project['project_id']; ?>">
                <input type="text" placeholder="Title" class="title" name="judul_project" value="<?= $project['judul_project']; ?>" required>
                <input type="date" class="initanggal" name="tanggal_project" value="<?= $project['tanggal_project']; ?>" required>
                <select name="kategori_project" id="category" required>
                    <option value="" disabled>Select Project Category</option>
                    <option value="videography" <?= $project['kategori_project'] == 'videography' ? 'selected' : ''; ?>>Videography</option>
                    <option value="photography" <?= $project['kategori_project'] == 'photography' ? 'selected' : ''; ?>>Photography</option>
                    <option value="video_editing" <?= $project['kategori_project'] == 'video_editing' ? 'selected' : ''; ?>>Video Editing</option>
                    <option value="photo_editing" <?= $project['kategori_project'] == 'photo_editing' ? 'selected' : ''; ?>>Photo Editing</option>
                    <option value="frontend_development" <?= $project['kategori_project'] == 'frontend_development' ? 'selected' : ''; ?>>Frontend Development</option>
                    <option value="backend_development" <?= $project['kategori_project'] == 'backend_development' ? 'selected' : ''; ?>>Backend Development</option>
                </select>
                <textarea placeholder="Description" class="deskripsi" name="deskripsi_project" required><?= $project['deskripsi_project']; ?></textarea>
                <input type="text" placeholder="Link (Optional)" class="link" name="link_project" value="<?= $project['link_project']; ?>">
                <button type="submit" class="uploadproject" name="update_project">Update Project</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['update_project'])) {
    $judul_project = $_POST['judul_project'];
    $tanggal_project = $_POST['tanggal_project'];
    $kategori_project = $_POST['kategori_project'];
    $deskripsi_project = $_POST['deskripsi_project'];
    $link_project = $_POST['link_project'];
    
    if ($_FILES['foto_project']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto_project"]["name"]);
        move_uploaded_file($_FILES["foto_project"]["tmp_name"], $target_file);
    } else {
        $target_file = $project['foto_project']; 
    }

    $sql = "UPDATE daftar_project SET 
            judul_project='$judul_project', 
            tanggal_project='$tanggal_project', 
            kategori_project='$kategori_project', 
            deskripsi_project='$deskripsi_project', 
            link_project='$link_project', 
            foto_project='$target_file' 
            WHERE user_id='$user_id' AND project_id='$project_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: Homepage_zero.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
