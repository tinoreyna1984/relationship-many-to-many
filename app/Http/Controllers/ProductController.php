<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // devuelve todos los productos
    public function getProducts(){
        return response()->json(Product::all(), 200);
    }

    // devuelve un producto por ID
    public function getProduct($id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['msg'=>'Producto no encontrado'],404);
        }
        return response()->json($product::find($id), 200);
    }

    // agrega producto
    public function addProduct(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
        ]);
        //return response($request->input("category_id"), 201);
        $category = Category::find($request->input("category_id"));
        $product = Product::create($request->all()); // crea producto
        $product->categories()->attach($category); // inscribe a la tabla intermedia (pivote)
        return response($product, 201);
    }

    // actualiza producto
    public function updProduct(Request $request, $id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['msg'=>'Producto no encontrado'],404);
        }
        $product->update($request->all());
        return response($product, 200);
    }

    // borra producto
    public function deleteProduct($id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['msg'=>'Producto no encontrado'],404);
        }
        $product->delete();
        return response()->json(['msg'=>'Eliminado Correctamente'],200);
    }
}
