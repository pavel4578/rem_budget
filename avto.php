<?php 
session_start();
include 'temp/bd.php';
$login = $_POST['login'];
$password = $_POST['password'];
$sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
$res = mysqli_query($mysqli, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    $user = mysqli_fetch_assoc($res);
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['fio'] = $user['fio'];
    $_SESSION['role'] = $user['role'];
    header('Location: index.php');
    exit;
} else {
    header('Location: formavto.php?error=1');
    exit;
}
?>
