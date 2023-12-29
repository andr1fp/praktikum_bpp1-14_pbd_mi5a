<?php
session_start();
require("../sistem/koneksi.php");
$hub = open_connection();
$usr = mysqli_real_escape_string($hub, $_POST['usr']);
$psw = mysqli_real_escape_string($hub, $_POST['psw']);
$op = filter_input(INPUT_GET, 'op', FILTER_SANITIZE_STRING);

if ($op == "in") {
    $cek = mysqli_query($hub, "SELECT * FROM user WHERE username='$usr' AND password='$psw' AND status='F'");
    if (mysqli_num_rows($cek) == 1) {
        $c = mysqli_fetch_array($cek);
        $_SESSION['iduser'] = $c['iduser'];
        $_SESSION['username'] = $c['username'];
        $_SESSION['jenisuser'] = $c['jenisuser'];
        $update_status = mysqli_query($hub, "UPDATE user SET status='T' WHERE iduser=" . $c['iduser']);
        if (!$update_status) {
            die("Gagal melakukan proses login. Silakan coba lagi atau hubungi administrator.");
        }
        header("location:index.php");
    } else {
        die("Username/password salah atau user sedang online. Silakan coba lagi atau hubungi administrator.");
    }
    mysqli_close($hub);
} else if ($op == "out") {
    $update_status = mysqli_query($hub, "UPDATE user SET status='F' WHERE iduser=" . $_SESSION['iduser']);
    if (!$update_status) {
        die("Gagal melakukan proses logout. Silakan coba lagi atau hubungi administrator.");
    }
    unset($_SESSION['iduser']);
    unset($_SESSION['username']);
    unset($_SESSION['jenisuser']);
    header("location:index.php");
}
?>
