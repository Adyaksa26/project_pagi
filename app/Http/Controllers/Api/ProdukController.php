<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\JenisProduk;
use DB;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    //
    public function index(){
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->get();
        return new ProdukResource(true, 'List Data produk', $produk);
    }

    public function show($id){
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->where('produk.id', $id)
        ->get();
        if($produk){
            return new ProdukResource(true, 'Detail Data Produk', $produk);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'produk Tidak ditemukan',
            ], 404);
        }
    }
}