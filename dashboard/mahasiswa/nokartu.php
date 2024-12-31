<?php
include "../../koneksi.php";

$nokartu = "";


$sql = mysqli_query($konek, "SELECT * FROM tmprfid LIMIT 1");

// Cek jika data tersedia
if ($sql && mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_array($sql);
    $nokartu = $data['nokartu'];
}
?>

<div class="mb-3">
    <label for="nokartu" class="form-label">No Kartu</label>
    <input type="text" class="form-control" id="nokartu" name="nokartu" value="<?php echo htmlspecialchars($nokartu); ?>" placeholder="Tempelkan kartu RFID Anda" required>
</div>