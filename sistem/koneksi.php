<?php
// function open_connection()
//     {
//         $hostname="localhost";
//         $username="root";
//         $password="";
//         $dbname="akademik";
//         $koneksi=mysqli_connect($hostname,$username,$password,$dbname);
//         return $koneksi;
//     }

function open_connection()
{
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "akademik";

    try {
        $connection = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>


