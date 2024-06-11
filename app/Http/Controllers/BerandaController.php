<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\JenisProduk;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{
    //
    public function index(){
        $produk = Produk::join('jenis_produk', 'jenis_produk_id', '=', 'jenis_produk.id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->get();
        return view ('front.home', compact('produk'));
    }
    public function addToCart($id){
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart',[]);
        if (isset($cart[$id])){
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "nama" => $produk->nama,
                "quantity" => 1,
                "harga_jual" => $produk->harga_jual,
                "foto" => $produk->foto
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke cart');
    }
    public function detail($id){
        $produk = produk::join('jenis_produk', 'jenis_produk.id', '=', 'jenis_produk_id')
        ->select('produk.*', 'jenis_produk.nama as jenis')
        ->where('produk.id', $id)
        ->get();

        return view('front.detail', compact('produk'));
    }
    public function cart(){
        // panggil detail produk yang sudah masuk dalam cart
        $produk = Produk::all();
        $cart = session()->get('cart');

        $total = 0;
        if($cart){
            foreach($cart as $key => $produk){
                $total += $produk['harga_jual'] * $produk['quantity'];
            }
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $total,
                ),
                'customer_details' => array(
                    'first_name' => auth()->user()->name,
                    // 'last_name' => 'pratama',
                    'email' => auth()->user()->email,
                    // 'phone' => '08111222333',
                ),
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return view('front.shop_cart', compact('produk', 'cart', 'total', 'snapToken'));
        }
        return view('front.home', compact('produk'));
    }
    // update harga sub total
    public function update(Request $request){
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id["quantity"] = $request->quantity];
            session()->put('cart', $cart);
            session()->with('success', 'cart terupdate');
        }
    }
    public function request(Request $request){
        $cart = session()->get('cart');
        if(isset($cart[$request->id])){
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        session()->with('success', 'cart delete');
    }
}