<!DOCTYPE html>
<html>

<head>
    <title>Export</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 3cm;">NIDN</th>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 8cm;">NAMA DOSEN
                </th>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 5cm;">KODE
                    MATAKULIAH</th>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 10cm;">NAMA
                    MATAKULIAH</th>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 5cm;">SKS</th>
                <th align="center" style="vertical-align: middle; font-weight: bold; word-wrap: break-word; width: 8cm;">NAMA PRODI
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sgas as $s)
                <tr>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ $s->nidn }}</td>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ $s->nama }}</td>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ $s->kode_matakuliah }}</td>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ $s->nama_matakuliah }}</td>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ ($s->sks * $s->kelas) / $s->total_dosen }}</td>
                    <td align="center" style="vertical-align: middle; word-wrap: break-word;">
                        {{ $s->nama_prodi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
