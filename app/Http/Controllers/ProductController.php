<?php

namespace App\Http\Controllers;

use App\Helpers\UploadPaths;
use App\Product;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function productCreateView()
    {
        return view('product.create');
    }

    public function productCreate(Request $request)
    {
        $name = $request->get('name');
        $price = $request->get('price');
        $filePhotoUrl = $request->file('photo');
        $user = User::find(1);
        if(isset($filePhotoUrl)){
            $productPhotoName = uniqid('product_').'-' . $filePhotoUrl->getClientOriginalExtension();
            $filePhotoUrl->nove(UploadPaths::getUploadPath('product_photo'), $productPhotoName);
        }
        Product::create([
            'name' => $name,
            'price' => $price,
            'photo' => $productPhotoName,
            'is_approve' => false,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        return'Başarıyla eklendi';
    }
    public function indexView()
    {
        $products = Product::with(['user'])->where('deleted_at','=',null)->get();
        return view('product.index',compact('products'));
    }

}
