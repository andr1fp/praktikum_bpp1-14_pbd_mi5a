<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("location: login.php");
        exit(); // Added exit to stop script execution after redirection
    } 
?>

<h2>
<?php 
    if($_SESSION['jenisuser'] == '0'){
        $ju = 'User-Client';
    } else {
        $ju = 'User-Admin';
    }
    echo $ju . '<hr>';
    ?>
</h2>

<h3>
<?php
    echo "Welcome " . $_SESSION['username'] . ' | <a href="sistem.php?op=out">Log Out</a>' . '
    | <a href="curd_prodi.php">Tabel Prodi</a>' . ' | <a href="curd_user.php">Tabel User</a>' .'
    | <a href="curd_mahasiswa.php">Tabel Mahasiswa</a>';
?>
</h3>
