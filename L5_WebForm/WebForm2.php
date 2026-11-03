
<?php

// Function untuk menghitung total harga
function hitungTotalHarga($jumlah, $harga) {

    $total = $jumlah * $harga;
    $diskon = 0;

    // Diskon 10% jika total di atas Rp100.000
    if ($total > 100000) {
        $diskon = $total * 0.10;
    }

    $totalBayar = $total - $diskon;

    return [
        "total" => $total,
        "diskon" => $diskon,
        "total_bayar" => $totalBayar
    ];
}

$errors = [];
$hasil = null;

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST["btn_proses"])) {

    // Mengambil data dari form
    $produk = trim($_POST["produk"] ?? "");
    $jumlahInput = $_POST["jumlah"] ?? "";
    $hargaInput = $_POST["harga"] ?? "";

    // Validasi nama produk
    if ($produk == "") {
        $errors[] = "Nama produk wajib diisi.";
    }

    // Validasi jumlah
    if (
        $jumlahInput == "" ||
        !is_numeric($jumlahInput) ||
        (float)$jumlahInput <= 0 ||
        (int)$jumlahInput != (float)$jumlahInput
    ) {
        $errors[] = "Jumlah harus berupa bilangan bulat lebih besar dari nol.";
    }

    // Validasi harga
    if (
        $hargaInput == "" ||
        !is_numeric($hargaInput) ||
        (float)$hargaInput <= 0
    ) {
        $errors[] = "Harga harus lebih besar dari nol.";
    }

    // Jika semua validasi terpenuhi
    if (empty($errors)) {

        $jumlah = (int)$jumlahInput;
        $harga = (float)$hargaInput;

        // Memanggil function
        $hasil = hitungTotalHarga($jumlah, $harga);
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemesanan Produk</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #dde8ec;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #14263a;
        }

        .container {
            background-color: white;
            width: 55%;
            margin: 40px auto;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(20, 38, 58, 0.15);
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #c3d1d9;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #14263a;
            color: white;
        }

        .total {
            font-weight: bold;
            background-color: #f4f8fa;
        }

        .diskon {
            color: green;
            font-weight: bold;
        }

        .error {
            background-color: #fff0f0;
            border: 1px solid #e5a4a4;
            border-radius: 8px;
            padding: 12px;
            color: #9b2525;
        }

        .button {
            display: inline-block;
            text-decoration: none;
            padding: 10px 16px;
            background-color: #14263a;
            color: white;
            border-radius: 8px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #2457f5;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Hasil Pemesanan Produk</h1>

    <?php if (!isset($_POST["btn_proses"])) { ?>

        <div class="error">
            Silakan isi form pemesanan terlebih dahulu.
        </div>

    <?php } elseif (!empty($errors)) { ?>

        <div class="error">
            <b>Data belum valid:</b>

            <ul>
                <?php foreach ($errors as $error) { ?>
                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

    <?php } else { ?>

        <p>Berikut adalah rincian pemesanan produk:</p>

        <table>
            <tr>
                <th>Keterangan</th>
                <th>Hasil</th>
            </tr>

            <tr>
                <td>Nama Produk</td>
                <td>
                    <?= htmlspecialchars($produk) ?>
                </td>
            </tr>

            <tr>
                <td>Jumlah</td>
                <td>
                    <?= number_format($jumlah, 0, ',', '.') ?>
                </td>
            </tr>

            <tr>
                <td>Harga Satuan</td>
                <td>
                    Rp <?= number_format($harga, 0, ',', '.') ?>
                </td>
            </tr>

            <tr>
                <td>Total Harga</td>
                <td>
                    Rp <?= number_format($hasil["total"], 0, ',', '.') ?>
                </td>
            </tr>

            <tr>
                <td>Diskon 10%</td>
                <td class="diskon">
                    Rp <?= number_format($hasil["diskon"], 0, ',', '.') ?>
                </td>
            </tr>

            <tr class="total">
                <td>Total Bayar</td>
                <td>
                    Rp <?= number_format($hasil["total_bayar"], 0, ',', '.') ?>
                </td>
            </tr>
        </table>

        <?php if ($hasil["diskon"] > 0) { ?>

            <p>Selamat! Anda mendapatkan diskon 10%.</p>

        <?php } else { ?>

            <p>Anda belum mendapatkan diskon.</p>

        <?php } ?>

    <?php } ?>

    <br><br>

    <a href="WebForm.php" class="button">
        Kembali ke Form
    </a>

</div>

</body>
</html>