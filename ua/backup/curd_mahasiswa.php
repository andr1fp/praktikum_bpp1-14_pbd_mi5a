<?php
        session_start();
        // Check if the user is logged in with appropriate credentials
        if (!isset($_SESSION['username']) || ($_SESSION['jenisuser'] !== '0' && $_SESSION['level'] !== '11')) {
            header("location: login.php"); // Redirect to the login page
            exit;
        }

        require("../sistem/koneksi.php");
        $hub = open_connection();
        $a = isset($_GET["a"]) ? $_GET["a"] : '';
        $id = isset($_GET["id"]) ? $_GET["id"] : '';
        $sql = isset($_POST["sql"]) ? $_POST["sql"] : '';

        switch ($sql) {
            case "create":
                create_mahasiswa();
                break;
            case "update":
                update_mahasiswa();
                break;
            case "delete":
                delete_mahasiswa();
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

        function read_data()
        {
            global $hub;
            $query = "SELECT mahasiswa.*, dt_prodi.nmprodi 
                      FROM mahasiswa
                      INNER JOIN dt_prodi ON mahasiswa.idprodi = dt_prodi.idprodi";
            $result = mysqli_query($hub, $query);
            ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CRUD Mahasiswa Keren</title>

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

        th,
        td {
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
            <h2>Read Data Mahasiswa</h2>
            <table border=1 cellpadding=2>
                <tr>
                    <td colspan="5">
                        <a href="curd_mahasiswa.php?a=input">INPUT</a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>NPM</td>
                    <td>Nama Mahasiswa</td>
                    <td>Program Studi</td>
                    <td>AKSI</td>
                </tr>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['idmhs']; ?></td>
                        <td><?php echo $row['npm']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['nmprodi']; ?></td>
                        <td>
                            <a href="curd_mahasiswa.php?a=edit&id=<?php echo $row['idmhs']; ?>">EDIT</a>
                            <a href="curd_mahasiswa.php?a=hapus&id=<?php echo $row['idmhs']; ?>">HAPUS</a>
                        </td>
                    </tr>
                <?php
            }
            ?>
            </table>
        <?php
    }

    function input_data()
    {
        global $hub;
        $query_prodi = "SELECT * FROM dt_prodi";
        $result_prodi = mysqli_query($hub, $query_prodi);
        $row = array(
            "npm" => "",
            "nama" => "",
            "idprodi" => ""
        );
        ?>
        <h2>Input Data Mahasiswa</h2>
        <form action="curd_mahasiswa.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="create">
            <label for="npm">NPM:</label>
            <input type="text" name="npm" id="npm" maxlength="8" size="8"
                value="<?php echo htmlspecialchars(trim($row["npm"])); ?>" required /><br>
            <label for="nama">Nama Mahasiswa:</label>
            <input type="text" name="nama" id="nama" maxlength="50" size="50"
                value="<?php echo htmlspecialchars(trim($row["nama"])); ?>" required /><br>
            <label>Program Studi:</label>
            <?php
            while ($prodi = mysqli_fetch_array($result_prodi)) {
                ?>
                <input type="radio" name="idprodi"
                    value="<?php echo $prodi['idprodi']; ?>"<?php if ($row["idprodi"] == $prodi['idprodi']) {
                                                                                                    echo "checked";
                                                                                                } ?>>
                <?php echo $prodi['nmprodi']; ?>
            <?php
        }
        ?>
            <br><input type="submit" name="action" value="Simpan">
            <a href="curd_mahasiswa.php?a=list">Batal</a>
        </form>
    <?php
    }

    function edit_data($id)
    {
        global $hub;
        $query_prodi = "SELECT * FROM dt_prodi";
        $result_prodi = mysqli_query($hub, $query_prodi);
        $query = "SELECT * FROM mahasiswa WHERE idmhs = $id";
        $result = mysqli_query($hub, $query);
        $row = mysqli_fetch_array($result);
        ?>
        <h2>Edit Data Mahasiswa</h2>
        <form action="curd_mahasiswa.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="update">
            <input type="hidden" name="idmhs" value="<?php echo htmlspecialchars($id); ?>">
            <label for="npm">NPM:</label>
            <input type="text" name="npm" id="npm" maxlength="8" size="8"
                value="<?php echo htmlspecialchars($row["npm"]); ?>" required /><br>
            <label for="nama">Nama Mahasiswa:</label>
            <input type="text" name="nama" id="nama" maxlength="50" size="50"
                value="<?php echo htmlspecialchars($row["nama"]); ?>" required /><br>
            <label>Program Studi:</label>
            <?php
            while ($prodi = mysqli_fetch_array($result_prodi)) {
                ?>
                <input type="radio" name="idprodi"
                value="<?php echo $prodi['idprodi']; ?>" <?php if ($row["idprodi"] == $prodi['idprodi']) {
                                                                                                    echo "checked";
                                                                                                } ?>>
                <?php echo $prodi['nmprodi']; ?>
            <?php
        }
        ?>
            <br><input type="submit" name="action" value="Simpan">
            <a href="curd_mahasiswa.php?a=list">Batal</a>
        </form>
    <?php
    }

    function hapus_data($id)
    {
        global $hub;
        $query_prodi = "SELECT * FROM dt_prodi";
        $result_prodi = mysqli_query($hub, $query_prodi);
        $query = "SELECT * FROM mahasiswa WHERE idmhs = $id";
        $result = mysqli_query($hub, $query);
        $row = mysqli_fetch_array($result);
        ?>
        <h2>Hapus Data Mahasiswa</h2>
        <form action="curd_mahasiswa.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="delete">
            <input type="hidden" name="idmhs" value="<?php echo htmlspecialchars($id); ?>">
            <table>
                <tr>
                    <td width=100>NPM</td>
                    <td><?php echo htmlspecialchars($row["npm"]); ?></td>
                </tr>
                <tr>
                    <td>Nama Mahasiswa</td>
                    <td><?php echo htmlspecialchars($row["nama"]); ?></td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td><?php echo htmlspecialchars($row["idprodi"]); ?></td>
                </tr>
            </table>
            <br><input type="submit" name="action" value="Hapus">
            <a href="curd_mahasiswa.php?a=list">Batal</a>
        </form>
    <?php
    }

    function create_mahasiswa()
    {
        global $hub;
        global $_POST;
        $npm = mysqli_real_escape_string($hub, $_POST["npm"]);
        $nama = mysqli_real_escape_string($hub, $_POST["nama"]);
        $idprodi = mysqli_real_escape_string($hub, $_POST["idprodi"]);

        $query  = "INSERT INTO `mahasiswa` (`npm`, `nama`, `idprodi`) VALUES ";
        $query .= "('$npm', '$nama', '$idprodi')";
        mysqli_query($hub, $query) or die(mysqli_error($hub));
    }

    function update_mahasiswa()
    {
        global $hub;
        global $_POST;
        $idmhs = mysqli_real_escape_string($hub, $_POST["idmhs"]);
        $npm = mysqli_real_escape_string($hub, $_POST["npm"]);
        $nama = mysqli_real_escape_string($hub, $_POST["nama"]);
        $idprodi = mysqli_real_escape_string($hub, $_POST["idprodi"]);

        $query  = "UPDATE `mahasiswa` SET npm='$npm', nama='$nama', idprodi='$idprodi' WHERE idmhs=$idmhs";
        mysqli_query($hub, $query) or die(mysqli_error($hub));
    }

    function delete_mahasiswa()
    {
        global $hub;
        global $_POST;
        $idmhs = mysqli_real_escape_string($hub, $_POST["idmhs"]);

        $query  = "DELETE FROM `mahasiswa` WHERE idmhs=$idmhs";
        mysqli_query($hub, $query) or die(mysqli_error($hub));
    }
    ?>
    </div>
</body>

</html>
