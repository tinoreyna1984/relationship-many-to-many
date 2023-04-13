<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Rutas CRUD para Categoría */
Route::get('category','App\Http\Controllers\CategoryController@getCategories');
Route::get('category/{id}','App\Http\Controllers\CategoryController@getCategory');
Route::post('category','App\Http\Controllers\CategoryController@addCategory');
Route::put('category/{id}','App\Http\Controllers\CategoryController@updCategory');
Route::delete('category/{id}','App\Http\Controllers\CategoryController@deleteCategory');

/* Rutas CRUD para Producto */
Route::get('product','App\Http\Controllers\ProductController@getProducts');
Route::get('product/{id}','App\Http\Controllers\ProductController@getProduct');
Route::post('product','App\Http\Controllers\ProductController@addProduct');
Route::put('product/{id}','App\Http\Controllers\ProductController@updProduct');
Route::delete('product/{id}','App\Http\Controllers\ProductController@deleteProduct');
