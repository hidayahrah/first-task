<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Products extends Controller
{
    public function save(Request $req)
    {
        $product = new Product;
        $product->name=$req->name;
        $product->category=$req->category;
        $product->price=$req->price;
        if($product->save())
        {
            return ['Result'=>"Product has been saved."];
        } 
    }

    public function update(Request $req)
    {
        return $req->input();
    }
}
