<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->where('is_active', true)->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.produk.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'material' => 'required',
            'dimensions' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Generate slug dari nama produk
        $slug = Str::slug($request->name);
        $counter = 1;
        
        // Pastikan slug unik
        while (Product::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $filename, 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.produk.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'material' => 'required',
            'dimensions' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Update slug jika nama berubah
        if ($request->name !== $product->name) {
            $slug = Str::slug($request->name);
            $counter = 1;
            
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = Str::slug($request->name) . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('products', $filename, 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
} 