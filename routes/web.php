<?php

use App\Models\Link;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

// Route::get('/', function () {
//     echo "das";
    
// });
// Route::post('/users', function (Request $request) {
//     echo "das";
// });

Route::get("/emp-img", function(Request $request) {

    return  storage_path("app/9.png");

});

Route::post('/link', function(Request $request) {
    try{
        $data = $request->all();
        print_r($data);
        
        

    }catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }
});
Route::post('/user', function(Request $request) {
    $data = $request->all();
    print_r($data);
});