<?php

namespace Nowyouwerkn\WeCommerce\Controllers;
use App\Http\Controllers\Controller;

use Session;
use Auth;
use Purifier;
use Storage;
use Image;

use Nowyouwerkn\WeCommerce\Models\Product;
use Nowyouwerkn\WeCommerce\Models\Category;
use Nowyouwerkn\WeCommerce\Models\ProductSize;
use Nowyouwerkn\WeCommerce\Models\ProductImage;
use Nowyouwerkn\WeCommerce\Models\ProductVariant;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);

        return view('wecommerce::back.products.index')->with('products', $products);
    }

    public function create()
    {
        $categories = Category::all();

        return view('wecommerce::back.products.create')
        ->with('categories', $categories);
    }

    public function store(Request $request)
    {
        //Validar
        $this -> validate($request, array(
            'name' => 'unique:products|required|max:255',
            'description' => 'required',
            'price' => 'required',
            'model_image' => 'sometimes|image',
            'sku' => 'nullable',
        ));

        // Guardar datos en la base de datos
        $product = new Product;

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->materials = Purifier::clean($request->materials);
        $product->color = $request->color;
        $product->pattern = $request->pattern;

        $product->in_index = $request->in_index;
        $product->is_favorite = $request->is_favorite;
        
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->production_cost = $request->production_cost;

        $product->has_discount = $request->has_discount;
        $product->has_tax = $request->has_tax;

        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->stock = $request->stock;

        $product->size_chart_file = $request->size_chart_file;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->lenght = $request->lenght;
        $product->weight = $request->weight;

        $product->category_id = $request->category_id;
        
        $product->status = $request->status;
        $product->search_tags = $request->search_tags;
        $product->available_date_start = $request->available_date_start;

        if ($request->hasFile('model_image')) {
            $model_image = $request->file('model_image');
            $filename = 'model' . time() . '.' . $model_image->getClientOriginalExtension();
            $location = public_path('img/products/' . $filename);

            Image::make($model_image)->resize(1280,null, function($constraint){ $constraint->aspectRatio(); })->save($location);

            $product->image = $filename;
        }

        $product->save();

        $product->subCategory()->sync($request->category_id);

        // Mensaje de session
        Session::flash('success', 'Your product was saved correctly in the database.');

        // Enviar a vista
        return redirect()->route('products.show', $product->id);
    }

    public function show($id)
    {
        $product = Product::find($id);
        $variant_stock = ProductVariant::where('product_id', $product->id)->get();

        return view('wecommerce::back.products.show')->with('product', $product)->with('variant_stock', $variant_stock);
    }

    public function storeImage(Request $request)
    {
        //Validar
        $this -> validate($request, array(
            'description' => 'nullable',
        ));

        // Guardar datos en la base de datos
        $var_imagen = new ProductImage;

        $var_imagen->description = $request->description;
        $var_imagen->product_id = $request->product_id;

        // Esto se logra gracias a la libreria de imagen Intervention de Laravel
        if ($request->hasFile('image')) {
            $imagen = $request->file('image');
            $nombre_archivo = Str::random(8) . '_productitem' . '.' . $imagen->getClientOriginalExtension();
            $ubicacion = public_path('img/products/' . $nombre_archivo);

            Image::make($imagen)->resize(1280,null, function($constraint){ $constraint->aspectRatio(); })->save($ubicacion);

            $var_imagen->image = $nombre_archivo;
        }

        $var_imagen->save();

        // Mensaje de session
        Session::flash('success', 'Image saved correctly on the database.');

        // Enviar a vista
        return redirect()->back();
    }

    public function destroyImage($id)
    {

        $var_imagen = ProductImage::find($id);

        $var_imagen->delete();

        Session::flash('success', 'The Image was succesfully deleted.');


        return redirect()->back();
    }

    public function storeLifestyle(Request $request)
    {
        //Validar
        $this -> validate($request, array(
            'description' => 'nullable',
        ));

        // Guardar datos en la base de datos
        $var_imagen = new ProductLifestyle;

        $var_imagen->description = $request->description;
        $var_imagen->product_id = $request->product_id;

        // Esto se logra gracias a la libreria de imagen Intervention de Laravel
        if ($request->hasFile('image')) {
            $imagen = $request->file('image');
            $nombre_archivo = Str::random(8) . '_lifestyle' . '.' . $imagen->getClientOriginalExtension();
            $ubicacion = public_path('img/products/lifestyle/' . $nombre_archivo);

            Image::make($imagen)->resize(800,null, function($constraint){ $constraint->aspectRatio(); })->save($ubicacion);

            $var_imagen->image = $nombre_archivo;
        }

        $var_imagen->save();

        // Mensaje de session
        Session::flash('success', 'Image saved correctly on the database.');

        // Enviar a vista
        return redirect()->back();
    }

    public function destroyLifestyle($id)
    {

        $var_imagen = ProductLifestyle::find($id);

        $var_imagen->delete();

        Session::flash('success', 'The Image was succesfully deleted.');


        return redirect()->back();
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::all();

        return view('wecommerce::back.products.edit')
        ->with('product', $product)
        ->with('categories', $categories);
    }

    public function update(Request $request, $id)
    {
        // Guardar datos en la base de datos
        $product = Product::find($id);

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->materials = Purifier::clean($request->materials);
        $product->color = $request->color;
        $product->pattern = $request->pattern;
        $product->search_tags = $request->search_tags;
        $product->price = $request->price;

        $product->has_discount = $request->has_discount;
        $product->discount_start = $request->discount_start;
        $product->discount_end = $request->discount_end;
        
        $product->discount_price = $request->discount_price;

        $product->sku = $request->sku;
        $product->brand_id = $request->brand_id;
        $product->gender_id = $request->gender_id;

        $product->in_index = $request->in_index;
        $product->is_favorite = $request->is_favorite;

        $product->category_id = $request->main_category;
        $product->height = $request->height;
        $product->lenght = $request->lenght;
        $product->width = $request->width;

        $img2 = 'model';

        if ($request->hasFile('model_image')) {
            $model_image = $request->file('model_image');
            $filename = $img2 . time() . '.' . $model_image->getClientOriginalExtension();
            $location = public_path('img/products/' . $filename);

            Image::make($model_image)->resize(1280,null, function($constraint){ $constraint->aspectRatio(); })->save($location);

            $product->image = $filename;
        }

        $product->save();
        $product->subCategory()->sync($request->category_id);
        $product->features()->sync($request->features);

        // Mensaje de session
        Session::flash('success', 'Your product was saved correctly in the database.');

        // Enviar a vista
        return redirect()->route('productos.show', $product->id);

    }
    public function destroy($id)
    {
        $product = Product::find($id);

        $product->delete();

        Session::flash('success', 'The product was succesfully deleted.');

        return redirect()->route('productos.index');
    }

    public function fetchSubcategory(Request $request)
    {
        $value = $request->get('value');

        return response()->json(Category::where('parent_id', $value)->get());
    }
}
