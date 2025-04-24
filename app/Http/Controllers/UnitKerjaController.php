<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UnitKerjaController extends Controller {

  public function treeUnitKerja() {
    try{
      $units = UnitKerja::with(['children', 'jabatans'])->whereNull('parent_id')->get();
      if($units->isEmpty()){
        return ApiCommon::sendResponse(null, 'not found', 404, false);
      }
      return ApiCommon::sendResponse($units, 'Berhasil Mengambil Unit Tree', 200);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }

  }

  public function createUnitKerja(Request $request) {
    try{
      $data = $request->validate([
        'name' => 'required|string',
        'parent_id' => 'nullable|exists:unit_kerjas,id',
      ]);

      $isExists = UnitKerja::where('name', $data['name'])
        ->where('parent_id', $data['parent_id'] ?? null) 
        ->exists();
      if($isExists){
        return ApiCommon::sendResponse(null, 'Name Already Taken', 400);
      }

      $unit = UnitKerja::create($data);

      return ApiCommon::sendResponse($unit, 'Berhasil Membuat Unit Kerja', 201);
    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function updateUnitKerja(Request $request, $id) {
    try {
      $unit = UnitKerja::findOrFail($id);
      $data = $request->validate([
        'name' => 'sometimes|required|string',
        'parent_id' => 'nullable|exists:unit_kerjas,id',
      ]);

      $name = $data['name'] ?? $unit->name;
      $parentId = $data['parent_id'] ?? $unit->parent_id;

      $isExists = UnitKerja::where('name', $name)
        ->where('parent_id', $parentId)
        ->where('id', '!=', $unit->id)
        ->exists();

      if($isExists){
        return ApiCommon::sendResponse(null, 'Name Already Taken', 400);
      }

      $unit->update($data);

      return ApiCommon::sendResponse($unit, 'Berhasil Updaate Unit Kerja', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function deleteUnitKerja($id) {
    try {
      $unit = UnitKerja::findOrFail($id);
      $unit->delete();
      return ApiCommon::sendResponse(null, 'Berhasil Delete Unit Kerja', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }
}
