<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // devuelve todas las categorías
    public function getCategories(){
        return response()->json(Category::all(), 200);
    }

    // devuelve una categoría por ID
    public function getCategory($id){
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        return response()->json($category::find($id), 200);
    }

    // agrega categoría
    public function addCategory(Request $request){
        $this->validate($request, [
            'title' => 'required|max:1000',
        ]);
        $category = Category::create($request->all());
        return response($category, 201);
    }

    // actualiza categoría
    public function updCategory(Request $request, $id){
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $category->update($request->all());
        return response($category, 200);
    }

    // borra categoría
    public function deleteCategory($id){
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $category->delete();
        return response()->json(['msg'=>'Eliminado Correctamente'],200);
    }
}
