<?php
// require("koneksi.php");
//     $hub=open_connection();
//         if ($hub) {
//             echo ("Koneksi SUKSES");
//             } else {
//             echo ("Koneksi GAGAL");
//         }
// mysqli_close($hub);

require("koneksi.php");

$connection = open_connection();

if ($connection) {
    echo "Koneksi SUKSES";
} else {
    echo "Koneksi GAGAL";
}
// Tidak perlu menggunakan mysqli_close() untuk koneksi PDO
?>
