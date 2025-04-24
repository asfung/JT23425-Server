<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller {

  public function index(Request $request) {
    try {
      $query = Pegawai::query()->with('unitKerja', 'media')->orderBy('no', 'asc');
  
      if ($request->filled('unit_kerja_id')) {
        $query->where('unit_kerja_id', $request->unit_kerja_id);
      }
  
      if ($request->filled('jabatan')) {
        // $query->where('jabatan', 'like', '%' . $request->jabatan . '%');
        $query->where('jabatan', $request->jabatan);
      }
  
      if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
          $q->where('nama', 'like', '%' . $request->search . '%')
            ->orWhere('nip', 'like', '%' . $request->search . '%');
        });
      }
  
      $perPage = $request->input('per_page', 10); 
      $page = $request->input('page', 1);
  
      $results = $query->paginate($perPage, ['*'], 'page', $page);
  
      if ($results->isEmpty()) {
        return ApiCommon::sendResponse(null, 'Tidak Ada Pegawai Ditemukan', 404, false);
      }
  
      return ApiCommon::sendPaginatedResponse($results, 'Berhasil Mendapatkan Pegawai', 200);
  
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }
  

  public function createPegawai(Request $request) {
    try {

      $data = $request->validate([
        'no' => 'nullable|string',
        'nip' => 'required|string|unique:pegawais,nip',
        'nama' => 'required|string',
        'tempat_lahir' => 'required|string',
        'tgl_lahir' => 'required',
        'alamat' => 'nullable|string',
        'jenis_kelamin' => 'required|in:L,P',
        'gol' => 'nullable|string',
        'eselon' => 'nullable|string',
        'jabatan' => 'required|string',
        'tempat_tugas' => 'nullable|string',
        'agama' => 'nullable|string',
        'unit_kerja_id' => 'required|exists:unit_kerjas,id',
        'no_hp' => 'nullable|string',
        'npwp' => 'nullable|string',
      ]);

      $data['tgl_lahir'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['tgl_lahir'])->format('Y-m-d');
      $pegawai = Pegawai::create($data);

      return ApiCommon::sendResponse($pegawai, 'Berhasil Membuat Pegawai', 201);
    } catch (\Exception  $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function showPegawai(Request $request, $id) {
    try {
      $pegawai = Pegawai::with('unitKerja', 'media')->findOrFail($id);
      return ApiCommon::sendResponse($pegawai, 'Berhasil Mendapatkan Data Pegawai', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function updatePegawai(Request $request, $id) {
    try {
      $pegawai = Pegawai::findOrFail($id);

      $data = $request->validate([
        'no' => 'sometimes|required|string',
        'nip' => 'sometimes|required|string|unique:pegawai,nip,' . $pegawai->id,
        'nama' => 'sometimes|required|string',
        'tempat_lahir' => 'sometimes|required|string',
        'tgl_lahir' => 'sometimes|required|date',
        'alamat' => 'nullable|string',
        'jenis_kelamin' => 'sometimes|required|in:L,P',
        'gol' => 'nullable|string',
        'eselon' => 'nullable|string',
        'jabatan' => 'sometimes|required|string',
        'tempat_tugas' => 'nullable|string',
        'agama' => 'nullable|string',
        'unit_kerja_id' => 'sometimes|required|exists:unit_kerjas,id',
        'no_hp' => 'nullable|string',
        'npwp' => 'nullable|string',
      ]);

      $pegawai->update($data);

      return ApiCommon::sendResponse($pegawai, 'Berhasil Update Pegawai', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function deletePegawai(Request $request, $id){
    try{
      $pegawai = Pegawai::findOrFail($id);
      $pegawai->delete();

      return ApiCommon::sendResponse(null, 'Berhasil Delete Pegawai', 200);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function uploadProfileImage(Request $request, $id) {
    try{
      $request->validate([
        'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
      ]);

      $pegawai = Pegawai::findOrFail($id);

      // Store the image
      $path = $request->file('image')->store('profile_images', 'public');

      // Create a new media record
      $media = $pegawai->media()->create([
        'path' => $path,
        'type' => 'profile_image',
      ]);

      return ApiCommon::sendResponse($media, 'Berhasil Mengupload File', 201);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }
}
