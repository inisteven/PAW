<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Cart;

class CartController extends Controller
{
    public function index(){
        $carts = Cart::all();

        if(count($carts) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $carts
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
            return response(['message' => $validate->errors()],400);
        

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

    public function update(Request $request, $id){
        $cart = Cart::find($id);
        if(is_null($cart)){
            return response([
                'message' => 'Cart Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_productCart' => 'required|numeric',
            'id_userCart' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'size' => 'required|alpha',
            'total_harga' => 'required|numeric',
            'isPay' => 'required|numeric',
            'kategori' => 'required|alpha'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $cart->id_productCart = $updateData['id_productCart'];
        $cart->id_userCart = $updateData['id_userCart'];
        $cart->jumlah = $updateData['jumlah'];
        $cart->size = $updateData['size'];
        $cart->total_harga = $updateData['total_harga'];
        $cart->isPay = $updateData['isPay'];
        $cart->kategori = $updateData['kategori'];

        if($cart->save()){
            return response([
            'message' => 'Update Cart Success',
            'data' => $cart,
            ],200);
        } 
        
        return response([
            'message' => 'Update Cart Failed',
            'data' => null,
        ],400);
    }
}
