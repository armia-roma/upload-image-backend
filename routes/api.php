<?php

use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\Test;
use App\Http\Controllers\ProductController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Link;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/questionnaire', [QuestionnaireController::class, 'store']);
Route::get('/questionnaire', function(Request $request) {
    dd($request->query('active') );
});

Route::post('/link', function(Request $request) {
    try{
    $data =  $request->validate([
        'password' => ['required'],
        'url' => ['required'],
        'description' => ['required']
    ]);
        $cryptPass= Crypt::encryptString($request->password);
        echo($cryptPass);
        // var_dump(Crypt::decryptString($cryptPass));
        // $data = $request->all();
        // print_r($data);
        // $link = new Link();
        // $link->title = $data['title'];
        // $link->url = $data['url'];
        // $link->description = $data['description'];
        // $link->save();

    }catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }
});
Route::post('upload_multiple', [ProductController::class, 'storeMultiples']);
Route::post('upload', [ProductController::class, 'store']);
Route::get('/link', function(Request $request) {
    try{
        $links = Link::all();
        print_r($links);
    } catch(Exception $e) {

    }
});
Route::post('articles', function(Request $request) {
    $body = $request->all();
    $article = new Article;
    $article->title = $body['title'];
    $article->body = $body['body'];
    $article->save();
});
Route::get('/articles', function() {
    return Article::all();
});

Route::put('/articles/{id}', function($id, Request $request) {
    $body = $request->all();
    $article = Article::find($id);
    // $article = new Article;
    
    $article->title = $body['title'];
    $article->body = $body['body'];
    $article->active = $body['active']; 
    $article->update();
});
Route::get('/image/{filename}',[ProductController::class, 'image']);
Route::get('/images',[ProductController::class, 'index'] );
class ArticleEntity {
    private $id;
    private $title;
    private $body;
    private $active;
    public function __construct($id , $title, $body, $active)
    {
        $this->id = $id ;
        $this->title = $title;
        $this->body = $body;
        $this->active = $active;
    }
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }
    public function setActive($active) {
        $this->active = $active;
        return $this;
    }
    public function print() {
        print_r($this);
    }
};
class ArticleService {
    private $ar;
    public function __construct(ArticleRepositories $ar)
    {
        $this->ar = $ar;
    }
    public function read($id) {
        $article = $this->ar->read($id);
    }
    //make entity
    public function make($data) 
    {
        $id = null;
        if(isset($data["id"]) && !is_null($data["id"])){
            $id = $data["id"];
        }
        if(!isset($data["title"])){
            throw new InvalidArgumentException("title is required");
        }
        $title = $data["title"];
        $body = "";
        if(isset($data["address"]) && !is_null($data["address"])) {
            $body = $data["body"];
        }
        $active = "";
        if(isset($data["active"]) && !is_null($data["active"])) {
            $body = $data["active"];
        }
        $article = new ArticleEntity($id, $title, $body, $active);
        return $article;
    }
}
class ArticleRepositories {
    public function update() {

    }
    public function read($id) {
        $row = DB::table('articles')
            ->where('id', $id)
            ->first();
                print_r($row);
                return (new ArticleService($this))->make([
                    "title" => $row->title,
                    "body" => $row->body,
                    "active" => $row->active
                ]);
            // echo;
            // $article =  (new ArticleService)->make([
                
                //     "title" => $row->title,
                //     "body" => $row->body,
                //     "active" => $row->active
                // ]);
                // return $row;
    }
}
Route::get('/articles/{id}', function( ArticleService $as ) {
    try {
        $article = $as->read(1);
        // foreach($article as $art) {
        //     print_r($art->title);
        // }
        print_r($article);
        // print_r($as->make($article => ['title']));
        
        // return Article::find($id);
        return $article;

    } catch (Exception $e) {
        return $e;
    }
}); 
