<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Common\ApiCommon;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller {

  public function index(Request $request) {
    try {
      $query = Pegawai::query()->with('unitKerja', 'media')->orderBy('no', 'asc');
  
      // if ($request->filled('unit_kerja_id')) {
      //   $query->where('unit_kerja_id', $request->unit_kerja_id);
      // }
      if ($request->filled('unit_kerja_id')) {
        $unitKerjaId = $request->unit_kerja_id;

        $unitKerja = UnitKerja::with('children')->find($unitKerjaId);
        $unitIds = collect([$unitKerjaId]);

        if ($unitKerja) {
            $unitIds = $unitIds->merge($this->getAllChildUnitKerjaIds($unitKerja));
        }

        $query->whereIn('unit_kerja_id', $unitIds->unique());
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
  
  public function showPegawai(Request $request, $id) {
    try {
      $pegawai = Pegawai::with('unitKerja', 'media')->findOrFail($id);
      return ApiCommon::sendResponse($pegawai, 'Berhasil Mendapatkan Data Pegawai', 200);
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
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
      ]);

      $data['tgl_lahir'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['tgl_lahir'])->format('Y-m-d');
      unset($data['image']);
      $pegawai = Pegawai::create($data);

      if ($request->hasFile('image')) {
        $path = $request->file('image')->store('/profile_images', 'public');
        $media = $pegawai->media()->create([
          'path' => $path,
          'type' => 'profile_image',
        ]);
      }

      return ApiCommon::sendResponse($pegawai, 'Berhasil Membuat Pegawai', 201);
    } catch (\Exception  $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function updatePegawai(Request $request, $id) {
    try {
      $pegawai = Pegawai::findOrFail($id);

      $data = $request->validate([
        'no' => 'sometimes|required|string',
        'nip' => 'sometimes|required|string|unique:pegawais,nip,' . $pegawai->id,
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
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:5120',
      ]);

      if (isset($data['tgl_lahir'])) {
        $data['tgl_lahir'] = \Carbon\Carbon::parse($data['tgl_lahir'])->format('Y-m-d');
      }
      unset($data['image']);
      $pegawai->update($data);

      if ($request->hasFile('image')) {
        if ($pegawai->media) {
            Storage::delete('public/' . $pegawai->media->path);
            $pegawai->media->delete(); 
        }

        $path = $request->file('image')->store('/profile_images', 'public');
        $pegawai->media()->create([
            'path' => $path,
            'type' => 'profile_image',
        ]);
    }


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

  public function getImage($id) {
    try{
      $pegawai = Pegawai::with('media')->findOrFail($id);

      if (!$pegawai->media || !$pegawai->media->path) {
        return ApiCommon::sendResponse(null, 'not found', 404, false);
      }

      return response()->file(storage_path('app/public/' . $pegawai->media->path));
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  private function getAllChildUnitKerjaIds($unitKerja) {
    $ids = collect();
    foreach ($unitKerja->children as $child) {
      $ids->push($child->id);
      $ids = $ids->merge($this->getAllChildUnitKerjaIds($child));
    }
    return $ids;
  }

  public function exportPegawaiPdf(Request $request) {
    try {
      $query = Pegawai::query()->with('unitKerja', 'media')->orderBy('no', 'asc');

      if ($request->filled('unit_kerja_id')) {
        $unitKerjaId = $request->unit_kerja_id;
        $unitKerja = UnitKerja::with('children')->find($unitKerjaId);
        $unitIds = collect([$unitKerjaId]);

        if ($unitKerja) {
          $unitIds = $unitIds->merge($this->getAllChildUnitKerjaIds($unitKerja));
        }

        $query->whereIn('unit_kerja_id', $unitIds->unique());
      }

      if ($request->filled('jabatan')) {
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

      $pegawais = $query->paginate($perPage, ['*'], 'page', $page);

      if ($pegawais->isEmpty()) {
        return ApiCommon::sendResponse(null, 'Tidak Ada Pegawai Ditemukan', 404, false);
      }

      // dump($pegawais->items());
      $pdf = Pdf::loadView('pdf.pegawai', ['pegawais' => $pegawais->items()]);
      return $pdf->download('daftar_pegawai.pdf');
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

}