<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\JenisProduk;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // index untuk produk
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->get();
        return view ('admin.produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $jenis_produk = DB::table('jenis_produk')->get();
        return view('admin.produk.create', compact('jenis_produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:produk|max:10',
            'nama' => 'required|max:45',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'min_stok' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ],
        [
            'kode.max' => 'kode maksimal 10 karakter',
            'kode.required' => 'kode wajib diisi',
            'kode.unique' => 'kode tidak boleh sama',
            'nama.required' => 'Nama wajib diisi',
            'nama.max' => 'Nama maksimal 45 karakter',
            'foto.max' => 'Foto maksimal 2 MB',
            'foto.mimes' => 'File ekstensi hanya bisa jpg,png,jpeg,gif,svg',
            'foto.image' => 'Foto harus berbentuk image'
        ]
    );
        // proses upload foto
        if(!empty($request->foto)){
            // maka proses berikut yang dijalankan
            $fileName = 'foto'.uniqid().','.$request->foto->extension();
            // setelah tau fotonya sudah masuk maka tempatkan ke public
            $request->foto->move(public_path('admin/images'),$fileName);
        } else{
            $fileName = '';
        }
        // tambah data produk
        DB::table('produk')->insert([
            'kode'=>$request->kode,
            'nama'=>$request->nama,
            'harga_jual'=>$request->harga_jual,
            'harga_beli'=>$request->harga_beli,
            'stok'=>$request->stok,
            'min_stok'=>$request->min_stok,
            'deskripsi'=>$request->deskripsi,
            'foto'=>$fileName,
            'jenis_produk_id'=>$request->jenis_produk_id,
        ]);
        // Alert::success('Tambah Produk', 'Berhasil menambahkan produk');
        return redirect('admin/produk')->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->where('produk.id', $id)
        ->get();
        return view('admin.produk.detail', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $jenis_produk = DB::table('jenis_produk')->get();
        $produk = DB::table('produk')->where('id', $id)->get();
        return view('admin.produk.edit', compact('jenis_produk', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $fotoLama = DB::table('produk')->select('foto')->where('id',$id)->get();
        foreach($fotoLama as $f1){
            $fotoLama = $f1->foto;
        }
        // jika foto sudah ada yangt terupload
        if(!empty($request->foto)){
            // maka proses selanjutnya
        if(!empty($fotoLama->foto)) unlink(public_path('admin/images'.$fotoLama->foto));
        // proses ganti foto
            $fileName = 'foto-'.uniqid().'.'.$request->foto->extension();
            // setelah tau fotonya sudah masuk maka tempatkan ke public
            $request->foto->move(public_path('admin/images'),$fileName);

        } else {
            $fileName = '$fotoLama';
        }
        DB::table('produk')->where ('id', $id)->update([
            'kode'=>$request->kode,
            'nama'=>$request->nama,
            'harga_jual'=>$request->harga_jual,
            'harga_beli'=>$request->harga_beli,
            'stok'=>$request->stok,
            'min_stok'=>$request->min_stok,
            'deskripsi'=>$request->deskripsi,
            'foto'=>$fileName,
            'jenis_produk_id'=>$request->jenis_produk_id,
        ]);
        Alert::success('Update Produk', 'Berhasil update produk');
        return redirect('admin/produk');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DB::table('produk')->where('id', $id)->delete();
        return redirect ('admin/produk');
    }
    public function produkApi(){
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Produk',
            'data' => $produk,
        ], 200);
    }
    public function produkApidetail($id){
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->where('produk.id', $id)
        ->get();
        if($produk){
            return response()->json([
                'success' => true,
                'message' => 'Detail Produk',
                'data' => $produk,
            ], 200);
            // 200, 404, 500
            // 200 succes tampilkan data
            // 404 not found
        } else {
            return response()->json([
                'success' => false,
                'message' => 'produk Tidak ditemukan',
            ], 404);
        }
    }
}
