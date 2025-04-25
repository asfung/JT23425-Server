<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daftar Pegawai</title>
  <style>
    body {
      font-family: sans-serif;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
      font-size: 12px;
    }
    th {
      background-color: #f2f2f2;
    }
    img.profile {
      max-width: 50px;
      max-height: 50px;
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
        <th>Jabatan</th>
        <th>Unit Kerja</th>
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
            <img src="data:image/jpeg;base64,{{ $imageData }}" class="profile" alt="Profile Image">
          @else
            @php
              // Use the UI Avatars API to generate an avatar
              $avatarName = urlencode($pegawai->nama);
              $avatarUrl = 'https://eu.ui-avatars.com/api/?background=random&name=' . $avatarName;
              $avatarImageData = base64_encode(file_get_contents($avatarUrl));
            @endphp
            <img src="data:image/svg+xml;base64,{{ $avatarImageData }}" class="profile" alt="Avatar Image">
          @endif
        </td>
        <td>{{ $pegawai->nip }}</td>
        <td>{{ $pegawai->nama }}</td>
        <td>{{ $pegawai->jabatan }}</td>
        <td>{{ $pegawai->unitKerja->label ?? '-' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
