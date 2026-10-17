
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Identitas Siswa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 15px;
            color: #1e293b;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border-radius: 7px;
            font-size: 15px;
        }

        input, select {
            border: 1px solid #cbd5e1;
            background: white;
        }

        select:disabled {
            background: #e2e8f0;
            cursor: not-allowed;
        }

        button {
            margin-top: 22px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #1d4ed8;
        }

        .hasil {
            margin-top: 25px;
            padding: 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
        }

        .hasil h3 {
            margin-top: 0;
            color: #1d4ed8;
        }

        .error {
            margin-top: 20px;
            padding: 12px;
            color: #991b1b;
            background: #fee2e2;
            border-radius: 7px;
        }

        .info {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Formulir Identitas Siswa</h2>

    <form method="post" action="">

        <label for="nama">Nama Lengkap</label>
        <input
            type="text"
            id="nama"
            name="nama"
            maxlength="100"
            required
            value="<?= htmlspecialchars(
                $_POST['nama'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            placeholder="Masukkan nama lengkap"
        >

        <label for="jurusan">Jurusan</label>
        <select id="jurusan" name="jurusan" required>
            <option value="">-- Pilih Jurusan --</option>

            <option value="RPL"
                <?= (($_POST['jurusan'] ?? '') === 'RPL')
                    ? 'selected' : '' ?>>
                Rekayasa Perangkat Lunak (RPL)
            </option>

            <option value="TKJ"
                <?= (($_POST['jurusan'] ?? '') === 'TKJ')
                    ? 'selected' : '' ?>>
                Teknik Komputer dan Jaringan (TKJ)
            </option>

            <option value="DKV"
                <?= (($_POST['jurusan'] ?? '') === 'DKV')
                    ? 'selected' : '' ?>>
                Desain Komunikasi Visual (DKV)
            </option>
        </select>

        <label for="kelas">Kelas</label>
        <select id="kelas" name="kelas" required disabled>
            <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
        </select>

        <p class="info">
            Pilihan kelas akan menyesuaikan jurusan yang kamu pilih.
        </p>

        <button type="submit">Kirim Data</button>

    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nama = trim($_POST["nama"] ?? "");
        $jurusan = $_POST["jurusan"] ?? "";
        $kelas = $_POST["kelas"] ?? "";

        // Daftar kelas yang diizinkan untuk setiap jurusan.
        $daftarKelas = [
            "RPL" => [
                "XII RPL 1",
                "XII RPL 2"
            ],
            "TKJ" => [
                "XII TKJ 1",
                "XII TKJ 2"
            ],
            "DKV" => [
                "XII DKV 1",
                "XII DKV 2"
            ]
        ];

        $namaJurusan = [
            "RPL" => "Rekayasa Perangkat Lunak",
            "TKJ" => "Teknik Komputer dan Jaringan",
            "DKV" => "Desain Komunikasi Visual"
        ];

        // Validasi data pada server.
        if ($nama === "" || strlen($nama) > 400) {
            echo '<div class="error">';
            echo 'Nama wajib diisi dan maksimal 100 karakter.';
            echo '</div>';

        } elseif (
            !isset($daftarKelas[$jurusan]) ||
            !in_array(
                $kelas,
                $daftarKelas[$jurusan] ?? [],
                true
            )
        ) {
            echo '<div class="error">';
            echo 'Jurusan atau kelas tidak valid. ';
            echo 'Silakan pilih kembali.';
            echo '</div>';

        } else {
            echo '<div class="hasil">';
            echo '<h3>Data Berhasil Dikirim!</h3>';

            echo '<p><strong>Nama:</strong> '
                . htmlspecialchars(
                    $nama,
                    ENT_QUOTES,
                    'UTF-8'
                ) . '</p>';

            echo '<p><strong>Jurusan:</strong> '
                . htmlspecialchars(
                    $namaJurusan[$jurusan],
                    ENT_QUOTES,
                    'UTF-8'
                ) . '</p>';

            echo '<p><strong>Kelas:</strong> '
                . htmlspecialchars(
                    $kelas,
                    ENT_QUOTES,
                    'UTF-8'
                ) . '</p>';

            echo '</div>';
        }
    }
    ?>

</div>

<script>
    // Daftar kelas berdasarkan jurusan.
    const daftarKelas = {
        RPL: [
            "XII RPL 1",
            "XII RPL 2"
        ],
        TKJ: [
            "XII TKJ 1",
            "XII TKJ 2"
        ],
        DKV: [
            "XII DKV 1",
            "XII DKV 2"
        ]
    };

    const jurusanSelect = document.getElementById("jurusan");
    const kelasSelect = document.getElementById("kelas");

    // Nilai kelas sebelumnya, misalnya setelah formulir dikirim.
    const kelasSebelumnya = <?= json_encode(
        $_SERVER["REQUEST_METHOD"] === "POST"
            ? ($_POST["kelas"] ?? "")
            : "",
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    ) ?>;

    function perbaruiKelas(pilihanKelas = "") {
        const jurusan = jurusanSelect.value;

        // Hapus seluruh pilihan kelas sebelumnya.
        kelasSelect.replaceChildren();

        // Buat pilihan awal.
        const opsiAwal = document.createElement("option");
        opsiAwal.value = "";

        if (!jurusan || !daftarKelas[jurusan]) {
            opsiAwal.textContent =
                "-- Pilih Jurusan Terlebih Dahulu --";

            kelasSelect.add(opsiAwal);
            kelasSelect.disabled = true;
            return;
        }

        opsiAwal.textContent = "-- Pilih Kelas --";
        kelasSelect.add(opsiAwal);
        kelasSelect.disabled = false;

        // Tampilkan hanya kelas dari jurusan yang dipilih.
        daftarKelas[jurusan].forEach(function(namaKelas) {
            const opsi = document.createElement("option");

            opsi.value = namaKelas;
            opsi.textContent = namaKelas;

            if (namaKelas === pilihanKelas) {
                opsi.selected = true;
            }

            kelasSelect.add(opsi);
        });
    }

    // Perbarui kelas setiap kali jurusan berubah.
    jurusanSelect.addEventListener("change", function() {
        perbaruiKelas();
    });

    // Pulihkan pilihan setelah halaman dikirim oleh PHP.
    perbaruiKelas(kelasSebelumnya);
</script>

</body>
</html>
