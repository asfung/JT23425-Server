<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InputFormController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UnitKerjaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:api'], function ($router) {
    $router->group(['prefix' => '/pegawai'], function ($router) {
        $router->get('/', [PegawaiController::class, 'index']);
        $router->get('/ExportPdf', [PegawaiController::class, 'exportPegawaiPdf']);
        // $router->get('/{id}', [PegawaiController::class, 'showPegawai']);
        $router->post('/Create', [PegawaiController::class, 'createPegawai']);
        $router->post('/{id}/Update', [PegawaiController::class, 'updatePegawai']);
        $router->delete('/{id}/Delete', [PegawaiController::class, 'deletePegawai']);
        $router->get('/{id}/ProfileImage', [PegawaiController::class, 'getImage']);
    });
    $router->group(['prefix' => '/unit-kerja'], function ($router) {
        $router->get('/', function () {
            return response()->json('test', 200);
        });
        $router->post('/Create', [UnitKerjaController::class, 'createUnitKerja']);
        $router->post('/{id}/Update', [UnitKerjaController::class, 'updateUnitKerja']);
        $router->delete('/{id}/Delete', [UnitKerjaController::class, 'deleteUnitKerja']);
        $router->get('/tree', [UnitKerjaController::class, 'treeUnitKerja']);
    });
    $router->group(['prefix' => '/jabatan'], function ($router) {
        $router->post('/Create', [JabatanController::class, 'createJabatan']);
        $router->post('/{id}/Update', [JabatanController::class, 'updateJabatan']);
        $router->delete('/{id}/Delete', [JabatanController::class, 'deleteJabatan']);
    });
    $router->group(['prefix' => '/form'], function ($router) {
        $router->get('/golongan', [InputFormController::class, 'golongans']);
        $router->get('/eselon', [InputFormController::class, 'eselons']);
        $router->get('/jenis-kelamin', [InputFormController::class, 'jenisKelamin']);
        $router->get('/unit-kerja', [InputFormController::class, 'unitKerja']);
        $router->get('/{id}/jabatan', [InputFormController::class, 'jabatanByUnitKerja']);
        $router->get('/cities', [InputFormController::class, 'cities']);
        $router->get('/agama', [InputFormController::class, 'agama']);
    });
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/me', 'me');
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh')->name('auth.refresh');
});
