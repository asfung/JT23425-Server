<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daftar Pegawai</title>
  <style>
    body {
      font-family: sans-serif;
      margin: 20px; 
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
    img.profile {
      max-width: 35px;
      max-height: 35px;
    }
  </style>
</head>
<body>
  <h2>
    <!-- SVG Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
      <circle cx="9" cy="7" r="4"></circle>
      <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
    </svg>
    Daftar Pegawai
  </h2>
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
        <th>Golongan</th>
        <th>Eselon</th>
        <th>Unit Kerja</th>
        <th>Jabatan</th>
        <th>Tempat Tugas</th>
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
        <td>{{ $pegawai->gol }}</td>
        <td>{{ $pegawai->eselon }}</td>
        <td>{{ $pegawai->unitKerja->label ?? '-' }}</td>
        <td>{{ $pegawai->jabatan }}</td>
        <td>{{ $pegawai->tempat_tugas }}</td>
        <td>{{ $pegawai->no_hp }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
