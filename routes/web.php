<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');
// Route::get('/hola/{nombre?}', function ($nombre="Juan") {
//     return "hola $nombre  conocenos <a href='".route("nosotros")."'>nosotros</a>";
// });
// Route::get('/sobre-nosotros', function () {
//     return "<h1>Toda la información sobre nosotros</h1>";
// })->name("nosotros");


// Route::get('home/{nombre?}/{apellido?}', function($nombre="Pepe",$apellido="Martin") {
//     $posts=["Posts1","Posts2","Posts3","Posts4"];
//     $posts2=null;
//     // return view("home")->with('nombre', $nombre)->with('apellido', $apellido);
//     return view("home",['nombre'=>$nombre,'apellido'=>$apellido, 'posts'=>$posts,'posts2'=>$posts2]);
    
// })->name("home");



Route::resource('dashboard/post', 'dashboard\PostController');
Route::resource('dashboard/category', 'dashboard\CategoryController');
