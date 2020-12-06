<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Woman;

class WomanController extends Controller
{
    public function getRandom(){
        $produk = Woman::all()->random(1);
        return response([
            'data'=>$produk,
        ]);
    }
    public function index(){
        $women = Woman::all();

        if(count($women) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $women
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $woman = Woman::where('id_produkW','=', $id)->first();

        if(!is_null($woman)){
            return response([
                'message' => 'Retrieve product Woman Success',
                'data' => $woman
            ],200);
        }

        return response([
            'message' => 'product Woman Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_produkW' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_produkW' => 'required|numeric',
            'deskripsi_produkW' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
            'gambar_produkW' => 'required|mimes:jpg,jpeg,png'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('gambar_produkW'))
        {
            $file = $request->file('gambar_produkW');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);

        }

        $woman = new Woman;
        $woman->nama_produkW = $request->nama_produkW;
        $woman->harga_produkW = $request->harga_produkW;
        $woman->deskripsi_produkW = $request->deskripsi_produkW;
        $woman->gambar_produkW = $filename;
        $woman->stok = $request->stok;
        $woman->save();
        
        if($woman->save()){
            return response([
                'message' => 'Add product Woman Success',
                'data' => $woman,
            ],200);
       }else{
            return response([
                'message' => 'Add product Woman Fail',
                'data' => null,
            ],400);
       }
    }

    public function destroy($id){
        $woman = Woman::where('id_produkW','=', $id)->first();

        if(is_null($woman)){
            return response([
                'message' => 'product Woman Not Found',
                'data' => $null
            ],404);
        }

        if($woman->delete()){
            return response([
            'message' => 'Delete product Woman Success',
            'data' => $woman,
            ],200);
        } 
        
        return response([
            'message' => 'Delete product Woman Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $woman = Woman::where('id_produkW','=', $id)->first();
        if(is_null($woman)){
            return response([
                'message' => 'product Woman Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_produkW' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_produkW' => 'required|numeric',
            'deskripsi_produkW' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $woman->nama_produkW = $updateData['nama_produkW'];
        $woman->harga_produkW = $updateData['harga_produkW'];
        $woman->deskripsi_produkW = $updateData['deskripsi_produkW'];
        $woman->stok = $updateData['stok'];

        // if($request->hasfile('gambar_produkW'))
        // {
        //     $file = $request->file('gambar_produkW');
        //     $ekstensi = $file->extension();
        //     $filename = 'IMG_'.time().'.'.$ekstensi;
        //     $path = base_path().'/public/products/';
        //     $file->move($path,$filename);

        //     $woman->gambar_produkW = $filename;
        // }

        if($woman->save()){
            return response([
            'message' => 'Update product Woman Success',
            'data' => $woman,
            ],200);
        } 
        
        return response([
            'message' => 'Update product Woman Failed',
            'data' => null,
        ],400);
    }

    public function uploadImage(Request $request , $id){
        if($request->hasFile('gambar_produkW')){
            $woman = Woman::find($id);
            if(is_null($woman)){
                return response([
                    'message' => 'Product not found',
                    'data' => null
                ],404);
            }

            $updateData = $request->all();
            $validate =  Validator::make($updateData,[
                'gambar_produkW' => 'mimes:jpeg,jpg,png',
            ]);

            if($validate->fails())
                return response(['message' => 'error',400]);


            $file = $request->file('gambar_produkW');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);

            $woman->gambar_produkW = $filename;

            if($woman->save()){
                return response([
                    'message'=> 'Upload Image Success',
                    'user'=>$woman
                ]);
            }else{
                return response([
                    'message'=> 'Upload Image Fail',
                    'user'=>null
                ]);
            }
        }
    }
}
