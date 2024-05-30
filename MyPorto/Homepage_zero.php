<?php
session_start();
include('koneksi.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Query untuk mengambil semua proyek pengguna dari database
$sql = "SELECT * FROM daftar_project WHERE user_id='$user_id'";
$result = $conn->query($sql);

$projects = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

// Query untuk menghitung jumlah proyek per kategori
$sql = "SELECT kategori_project, COUNT(*) as count FROM daftar_project WHERE user_id='$user_id' GROUP BY kategori_project";
$result = $conn->query($sql);

$categories = [
    "videography" => 0,
    "photography" => 0,
    "video_editing" => 0,
    "photo_editing" => 0,
    "frontend_development" => 0,
    "backend_development" => 0,
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['kategori_project']] = $row['count'];
    }
}

// Data kategori dan jumlah untuk JavaScript
$chart_data = json_encode(array_map(null, array_keys($categories), array_values($categories)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Homepage_zero.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-core.min.js"></script>
    <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-radar.min.js"></script>
    <title>MyPorto</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="navbar">
                <a href="Homepage_zero.php" class="logo_benerin">
                    <div class="text_logo">MyPorto</div>
                </a>
                <nav class="nav-links">
                    <a href="Homepage_zero.php" class="home">Home</a>
                    <a href="logout.php" class="logout">Logout</a>
                </nav>
            </div>
        </div>
        <div class="main">
            <div class="canvas">
                <div id="container"></div>
            </div>
            <div class="btn">
                <button class="addproject"><a href="AddProject.html" id="iniadd"> Add Project</a></button>
            </div>
            <div class="project">
                <?php foreach ($projects as $project): ?>
                <div class="niprojek" id="project<?= $project['project_id']; ?>" data-name="p<?= $project['project_id']; ?>">
                    <div class="uname">
                        <div class="img">
                            <img src="<?= $project['foto_project']; ?>" class="tampilan" id="dispict" />
                        </div>
                    </div>
                    <div class="titleContainer">
                        <div class="title"><?= $project['judul_project']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="project_preview">
            <?php foreach ($projects as $project): ?>
            <div class="input" data-target="p<?= $project['project_id']; ?>">
                <div class="dalem">
                    <img src="<?= $project['foto_project']; ?>" id="tampilan1" />
                    <div id="titlerev"><?= $project['judul_project']; ?></div>
                    <div id="initanggal"><?= $project['tanggal_project']; ?></div>
                    <div id="deskripsi"><?= $project['deskripsi_project']; ?></div>
                    <div id="link">Link: <a href="<?= $project['link_project']; ?>" class="linkproject"><?= $project['link_project']; ?></a></div>
                </div>
                <div class="btnedit">
                    <form method="POST" action="edit_project.php">
                        <input type="hidden" name="project_id" value="<?= $project['project_id']; ?>">
                        <button type="submit" id="uploadproject">Edit Project</button>
                    </form>
                    <form method="POST" action="delete_project.php">
                        <input type="hidden" name="project_id" value="<?= $project['project_id']; ?>">
                        <button type="submit" id="uploadproject">Delete Project</button>
                    </form>
                </div>
                <div class="getback"><p id="balik">Back</p></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        anychart.onDocumentReady(function () {
            // ambil data dari PHP
            var chartData = <?= $chart_data; ?>;

            // buat radar chart
            var chart = anychart.radar();

            // set data chart
            chart.data(chartData);

            // set judul chart
            chart.title("My Abilities");

            // set id container
            chart.container("container");

            // Tampilkan radar chart
            chart.draw();
        });

        let preveiwContainer = document.querySelector(".project_preview");
        let previewBox = preveiwContainer.querySelectorAll(".input");

        document.querySelectorAll(".project .niprojek").forEach((product) => {
            product.onclick = () => {
                preveiwContainer.style.display = "flex";
                let name = product.getAttribute("data-name");
                
                previewBox.forEach((preview) => {
                    preview.classList.remove("active");
                });

                previewBox.forEach((preview) => {
                    let target = preview.getAttribute("data-target");
                    if (name == target) {
                        preview.classList.add("active");
                    }
                });
            };
        });

        previewBox.forEach((close) => {
            close.querySelector(".getback").onclick = () => {
                close.classList.remove("active");
                preveiwContainer.style.display = "none";
            };
        });
    </script>
</body>
</html>
