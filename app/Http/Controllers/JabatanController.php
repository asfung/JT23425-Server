<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller {

  public function createJabatan(Request $request) {
    try{
      $data = $request->validate([
        'name' => 'required|string',
        'unit_kerja_id' => 'required|exists:unit_kerjas,id',
      ]);

      $isExists = Jabatan::where('name', $data['name'])
        ->where('unit_kerja_id', $data['unit_kerja_id'] ?? null)
        ->exists();

      if ($isExists) {
        return ApiCommon::sendResponse(null, 'Name Already Taken', 400);
      }


      $jabatan = Jabatan::create($data);

      return ApiCommon::sendResponse($jabatan, 'Berhasil Membuat Unit Kerja', 201);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function updateJabatan(Request $request, $id) {
    try{
      $jabatan = Jabatan::findOrFail($id);
      $data = $request->validate([
        'name' => 'sometimes|required|string',
        'unit_kerja_id' => 'sometimes|required|exists:unit_kerjas,id',
      ]);

      $name = $data['name'] ?? $jabatan->name;
      $unitKerjaId = $data['unit_kerja_id'] ?? $jabatan->unit_kerja_id;

      $isExists = Jabatan::where('name', $name)
        ->where('unit_kerja_id', $unitKerjaId)
        ->where('id', '!=', $jabatan->id)
        ->exists();

      if ($isExists) {
        return ApiCommon::sendResponse(null, 'Name Already Taken', 400);
      }


      $jabatan->update($data);

      return ApiCommon::sendResponse($jabatan, 'Berhasil Update Jabatan', 200);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function deleteJabatan($id) {
    try{
      $jabatan = Jabatan::findOrFail($id);
      $jabatan->delete();

      return ApiCommon::sendResponse(null, 'Berhasil Delete Jabatan', 200);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }
}
