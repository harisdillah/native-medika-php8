<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable dengan Filter Tanggal</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>DataTable dengan Filter Tanggal</h1>
    <label for="date_start">Tanggal Mulai:</label>
    <input type="date" id="date_start">

    <label for="date_end">Tanggal Akhir:</label>
    <input type="date" id="date_end">

    <table id="example" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi melalui DataTables -->
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: function(data, callback, settings) {
                    var dateStart = $('#date_start').val();
                    var dateEnd = $('#date_end').val();

                    // Kirim parameter tambahan ke server
                    $.ajax({
                        url: 'data.php',
                        method: 'GET',
                        data: {
                            start: settings.start,
                            length: settings.length,
                            search: data.search.value,
                            date_start: dateStart,
                            date_end: dateEnd,
                            draw: data.draw
                        },
                        success: function(response) {
                            var jsonResponse = JSON.parse(response);
                            callback({
                                draw: jsonResponse.draw,
                                recordsTotal: jsonResponse.recordsTotal,
                                recordsFiltered: jsonResponse.recordsFiltered,
                                data: jsonResponse.data
                            });
                        }
                    });
                },
                columns: [{
                        data: 'kd_obat'
                    },
                    {
                        data: 'nm_obat'
                    },
                    {
                        data: 'Stok'
                    },
                    // Tambahkan kolom lain sesuai dengan data yang ada
                ]
            });

            // Menambahkan event listener untuk filter tanggal
            $('#date_start, #date_end').on('change', function() {
                table.ajax.reload(); // Memuat ulang data setelah filter diterapkan
            });
        });
    </script>
</body>

</html>