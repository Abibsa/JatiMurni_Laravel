<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])->where('is_active', true)->latest()->paginate(10);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images'   => 'nullable|array|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $product = Product::create($data);

        // Upload gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $img) {
                $filename = time() . '_gallery_' . $index . '_' . $img->getClientOriginalName();
                $path = $img->storeAs('products/gallery', $filename, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images'   => 'nullable|array|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_gallery'   => 'nullable|array',
            'delete_gallery.*' => 'integer|exists:product_images,id',
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

        // Hapus gallery images yang diminta dihapus
        if ($request->has('delete_gallery')) {
            foreach ($request->delete_gallery as $imageId) {
                $galleryImage = ProductImage::find($imageId);
                if ($galleryImage && $galleryImage->product_id === $product->id) {
                    Storage::disk('public')->delete($galleryImage->image_path);
                    $galleryImage->delete();
                }
            }
        }

        // Upload gallery images baru
        if ($request->hasFile('gallery_images')) {
            $maxSort = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery_images') as $index => $img) {
                $filename = time() . '_gallery_' . $index . '_' . $img->getClientOriginalName();
                $path = $img->storeAs('products/gallery', $filename, 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $maxSort + $index + 1,
                ]);
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus semua gallery images
        foreach ($product->images as $galleryImage) {
            Storage::disk('public')->delete($galleryImage->image_path);
        }
        $product->images()->delete();

        $product->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }
} 