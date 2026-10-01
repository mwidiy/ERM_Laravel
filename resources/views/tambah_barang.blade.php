<h1>Form Tambah Barang ERP</h1>

<!-- Action mengarah ke URL Route::post yang kita bikin tadi -->
<form action="/simpan-barang" method="POST">
    
    <!-- INI PENTING BANGET: @csrf -->
    @csrf 
    
    <input type="text" name="nama_barang" placeholder="Nama Barang" required><br><br>
    <input type="number" name="stok" placeholder="Stok Barang" required><br><br>
    <input type="number" name="harga" placeholder="Harga Barang" required><br><br>
    
    <button type="submit">Simpan ke Database</button>
</form>