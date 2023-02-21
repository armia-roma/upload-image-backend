<?php
namespace App\Http\Controllers;
use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function store(Request $request) 
    {
        $validate = $request->validate([
            'file' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'image' => 'required'
        ]);
        $data = new product;
        $data['image'] = $request['image'];
        $extension = $request->file->extension();
        $product = $data->create(array("image" => $validate['image'], "extension" => $extension ));
        $path = $request->file->storeAs('images', $product->id.'.'.$extension,'public');
        // $product->path = Storage::url($product->id);
        $product->path = $path;
        return $product;
    }
    public function getImage(Request $request)
    {
        // $image = Storage::get($path);
       return asset('/images/8.png');
        // return response($image, 200)->header('Content-Type', Storage::getMimeType($path));
    }
    
    public function image($fileName){
        Log::info($fileName);
        $path = public_path()."/storage/images/".$fileName;
        // $path = storage_path();
        Log::info($path);
        return Response::download($path);        
    }
    public function index() {
        $products = DB::table('products')
        ->join('product_images', 'products.id', '=', 'product_images.product_id')
        ->select('products.id', 'products.name', 'product_images.url')
        ->get();
        return $products;
    }
    public function storeMultiples(Request $request) 
    {   
        // Log::info($request);
        // $files = [...$request->files];
        $body = $request->all();
        $files = $request->file('files');
        $request->validate([
            'name' => ['required'],
            'files' => ['required'] 
        ]);
        // Log::info($request->input('name'));
        $result =DB::transaction(function() use($request, $files) {
            // Log::info($files);
            $newProduct = product::create([
                "name" => $request->input('name')
            ]);
            $productImages = [];
            foreach($files as $file) {
                // Log::info($file);
                $path = $file->storeAs('images', $file->getClientOriginalName(),'public');
                $productImage = ProductImage::create([
                    "url" => $path,
                    "product_id" => $newProduct->id 
                ]);  
                $productImages[] = $productImage; 
            }
            return (object) ["product" => $newProduct, "productImages" => $productImages];
            
        });
        return response()->json([
            "result" => $result,
            'message' => "Product created successfully",
            'status' => "success"
        ]); 

    }

}
