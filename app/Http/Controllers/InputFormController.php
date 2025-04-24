<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use Illuminate\Http\Request;

class InputFormController extends Controller {

  public function golongans() {
    try {
      $golongans = [
        ['id' => 1, 'name' => 'I/a'],
        ['id' => 2, 'name' => 'I/b'],
        ['id' => 3, 'name' => 'I/c'],
        ['id' => 4, 'name' => 'I/d'],
        
        ['id' => 5, 'name' => 'II/a'],
        ['id' => 6, 'name' => 'II/b'],
        ['id' => 7, 'name' => 'II/c'],
        ['id' => 8, 'name' => 'II/d'],

        ['id' => 9, 'name' => 'III/a'],
        ['id' => 10, 'name' => 'III/b'],
        ['id' => 11, 'name' => 'III/c'],
        ['id' => 12, 'name' => 'III/d'],

        ['id' => 13, 'name' => 'IV/a'],
        ['id' => 14, 'name' => 'IV/b'],
        ['id' => 15, 'name' => 'IV/c'],
        ['id' => 16, 'name' => 'IV/d'],
        ['id' => 17, 'name' => 'IV/e'],
      ];

      return ApiCommon::sendResponse($golongans, 'Berhasil Dapat Data Golongan', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function eselons() {
    try {
      $eselons = [
        ['id' => 1, 'name' => 'I'],
        ['id' => 2, 'name' => 'II'],
        ['id' => 3, 'name' => 'III'],
        ['id' => 4, 'name' => 'IV'],
        // ['id' => 1, 'name' => 'Ia'],
        // ['id' => 2, 'name' => 'Ib'],
        // ['id' => 3, 'name' => 'IIa'],
        // ['id' => 4, 'name' => 'IIb'],
        // ['id' => 5, 'name' => 'IIIa'],
        // ['id' => 6, 'name' => 'IIIb'],
        // ['id' => 7, 'name' => 'IVa'],
        // ['id' => 8, 'name' => 'IVb'],
      ];

      return ApiCommon::sendResponse($eselons, 'Berhasil Dapat Data Eselon', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function jenisKelamin() {
    try {
      $jenis_kelamin = [
        ['id' => 1, 'name' => 'L'],
        ['id' => 2, 'name' => 'P'],
      ];
      return ApiCommon::sendResponse($jenis_kelamin, 'Berhasil Dapat Data Jenis Kelamin', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function agama() {
    try {
      $jenis_kelamin = [
        ['id' => 1, 'name' => 'Islam'],
        ['id' => 2, 'name' => 'Kristen Protestan'],
        ['id' => 3, 'name' => 'Kristen Katolik'],
        ['id' => 4, 'name' => 'Hindu'],
        ['id' => 5, 'name' => 'Buddha'],
        ['id' => 6, 'name' => 'Konghucu'],
      ];
      return ApiCommon::sendResponse($jenis_kelamin, 'Berhasil Dapat Data Jenis Kelamin', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }

  public function cities(Request $request){
    try{
      // $q = $request->has('q') ? $request->input('q') : null;
      $q = $request->input('q');

      $citiesData = \Indonesia::search($q)->allCities();
      return ApiCommon::sendResponse($citiesData, 'Berhasil Mengambil City', 200);

    }catch(\Exception $e){
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }

  }

}
