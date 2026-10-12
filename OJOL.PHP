<?php
class User {
    public $nama;
    public $noHP;

    public function __construct($nama, $noHP) {
       $this->nama = $nama;
       $this->noHP = $noHP;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getStatus() {
        return "Usernya lagi aktif ni";
    }
}

class Pelanggan extends User {
    private $total;
    public $poin = 0;

    public function __construct($nama, $noHP, $total) {
        parent::__construct($nama, $noHP);
        $this->total = $total;
    }

    public function getStatus() {
       return "Pelanggan Premium";
    }

    public function tambahPoin() {
        $this->poin += floor($this->total / 10000);
    }
}

class Driver extends User {
    private $kendaraan;

    public function __construct($nama, $noHP, $kendaraan) {
        parent::__construct($nama, $noHP);
        $this->kendaraan = $kendaraan;
    }

    public function getKendaraan() {
        return $this->kendaraan;
    }

    public function terimaOrder() {
        return $this->nama . " menerima order";
    }
}

class Layanan {
    public $jenisLayanan;
    public $tarif;

    public function __construct($jenisLayanan) {
        $this->jenisLayanan = $jenisLayanan;
    }

    public function getTarif() {

        if ($this->jenisLayanan == "GoRide Reguler") {
            $this->tarif = 2500;
        }
        elseif ($this->jenisLayanan == "GoRide Prioritas") {
            $this->tarif = 3000;
        }
        elseif ($this->jenisLayanan == "GoCar") {
            $this->tarif = 4500;
        }
        elseif ($this->jenisLayanan == "GoCar XL") {
            $this->tarif = 6000;
        }
        elseif ($this->jenisLayanan == "GoFood") {
            $this->tarif = 2000;
        }
        else {
            $this->tarif = 0;
        }

        return $this->tarif;
    }
}

class Voucher {
    public $kodeVoucher;
    public $diskonPersen;

    public function __construct($kodeVoucher) {
        $this->kodeVoucher = $kodeVoucher;

        if ($kodeVoucher == "Hemat10") {
            $this->diskonPersen = 10;
        }
        elseif ($kodeVoucher == "Hemat20") {
            $this->diskonPersen = 20;
        }
        elseif ($kodeVoucher == "Hemat30") {
            $this->diskonPersen = 30;
        }
        else {
            $this->diskonPersen = 0;
        }
    }

    public function hitungDiskon($total) {
        return ($total * $this->diskonPersen) / 100;
    }
}

class Pembayaran {
    public function getMetode() {
        return "Pembayaran";
    }
}

class Ewallet extends Pembayaran {
    public function getMetode() {
        return "E-Wallet";
    }
}

class TransferBank extends Pembayaran {
    public function getMetode() {
        return "Transfer Bank";
    }
}

class Cash extends Pembayaran {
    public function getMetode() {
        return "Cash";
    }
}

$driver = new Driver("Gerry", "083807108331", "Harley Davidson");

$Nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$noHP = isset($_POST['noHP']) ? $_POST['noHP'] : '';
$jarakTempuh = isset($_POST['jarak']) ? $_POST['jarak'] : 0;
$jenisLayanan = isset($_POST['jenisLayanan']) ? $_POST['jenisLayanan'] : '';
$kodevoucher = isset($_POST['voucher']) ? $_POST['voucher'] : '';
$metodePembayaran = isset($_POST['metodePembayaran']) ? $_POST['metodePembayaran'] : '';

$tarif = 0;
$totalBiaya = 0;
$diskon = 0;
$totalBayar = 0;

$errorNama = '';
$errornoHP = '';
$errorJarak = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($Nama)) {
        $errorNama = "Nama tidak boleh kosong!";
    }

    if (strlen($noHP) < 10) {
        $errornoHP = "Nomor HP minimal 10 digit!";
    }

    if ($jarakTempuh <= 0) {
        $errorJarak = "Jarak harus lebih dari 0!";
    }

    if ($errorNama == '' && $errornoHP == '' && $errorJarak == '') {

        $layanan = new Layanan($jenisLayanan);
        $tarif = $layanan->getTarif();

        $totalBiaya = $jarakTempuh * $tarif;

        $voucher = new Voucher($kodevoucher);
        $diskon = $voucher->hitungDiskon($totalBiaya);

        $totalBayar = $totalBiaya - $diskon;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Ojek Online</title>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 20px;
}

form, .struk-box {
    background: white;
    padding: 20px;
    width: 400px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
}

input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background: green;
    color: white;
    border: none;
    cursor: pointer;
}

