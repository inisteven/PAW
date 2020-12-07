<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Cart;

class CartController extends Controller
{
    // public function getTotal($idUser){
    //     $matchThese = ['id_userCart' => $idUser,'isPay'=>0];
    //     $carts = Cart::where($matchThese)->get();
    //     if(count($carts) < 0){
    //         return response([
    //             'message' => 'Retrieve Fail',
    //             'data' => null
    //         ],200);
    //     }

        
    //     return response([
    //         'message' => 'sum success',
    //         'total' => $total,
    //     ],200);

    // }
    public function index($idUser){
        $matchThese = ['id_userCart' => $idUser,'isPay'=>0];
        $carts = Cart::where($matchThese)->get();

        
        if(count($carts) > 0){
            $total = $carts->sum('total_harga');
            return response([
                'message' => 'Retrieve All Success',
                'data' => $carts,
                'total' => $total
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $cart = Cart::find($id);

        if(!is_null($cart)){
            return response([
                'message' => 'Retrieve Cart Success',
                'data' => $cart
            ],200);
        }

        return response([
            'message' => 'Cart Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_productCart' => 'required|numeric',
            'id_userCart' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'size' => 'required|alpha',
            'total_harga' => 'required|numeric',
            'isPay' => 'required|numeric',
            'kategori' => 'required|alpha'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()]);
        

        $cart = Cart::create($storeData);
        
        return response([
            'message' => 'Add Cart Success',
            'data' => $cart,
        ],200);
    }

    public function destroy($id){
        $cart = Cart::find($id);

        if(is_null($cart)){
            return response([
                'message' => 'Cart Not Found',
                'data' => $null
            ],404);
        }

        if($cart->delete()){
            return response([
            'message' => 'Delete Cart Success',
            'data' => $cart,
            ],200);
        } 
        
        return response([
            'message' => 'Delete Cart Failed',
            'data' => null,
        ],400);
    }

    public function cartCek($idProduk,$idUser, $size,$kategori){
        $matchThese = ['id_productCart' => $idProduk,'id_userCart' => $idUser, 'size' => $size,'isPay'=>0,'kategori'=>$kategori];
        $cart = Cart::where($matchThese)->get();

        return response([
            'data' => $cart,
        ]);
    }

    public function update($id_productCart,$id_userCart,$size,$kategori,$jumlah){
        
        $matchThese = ['id_productCart' => $id_productCart, 'id_userCart'=> $id_userCart, 'size' => $size,'kategori' => $kategori];
        $cart = Cart::where($matchThese)->get()->first();

        if($cart == null){
            return response([
                'message' => 'Cart Not Found',
                'data' => $cart
            ],404);
        }
            
    

        // $newStok = $cart->jumlah + $request->stok;
        $hargaPerProduk = $cart->total_harga / $cart->jumlah;
        $tambahanHarga = $jumlah * $hargaPerProduk;
        $cart->jumlah = $cart->jumlah+$jumlah;
        
        $cart->total_harga = $cart->total_harga + $tambahanHarga; 

        if($cart->save()){
            return response([
            'message' => 'Add Cart Success',
            'data' => $cart,
            ],200);
        } 
        
        return response([
            'message' => 'Update Cart Failed',
            'data' => null,
        ],400);
    }
}
