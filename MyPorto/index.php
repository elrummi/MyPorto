<?php
include('koneksi.php');
session_start();

$loginError = $registerError = "";

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbl WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            header("Location: Homepage_zero.php");
            exit;
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "Username not found.";
    }
}

// Proses registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Periksa apakah password dan konfirmasi password sesuai
    if ($password === $confirmPassword) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbl (username, password) VALUES ('$username', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit;
        } else {
            $registerError = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $registerError = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <title>Welcome to MyPorto</title>
</head>
<body>
    <div class="blok1">
        <form action="index.php" method="POST">
            <div class="container_login" id="contlog">
                <div class="welcome">
                    WELCOME to <span class="text_logo1">MyPorto</span>
                </div> 
                <div class="input">
                    <input type="text" id="uname" name="username" placeholder="Username" required id>
                    <input type="password" id="pass" name="password" placeholder="Password" required>
                    <button type="submit" name="login" id="login">Login</button>
                    <p><?php echo $loginError; ?></p>
                    <a href="#" id="gapunyaAkun">Don't have an account? Register</a>   
                </div>
            </div>
        </form>

        <form action="index.php" method="POST">
            <div class="container_register" id="contreg" style="display: none;">
                <div class="welcome2">
                    WELCOME to <span class="text_logo2">MyPorto</span>
                </div> 
                <div class="input2">
                    <input type="text" class="uname2" name="username" placeholder="Username" required>
                    <input type="password" class="pass2" id="password" name="password" placeholder="Password" required>
                    <input type="password" class="conpass2" id="confirm_password" name="confirm_password" placeholder="Confirm Password"  required>
                    <button type="submit" name="register" id="register2">Register</button>
                    <p><?php echo $registerError; ?></p>
                    <a href="#" id="punyaAkun">Already have an account? Login</a>
                </div>
            </div>
        </form>
    </div>

    <div class="footer"> 
        <div class="text_logo">MyPorto</div>
    </div>

    <script>
        const loginButton = document.getElementById("gapunyaAkun");
        const regButton = document.getElementById("punyaAkun");
        const loginForm = document.getElementById("contlog");
        const regForm = document.getElementById("contreg");

        loginButton.addEventListener("click", function () {
            loginForm.style.display = "none";
            regForm.style.display = "block";
        });

        regButton.addEventListener("click", function () {
            loginForm.style.display = "block";
            regForm.style.display = "none";
        });

        document.getElementById('register2').addEventListener('click', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

