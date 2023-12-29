<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Program Studi</title>
  
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        border-radius: 5px 5px 0 0;
        margin-top: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        transition: background-color 0.3s ease;
    }

    th {
        background-color: #007bff;
        color: #fff;
        position: relative;
    }

    th:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, #007bff, #0056b3);
        opacity: 0.6;
        z-index: -1;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e2e6ea;
    }

    .actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .actions a {
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .actions a.input {
        background-color: #007bff;
        color: #fff;
    }

    .actions a.input:hover {
        background-color: #0056b3;
    }

    .actions a.delete {
        background-color: #dc3545;
        color: #fff;
    }

    .actions a.delete:hover {
        background-color: #c82333;
    }

    .actions a.update {
        background-color: #28a745;
        color: #fff;
    }

    .actions a.update:hover {
        background-color: #218838;
    }

    .form-input {
        margin-top: 20px;
    }

    .form-input label {
        display: block;
        margin-bottom: 5px;
        color: #495057;
    }

    .form-input input[type="text"],
    .form-input input[type="radio"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-input input[type="radio"] {
        width: auto;
        margin-right: 10px;
    }

    .form-input input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .form-input input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .form-input a {
        text-decoration: none;
        margin-left: 10px;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .form-input a:hover {
        color: #0056b3;
    }
</style>

</head>

<body>
    <div class="container">
        
<?php
require("../sistem/koneksi.php");
$hub = open_connection();
$a = isset($_GET["a"]) ? $_GET["a"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
$sql = isset($_POST["sql"]) ? $_POST["sql"] : '';

switch ($sql) {
    case "create":
        create_prodi();
        break;
    case "update":
        update_prodi();
        break;
    case "delete":
        delete_prodi();
        break;
}

switch ($a) {
    case "list":
        read_data();
        break;
    case "input":
        input_data();
        break;
    case "edit":
        edit_data($id);
        break;
    case "hapus":
        hapus_data($id);
        break;
    default:
        read_data();
        break;
}

mysqli_close($hub);

function read_data() {
    global $hub;
    $query = "SELECT * FROM dt_prodi";
    $result = mysqli_query($hub, $query);
    ?>
    <h2>Read Data Program Studi</h2>
    <table border=1 cellpadding=2>
    <tr><td colspan="5">
    <a href="curd_prodi.php?a=input">INPUT</a>
    </td></tr>
    <tr>
        <td>ID</td>
        <td>KODE</td>
        <td>NAMA PRODI</td>
        <td>AKREDITASI</td>
        <td>AKSI</td></tr>
    <?php
    while($row = mysqli_fetch_array($result)) {
        ?>
        <tr>
        <td><?php echo $row['idprodi']; ?></td>
        <td><?php echo $row['kdprodi']; ?></td>
        <td><?php echo $row['nmprodi']; ?></td>
        <td><?php echo $row['akreditasi']; ?></td>
        <td>
        <a href="curd_prodi.php?a=edit&id=<?php echo $row['idprodi']; ?>">EDIT</a>
        <a href="curd_prodi.php?a=hapus&id=<?php echo $row['idprodi']; ?>">HAPUS</a>
        </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <?php
}
function input_data() {
    $row = array(
        "kdprodi" => "",
        "nmprodi" => "",
        "akreditasi" => "-"
    );
    ?>
    <h2>Input Data Program Studi</h2>
    <form action="curd_prodi.php?a=list" method="post" class="form-input">
    <input type="hidden" name="sql" value="create">
    <label for="kdprodi">Kode Prodi:</label>
    <input type="text" name="kdprodi" id="kdprodi" maxlength="6" size="6" value="<?php echo htmlspecialchars(trim($row["kdprodi"])); ?>" required /><br>
    <label for="nmprodi">Nama Prodi:</label>
    <input type="text" name="nmprodi" id="nmprodi" maxlength="70" size="70" value="<?php echo htmlspecialchars(trim($row["nmprodi"])); ?>" required /><br>
    <label>Akreditasi Prodi:</label>
    <input type="radio" name="akreditasi" value="-" <?php if($row["akreditasi"] == '-' || $row["akreditasi"] == '') { echo "checked"; } ?>>
    -
    <input type="radio" name="akreditasi" value="A" <?php if($row["akreditasi"] == 'A') { echo "checked"; } ?>>
    A
    <input type="radio" name="akreditasi" value="B" <?php if($row["akreditasi"] == 'B') { echo "checked"; } ?>>
    B
    <input type="radio" name="akreditasi" value="C" <?php if($row["akreditasi"] == 'C') { echo "checked"; } ?>>
    C
    <br><input type="submit" name="action" value="Simpan">
    <a href="curd_prodi.php?a=list">Batal</a>
    </form>
    <?php
}

function edit_data($id) {
    global $hub;
    $query = "SELECT * FROM dt_prodi WHERE idprodi = $id";
    $result = mysqli_query($hub, $query);
    $row = mysqli_fetch_array($result);
    ?>
    <h2>Edit Data Program Studi</h2>
    <form action="curd_prodi.php?a=list" method="post" class="form-input">
    <input type="hidden" name="sql" value="update">
    <input type="hidden" name="idprodi" value="<?php echo htmlspecialchars($id); ?>">
    <label for="kdprodi">Kode Prodi:</label>
    <input type="text" name="kdprodi" id="kdprodi" maxlength="6" size="6" value="<?php echo htmlspecialchars($row["kdprodi"]); ?>" required /><br>
    <label for="nmprodi">Nama Prodi:</label>
    <input type="text" name="nmprodi" id="nmprodi" maxlength="70" size="70" value="<?php echo htmlspecialchars($row["nmprodi"]); ?>" required /><br>
    <label>Akreditasi Prodi:</label>
    <input type="radio" name="akreditasi" value="-" <?php if($row["akreditasi"] == '-' || $row["akreditasi"] == '') { echo "checked"; } ?>>
    -
    <input type="radio" name="akreditasi" value="A" <?php if($row["akreditasi"] == 'A') { echo "checked"; } ?>>
    A
    <input type="radio" name="akreditasi" value="B" <?php if($row["akreditasi"] == 'B') { echo "checked"; } ?>>
    B
    <input type="radio" name="akreditasi" value="C" <?php if($row["akreditasi"] == 'C') { echo "checked"; } ?>>
    C
    <br><input type="submit" name="action" value="Simpan">
    <a href="curd_prodi.php?a=list">Batal</a>
    </form>
    <?php
}

function hapus_data($id) {
    global $hub;
    $query = "SELECT * FROM dt_prodi WHERE idprodi = $id";
    $result = mysqli_query($hub, $query);
    $row = mysqli_fetch_array($result);
    ?>
    <h2>Hapus Data Program Studi</h2>
    <form action="curd_prodi.php?a=list" method="post" class="form-input">
    <input type="hidden" name="sql" value="delete">
    <input type="hidden" name="idprodi" value="<?php echo htmlspecialchars($id); ?>">
    <table>
    <tr><td width=100>Kode</td><td><?php echo htmlspecialchars($row["kdprodi"]); ?></td></tr>
    <tr><td>Nama Prodi</td><td><?php echo htmlspecialchars($row["nmprodi"]); ?></td></tr>
    <tr><td>Akreditasi</td><td><?php echo htmlspecialchars($row["akreditasi"]); ?></td></tr>
    </table>
    <br><input type="submit" name="action" value="Hapus">
    <a href="curd_prodi.php?a=list">Batal</a>
    </form>
    <?php
}


function create_prodi() {
    global $hub;
    global $_POST;
    $kdprodi = mysqli_real_escape_string($hub, $_POST["kdprodi"]);
    $nmprodi = mysqli_real_escape_string($hub, $_POST["nmprodi"]);
    $akreditasi = mysqli_real_escape_string($hub, $_POST["akreditasi"]);

    $query  = "INSERT INTO `dt_prodi` (`kdprodi`, `nmprodi`, `akreditasi`) VALUES ";
    $query .= "('$kdprodi', '$nmprodi', '$akreditasi')";
    mysqli_query($hub, $query) or die(mysqli_error($hub));
}

function update_prodi() {
    global $hub;
    global $_POST;
    $idprodi = mysqli_real_escape_string($hub, $_POST["idprodi"]);
    $kdprodi = mysqli_real_escape_string($hub, $_POST["kdprodi"]);
    $nmprodi = mysqli_real_escape_string($hub, $_POST["nmprodi"]);
    $akreditasi = mysqli_real_escape_string($hub, $_POST["akreditasi"]);

    $query  = "UPDATE `dt_prodi` SET kdprodi='$kdprodi', nmprodi='$nmprodi', akreditasi='$akreditasi' WHERE idprodi=$idprodi";
    mysqli_query($hub, $query) or die(mysqli_error($hub));
}

function delete_prodi() {
    global $hub;
    global $_POST;
    $idprodi = mysqli_real_escape_string($hub, $_POST["idprodi"]);

    $query  = "DELETE FROM `dt_prodi` WHERE idprodi=$idprodi";
    mysqli_query($hub, $query) or die(mysqli_error($hub));
}
?>
    </div>
</body>

</html>