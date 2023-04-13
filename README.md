# Práctica de relación M-N

1. Crear proyecto Laravel:

```bash
composer create-project laravel/laravel relationship-many-to-many
```

o:

```bash
laravel new relationship-many-to-many
```

Luego, configurar la base de datos en el archivo ".env".

2. Agregar dependencias de UI y Login:

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
```

3. Ejecutar en 2 terminales:

NPM:

```bash
cd relationship-many-to-many
npm i
npm run dev
```

Artisan:

```bash
cd relationship-many-to-many
php artisan serve
```

Las entidades participantes son categorías y productos (una categoría puede representar a muchos productos y un producto puede estar en muchas categorías).


4. Crear modelo, controlador y migración para categoría y para producto:

Ejecutar: php artisan make:model <Modelo> -mc

```bash
php artisan make:model Category -mc
php artisan make:model Product -mc
```

Salida:

```bash
PS E:\laravel\relationship-many-to-many> php artisan make:model Category -mc

   INFO  Model [E:\laravel\relationship-many-to-many\app/Models/Category.php] created successfully.

   INFO  Migration [E:\laravel\relationship-many-to-many\database\migrations/2023_04_13_065953_create_categories_table.php] created successfully.

   INFO  Controller [E:\laravel\relationship-many-to-many\app/Http/Controllers/CategoryController.php] created successfully.

PS E:\laravel\relationship-many-to-many> php artisan make:model Product -mc

   INFO  Model [E:\laravel\relationship-many-to-many\app/Models/Product.php] created successfully.

   INFO  Migration [E:\laravel\relationship-many-to-many\database\migrations/2023_04_13_070003_create_products_table.php] created successfully.

   INFO  Controller [E:\laravel\relationship-many-to-many\app/Http/Controllers/ProductController.php] created successfully.
```

5. Editar ambos archivos de migración:

Categoría:

```php
Schema::create('categories', function (Blueprint $table) {
	$table->id();
	$table->string('title');
	$table->timestamps();
});
```

Producto:

```php
Schema::create('products', function (Blueprint $table) {
	$table->id();
	$table->string('name');
	$table->float('price');
	$table->timestamps();
});
```

6. Migrar:

```bash
php artisan migrate
```

7. Modificar los modelos para inserción masiva:

```php
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title'];
}

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];
}
```

8. Editar controlador y API de categorías:

Controlador:

```php
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
```

API endpoints:

```php
/* Rutas CRUD para Categoría */
Route::get('category','App\Http\Controllers\CategoryController@getCategories');
Route::get('category/{id}','App\Http\Controllers\CategoryController@getCategory');
Route::post('category','App\Http\Controllers\CategoryController@addCategory');
Route::put('category/{id}','App\Http\Controllers\CategoryController@updCategory');
Route::delete('category/{id}','App\Http\Controllers\CategoryController@deleteCategory');
```

9. Editar controlador y API de productos:

Controlador:

```php
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
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        return response()->json($product::find($id), 200);
    }

    // agrega producto
    public function addProduct(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
        ]);
        $product = Product::create($request->all());
        return response($product, 201);
    }

    // actualiza producto
    public function updProduct(Request $request, $id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $product->update($request->all());
        return response($product, 200);
    }

    // borra producto
    public function deleteProduct($id){
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $product->delete();
        return response()->json(['msg'=>'Eliminado Correctamente'],200);
    }
}
```

API endpoints:

```php
/* Rutas CRUD para Producto */
Route::get('product','App\Http\Controllers\ProductController@getProducts');
Route::get('product/{id}','App\Http\Controllers\ProductController@getProduct');
Route::post('product','App\Http\Controllers\ProductController@addProduct');
Route::put('product/{id}','App\Http\Controllers\ProductController@updProduct');
Route::delete('product/{id}','App\Http\Controllers\ProductController@deleteProduct');
```

10. Modificar los modelos para la relación:

```php
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function products() // <--------------
    {
        return $this->belongsToMany(Product::class);
    }
}

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function categories() // <--------------
    {
        return $this->belongsToMany(Category::class);
    }
}
```

11. Crear tabla intermedia:

```bash
php artisan make:migration create_category_product_table --create=category_product
```

12. Definir tabla intermedia:

```php
public function up(): void
{
	Schema::create('category_product', function (Blueprint $table) {
		$table->id();
		$table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // <--------------
		$table->foreignId('product_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate(); // <--------------
		$table->timestamps();
	});
}
```

13. Modificar el controlador de Producto para inscribir en tabla intermedia.

```php
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
```

Notar que solo se necesita modificar para insertar producto, no actualizar ni borrar (debido a la restricción que aplica en cascada en base de datos).





