<?php
$hostname = 'localhost';
$username = 'root';
$password = '12345678';
$database = 'timilehin';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// REGISTER
if (isset($_POST['register'])) {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $p_word = password_hash($_POST['p_word'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO tbl_reg (f_name, l_name, email, p_word) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $f_name, $l_name, $email, $p_word);

    if ($stmt->execute()) {
        echo "<script>alert('You have been successfully registered'); window.location.href='portal-login.html';</script>";
    } else {
        echo "<script>alert('An error occurred: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}

// LOGIN
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $p_word = $_POST['p_word'];

    $stmt = $conn->prepare("SELECT * FROM tbl_reg WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($p_word, $row['p_word'])) {
            echo "<script>alert('Welcome sir!!!!'); window.location.href='welcome.html';</script>";
        } else {
            echo "<script>alert('Password not correct'); window.location.href='portal-login.html';</script>";
        }
    } else {
        echo "<script>alert('Username not correct'); window.location.href='portal-register.html';</script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
