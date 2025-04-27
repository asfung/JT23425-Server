<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daftar Pegawai</title>
  <style>
  body {
    font-family: sans-serif;
    margin: 2px;
  }
  h2 {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-top: 20px;
    word-wrap: break-word;
    font-size: 11px;
  }
  th, td {
    border: 1px solid #000;
    padding: 6px;
    text-align: left;
    vertical-align: top;
  }
  th {
    background-color: #f2f2f2;
  }
  th:nth-child(1), td:nth-child(1) { width: 5%; }
  th:nth-child(2), td:nth-child(2) { width: 9%; }
  th:nth-child(3), td:nth-child(3) { width: 8%; }
  th:nth-child(4), td:nth-child(4) { width: 10%; }
  th:nth-child(5), td:nth-child(5) { width: 9%; }
  th:nth-child(6), td:nth-child(6) { width: 7%; }
  th:nth-child(7), td:nth-child(7) { width: 12%; }
  th:nth-child(8), td:nth-child(8) { width: 8%; }
  th:nth-child(9), td:nth-child(9) { width: 8%; }
  th:nth-child(10), td:nth-child(10) { width: 10%; }
  th:nth-child(11), td:nth-child(11) { width: 9%; }
  th:nth-child(12), td:nth-child(12) { width: 5%; }
  th:nth-child(13), td:nth-child(13) { width: 5%; }
  th:nth-child(14), td:nth-child(14) { width: 9%; }
  th:nth-child(15), td:nth-child(15) { width: 9%; }
  img.profile {
    max-width: 35px;
    max-height: 35px;
  }
</style>
</head>
<body>
  <h2>Daftar Pegawai</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Profile</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>Jenis Kelamin</th>
        <th>Unit Kerja</th>
        <th>Jabatan</th>
        <th>Tempat Tugas</th>
        <th>Gol</th>
        <th>Eselon</th>
        <th>Agama</th>
        <th>No Hp</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pegawais as $pegawai)
      <tr>
        <td>{{ $pegawai->no }}</td>
        <td>
          @if ($pegawai->media && $pegawai->media->path)
            @php
              $imagePath = storage_path('app/public/' . $pegawai->media->path);
              $imageData = base64_encode(file_get_contents($imagePath));
              $src = 'data:image/jpeg;base64,' . $imageData;
            @endphp
            <img src="{{ $src }}" class="profile" alt="Profile Image">
          @else
            @php
              $avatarName = urlencode($pegawai->nama);
              $avatarUrl = 'https://eu.ui-avatars.com/api/?background=random&name=' . $avatarName;
              $avatarImageData = base64_encode(file_get_contents($avatarUrl));
              $avatarSrc = 'data:image/svg+xml;base64,' . $avatarImageData;
            @endphp
            <img src="{{ $avatarSrc }}" class="profile" alt="Avatar Image">
          @endif
        </td>
        <td>{{ $pegawai->nip }}</td>
        <td>{{ $pegawai->nama }}</td>
        <td>{{ $pegawai->tempat_lahir }}</td>
        <td>{{ $pegawai->tgl_lahir }}</td>
        <td>{{ $pegawai->alamat }}</td>
        <td>{{ $pegawai->jenis_kelamin }}</td>
        <td>{{ $pegawai->unitKerja->label ?? '-' }}</td>
        <td>{{ $pegawai->jabatan }}</td>
        <td>{{ $pegawai->tempat_tugas }}</td>
        <td>{{ $pegawai->gol }}</td>
        <td>{{ $pegawai->eselon }}</td>
        <td>{{ $pegawai->agama }}</td>
        <td>{{ $pegawai->no_hp }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
