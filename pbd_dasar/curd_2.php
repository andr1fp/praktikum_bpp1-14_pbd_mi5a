<?php
require("../sistem/koneksi.php");
$hub = open_connection();
$a = isset($_GET["a"]) ? $_GET["a"] : '';
$sql = isset($_POST["sql"]) ? $_POST["sql"] : '';

switch ($sql) {
    case "create":
        create_prodi();
        break;
}

switch ($a) {
    case "list":
        read_data();
        break;
    case "input":
        input_data();
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
    <tr><td colspan="4">
    <a href="curd_2.php?a=input">INPUT</a>
    </td></tr>
    <tr><td>ID</td><td>KODE</td><td>NAMA PRODI</td><td>AKREDITASI</td></tr>
    <?php
    while($row = mysqli_fetch_array($result)) {
        ?>
        <tr>
        <td><?php echo $row['idprodi']; ?></td>
        <td><?php echo $row['kdprodi']; ?></td>
        <td><?php echo $row['nmprodi']; ?></td>
        <td><?php echo $row['akreditasi']; ?></td>
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
    <form action="curd_2.php?a=list" method="post">
    <input type="hidden" name="sql" value="create">
    Kode Prodi
    <input type="text" name="kdprodi" maxlength="6" size="6" value="<?php echo htmlspecialchars(trim($row["kdprodi"])); ?>" /><br>
    Nama Prodi
    <input type="text" name="nmprodi" maxlength="70" size="70" value="<?php echo htmlspecialchars(trim($row["nmprodi"])); ?>" /><br>
    Akreditasi Prodi
    <input type="radio" name="akreditasi" value="-" <?php if($row["akreditasi"] == '-' || $row["akreditasi"] == '') { echo "checked=\"checked\""; } ?>>
    -
    <input type="radio" name="akreditasi" value="A" <?php if($row["akreditasi"] == 'A') { echo "checked=\"checked\""; } ?>>
    A
    <input type="radio" name="akreditasi" value="B" <?php if($row["akreditasi"] == 'B') { echo "checked=\"checked\""; } ?>>
    B
    <input type="radio" name="akreditasi" value="C" <?php if($row["akreditasi"] == 'C') { echo "checked=\"checked\""; } ?>>
    C
    <br><input type="submit" name="action" value="Simpan"><br>
    <a href="curd_2.php?a=list">Batal</a>
    </form>
    </table>
    <?php
}

function create_prodi() {
    global $hub;
    $kdprodi = mysqli_real_escape_string($hub, $_POST["kdprodi"]);
    $nmprodi = mysqli_real_escape_string($hub, $_POST["nmprodi"]);
    $akreditasi = mysqli_real_escape_string($hub, $_POST["akreditasi"]);

    $query = "INSERT INTO `dt_prodi` (`kdprodi`, `nmprodi`, `akreditasi`) VALUES ('$kdprodi', '$nmprodi', '$akreditasi')";
    mysqli_query($hub, $query) or die(mysqli_error($hub));
}
?>