.error {
    color: red;
    font-weight: bold;
}
</style>

</head>
<body>

<h1>Sistem Ojek Online Premium</h1>

<form action="" method="POST">

    <label>Nama :</label><br>
    <input type="text" name="nama" value="<?= $Nama ?>"><br><br>

    <label>No HP :</label><br>
    <input type="text" name="noHP" value="<?= $noHP ?>"><br><br>

    <label>Jarak Tempuh (KM) :</label><br>
    <input type="number" name="jarak" value="<?= $jarakTempuh ?>"><br><br>

    <label>Jenis Layanan :</label><br>
    <select name="jenisLayanan">
        <option value="GoRide Reguler" <?= $jenisLayanan == 'GoRide Reguler' ? 'selected' : '' ?>>GoRide Reguler</option>
        <option value="GoRide Prioritas" <?= $jenisLayanan == 'GoRide Prioritas' ? 'selected' : '' ?>>GoRide Prioritas</option>
        <option value="GoCar" <?= $jenisLayanan == 'GoCar' ? 'selected' : '' ?>>GoCar</option>
        <option value="GoCar XL" <?= $jenisLayanan == 'GoCar XL' ? 'selected' : '' ?>>GoCar XL</option>
        <option value="GoFood" <?= $jenisLayanan == 'GoFood' ? 'selected' : '' ?>>GoFood</option>
    </select>

    <br><br>

    <label>Voucher :</label><br>
    <select name="voucher">
        <option value="">Pilih Voucher</option>
        <option value="Hemat10" <?= $kodevoucher == 'Hemat10' ? 'selected' : '' ?>>Hemat10</option>
        <option value="Hemat20" <?= $kodevoucher == 'Hemat20' ? 'selected' : '' ?>>Hemat20</option>
        <option value="Hemat30" <?= $kodevoucher == 'Hemat30' ? 'selected' : '' ?>>Hemat30</option>
    </select>

    <br><br>

    <label>Metode Pembayaran :</label><br>
    <select name="metodePembayaran">
        <option value="">Pilih Pembayaran</option>
        <option value="Ewallet" <?= $metodePembayaran == 'Ewallet' ? 'selected' : '' ?>>E-Wallet</option>
        <option value="TransferBank" <?= $metodePembayaran == 'TransferBank' ? 'selected' : '' ?>>Transfer Bank</option>
        <option value="Cash" <?= $metodePembayaran == 'Cash' ? 'selected' : '' ?>>Cash</option>
    </select>

    <br><br>

    <input type="submit" value="Hitung Biaya">

</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($errorNama != '' || $errornoHP != '' || $errorJarak != '') {

        echo "<div class='struk-box'>";
        echo "<h2>ERROR</h2>";

        if ($errorNama != '') {
            echo "<p class='error'>$errorNama</p>";
        }

        if ($errornoHP != '') {
            echo "<p class='error'>$errornoHP</p>";
        }

        if ($errorJarak != '') {
            echo "<p class='error'>$errorJarak</p>";
        }

        echo "</div>";
    }
}
?>

<?php
if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    $errorNama == '' &&
    $errornoHP == '' &&
    $errorJarak == ''
) {

echo "<div class='struk-box'>";

echo "<h2>STRUK PEMBAYARAN</h2>";

echo "<p>Nama : $Nama</p>";
echo "<p>No HP : $noHP</p>";
echo "<p>Jarak Tempuh : "   . number_format($jarakTempuh, 0, ',', '.') . " KM</p>";
echo "<p>Jenis Layanan : $jenisLayanan</p>";

echo "<p>Tarif per KM : Rp " . number_format($tarif, 0, ',', '.') . "</p>";

echo "<p>Total Biaya : Rp " . number_format($totalBiaya, 0, ',', '.') . "</p>";

echo "<p>Voucher : " . ($kodevoucher != '' ? $kodevoucher : 'Tidak ada') . "</p>";

echo "<p>Diskon : Rp " . number_format($diskon, 0, ',', '.') . "</p>";

echo "<p>Metode Pembayaran : $metodePembayaran</p>";

echo "<p>Total Bayar : Rp " . number_format($totalBayar, 0, ',', '.') . "</p>";

echo "<p>Driver : " . $driver->getNama() . "</p>";

echo "<p>Kendaraan : " . $driver->getKendaraan() . "</p>";

echo "<p>Status Driver : " . $driver->terimaOrder() . "</p>";

echo "</div>";
}
?>

</body>
</html>