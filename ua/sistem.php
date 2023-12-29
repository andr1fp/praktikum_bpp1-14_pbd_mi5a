<?php
session_start();
require("../sistem/koneksi_pdo.php"); // Sesuaikan dengan nama file koneksi PDO

$hub = open_connection();
$usr = filter_input(INPUT_POST, 'usr', FILTER_SANITIZE_STRING);
$psw = filter_input(INPUT_POST, 'psw', FILTER_SANITIZE_STRING);
$op = filter_input(INPUT_GET, 'op', FILTER_SANITIZE_STRING);

if ($op == "in") {
    try {
        $stmt = $hub->prepare("SELECT * FROM user WHERE username = :usr AND password = :psw AND status = 'F'");
        $stmt->bindParam(':usr', $usr, PDO::PARAM_STR);
        $stmt->bindParam(':psw', $psw, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION['iduser'] = $result['iduser'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['jenisuser'] = $result['jenisuser'];

            $update_status = $hub->prepare("UPDATE user SET status='T' WHERE iduser = :iduser");
            $update_status->bindParam(':iduser', $result['iduser'], PDO::PARAM_INT);
            $update_status->execute();

            header("location:index.php");
        } else {
            die("Username/password salah atau user sedang online. Silakan coba lagi atau hubungi administrator.");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    $hub = null;
} else if ($op == "out") {
    try {
        $update_status = $hub->prepare("UPDATE user SET status='F' WHERE iduser = :iduser");
        $update_status->bindParam(':iduser', $_SESSION['iduser'], PDO::PARAM_INT);
        $update_status->execute();

        unset($_SESSION['iduser']);
        unset($_SESSION['username']);
        unset($_SESSION['jenisuser']);

        header("location:index.php");
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    $hub = null;
}
?>
