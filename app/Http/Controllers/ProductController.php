<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Fungsi nampilin halaman Form
    public function create() 
    {
        return view('tambah_barang');
    }

    // Fungsi nyimpen data ke database
    public function store(Request $request) 
    {
        // Simpan semua data dari form ke tabel products
        Product::create($request->all());

        // Kalau sukses, tampilin pesan ini
        return "Mantap Bro! Data berhasil masuk database."; 
    }
}
