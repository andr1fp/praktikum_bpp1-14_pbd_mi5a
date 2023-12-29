<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <!-- Include your styles here -->
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

        .form-input {
            margin-top: 20px;
        }

        .form-input label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
        }

        .form-input input[type="text"],
        .form-input input[type="password"],
        .form-input input[type="number"],
        .form-input select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
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
        session_start();
        // Check if the user is logged in with appropriate credentials
        if (!isset($_SESSION['username']) || ($_SESSION['jenisuser'] !== '1' && $_SESSION['level'] !== '10')) {
            header("location: login.php"); // Redirect to the login page
            exit;
        }

        require("../sistem/koneksi.php"); // Adjust the file name based on your PDO connection
        $hub = open_connection();
        $a = isset($_GET["a"]) ? $_GET["a"] : '';
        $id = isset($_GET["id"]) ? $_GET["id"] : '';
        $sql = isset($_POST["sql"]) ? $_POST["sql"] : '';

        switch ($sql) {
            case "create":
                create_user();
                break;
            case "update":
                update_user();
                break;
            case "delete":
                delete_user();
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

        $hub = null; // Close the PDO connection

        function read_data()
        {
            global $hub;
            $query = "SELECT * FROM user";
            $stmt = $hub->query($query);
            ?>
            <h2>User Management</h2>
            <table border=1 cellpadding=2>
                <tr>
                    <td colspan="7">
                        <a href="curd_user.php?a=input">INPUT</a>
                    </td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>Username</td>
                    <td>Password</td>
                    <td>Jenis User</td>
                    <td>Level</td>
                    <td>Status</td>
                    <td>ID Prodi</td>
                    <td>Aksi</td>
                </tr>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $row['iduser']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['jenisuser']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['idprodi']; ?></td>
                        <td>
                            <a href="curd_user.php?a=edit&id=<?php echo $row['iduser']; ?>">EDIT</a>
                            <a href="curd_user.php?a=hapus&id=<?php echo $row['iduser']; ?>">HAPUS</a>
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
        $row = array(
            "username" => "",
            "password" => "",
            "jenisuser" => "0",
            "level" => "00",
            "status" => "F",
            "idprodi" => 0
        );
        ?>
        <h2>Input User</h2>
        <form action="curd_user.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="create">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" maxlength="50" size="50" value="
                <?php echo htmlspecialchars(trim($row["username"])); ?>" required /><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" maxlength="50" size="50" value="
                <?php echo htmlspecialchars(trim($row["password"])); ?>" required /><br>
            <label for="jenisuser">Jenis User:</label>
            <select name="jenisuser">
                <option value="0" <?php if ($row["jenisuser"] == '0') echo "selected"; ?>>User-Client</option>
                <option value="1" <?php if ($row["jenisuser"] == '1') echo "selected"; ?>>User-Admin</option>
            </select><br>
            <label for="level">Level:</label>
            <select name="level">
                <option value="00" <?php if ($row["level"] == '00') echo "selected"; ?>>User-Client</option>
                <option value="10" <?php if ($row["level"] == '10') echo "selected"; ?>>Super-Admin</option>
                <option value="11" <?php if ($row["level"] == '11') echo "selected"; ?>>Admin</option>
            </select><br>
            <label for="status">Status:</label>
            <select name="status">
                <option value="F" <?php if ($row["status"] == 'F') echo "selected"; ?>>Offline</option>
                <option value="T" <?php if ($row["status"] == 'T') echo "selected"; ?>>Online</option>
            </select><br>
            <label for="idprodi">ID Prodi:</label>
            <input type="number" name="idprodi" id="idprodi" value="<?php echo htmlspecialchars(trim($row["idprodi"])); ?>" /><br>
            <br><input type="submit" name="action" value="Simpan">
            <a href="curd_user.php?a=list">Batal</a>
        </form>
    <?php
}

function edit_data($id)
{
    global $hub;
    $query = "SELECT * FROM user WHERE iduser = $id";
    $stmt = $hub->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Edit User</h2>
        <form action="curd_user.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="update">
            <input type="hidden" name="iduser" value="<?php echo htmlspecialchars($id); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" maxlength="50" size="50"
                value="<?php echo htmlspecialchars($row["username"]); ?>" required /><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" maxlength="50" size="50"
                value="<?php echo htmlspecialchars($row["password"]); ?>" required /><br>
            <label for="jenisuser">Jenis User:</label>
            <select name="jenisuser">
                <option value="0" <?php if ($row["jenisuser"] == '0') echo "selected"; ?>>User-Client</option>
                <option value="1" <?php if ($row["jenisuser"] == '1') echo "selected"; ?>>User-Admin</option>
            </select><br>
            <label for="level">Level:</label>
            <select name="level">
                <option value="00" <?php if ($row["level"] == '00') echo "selected"; ?>>User-Client</option>
                <option value="10" <?php if ($row["level"] == '10') echo "selected"; ?>>Super-Admin</option>
                <option value="11" <?php if ($row["level"] == '11') echo "selected"; ?>>Admin</option>
            </select><br>
            <label for="status">Status:</label>
            <select name="status">
                <option value="F" <?php if ($row["status"] == 'F') echo "selected"; ?>>Offline</option>
                <option value="T" <?php if ($row["status"] == 'T') echo "selected"; ?>>Online</option>
            </select><br>
            <label for="idprodi">ID Prodi:</label>
            <input type="number" name="idprodi" id="idprodi" value="<?php echo htmlspecialchars($row["idprodi"]); ?>" /><br>
            <br><input type="submit" name="action" value="Simpan">
            <a href="curd_user.php?a=list">Batal</a>
        </form>
    <?php
}

function hapus_data($id)
{
    global $hub;
    $query = "SELECT * FROM user WHERE iduser = $id";
    $stmt = $hub->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <h2>Hapus User</h2>
        <form action="curd_user.php?a=list" method="post" class="form-input">
            <input type="hidden" name="sql" value="delete">
            <input type="hidden" name="iduser" value="<?php echo htmlspecialchars($id); ?>">
            <table>
                <tr>
                    <td width=100>Username</td>
                    <td><?php echo htmlspecialchars($row["username"]); ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo htmlspecialchars($row["password"]); ?></td>
                </tr>
                <tr>
                    <td>Jenis User</td>
                    <td><?php echo htmlspecialchars($row["jenisuser"]); ?></td>
                </tr>
                <tr>
                    <td>Level</td>
                    <td><?php echo htmlspecialchars($row["level"]); ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                </tr>
                <tr>
                    <td>ID Prodi</td>
                    <td><?php echo htmlspecialchars($row["idprodi"]); ?></td>
                </tr>
            </table>
            <br><input type="submit" name="action" value="Hapus">
            <a href="curd_user.php?a=list">Batal</a>
        </form>
    <?php
}

function create_user()
{
    global $hub;
    global $_POST;
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $jenisuser = htmlspecialchars(trim($_POST["jenisuser"]));
    $level = htmlspecialchars(trim($_POST["level"]));
    $status = htmlspecialchars(trim($_POST["status"]));
    $idprodi = htmlspecialchars(trim($_POST["idprodi"]));

    $query  = "INSERT INTO `user` (`username`, `password`, `jenisuser`, `level`, `status`, `idprodi`) VALUES ";
    $query .= "(:username, :password, :jenisuser, :level, :status, :idprodi)";
    $stmt = $hub->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':jenisuser', $jenisuser);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idprodi', $idprodi);
    $stmt->execute();
}

function update_user()
{
    global $hub;
    global $_POST;
    $iduser = htmlspecialchars(trim($_POST["iduser"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $jenisuser = htmlspecialchars(trim($_POST["jenisuser"]));
    $level = htmlspecialchars(trim($_POST["level"]));
    $status = htmlspecialchars(trim($_POST["status"]));
    $idprodi = htmlspecialchars(trim($_POST["idprodi"]));

    $query  = "UPDATE `user` SET username=:username, password=:password,
                jenisuser=:jenisuser, level=:level, status=:status, idprodi=:idprodi WHERE iduser=:iduser";
    $stmt = $hub->prepare($query);
    $stmt->bindParam(':iduser', $iduser);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':jenisuser', $jenisuser);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idprodi', $idprodi);
    $stmt->execute();
}

function delete_user()
{
    global $hub;
    global $_POST;
    $iduser = htmlspecialchars(trim($_POST["iduser"]));

    $query  = "DELETE FROM `user` WHERE iduser=:iduser";
    $stmt = $hub->prepare($query);
    $stmt->bindParam(':iduser', $iduser);
    $stmt->execute();
}
?>
    </div>
</body>
</html>
