<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->paginate(4); 

        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            $destination = public_path('uploads');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0777, true, true);
            }

            $file->move($destination, $imageName);
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName
        ]);

        return response()->json(['status' => true]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $imageName = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
                unlink(public_path('uploads/' . $product->image));
            }

            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $imageName);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName 
        ]);

        return response()->json(['status' => true]);
    }

    public function destroy($id)
{
    Product::findOrFail($id)->delete();
    return response()->json(['status' => true]);
}

    public function search(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->search . '%')->get();
        return view('products.search',  ['products' => $products]);
    }

    public function editNEw($id)
    {
        $product=Product::findOrFail($id);
        return response()->json(['status' =>true,'product'=>$product]);
        
    }
public function updatenew($id)
{
    $product = Product::findOrFail($id);

    $product->name = "Testing";
    $product->price = 5667;

    $product->stock = 112;

    $product->save();

    return response()->json(['status' => true,'product' => $product]);
}

}
