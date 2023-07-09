<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with('category')->paginate(10);
        return view('admin.product.index', ['produk' => $product]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::get();
        return view('admin.product.add', ['kategori' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newName = '';
        if ($request->file('gambar')) {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $newName = $request->nama_produk . '-' . now()->timestamp . '.' . $extension;
            $request->file('gambar')->storeAs('images', $newName, 'public');
        }

        $request['image'] = $newName;
        Product::create([
            'category_id' => $request->kategori_produk,
            'name' => $request->nama_produk,
            'price' => $request->harga,
            'description' => $request->deskripsi,
            'image' => $newName,
        ]);

        return redirect('/admin/product')->with('alert', 'Berhasil menambahkan product');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.product.detail', ['produk' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::get();

        return view('admin.product.edit', ['produk' => $product, 'kategori' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->file('gambar')) {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $newName = $request->nama_produk . '-' . now()->timestamp . '.' . $extension;
            $request->file('gambar')->storeAs('images', $newName, 'public');

            // delete gambar lama dari storage
            Storage::delete('public/images/' . $product->image);

            $product->image = $newName;
        }

        $data = [
            'category_id' => $request->kategori_produk,
            'name' => $request->nama_produk,
            'price' => $request->harga,
            'description' => $request->deskripsi,
        ];

        $product->update($data);

        return redirect('/admin/product')->with('alert', 'Berhasil mengubah product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::delete('public/images/' . $product->image);
        $product->delete();
        return redirect('/admin/product')->with('alert', 'Berhasil menghapus product');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $product = Product::where('name', 'LIKE', '%'.$keyword.'%')->get();

        return view('admin.product.index',compact('product'));
    }
}
