<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Accessory;

class AccessoryController extends Controller
{
    public function index(){
        $accs = Accessory::all();

        if(count($accs) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $accs
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $acc = Accessory::where('id_aksesoris','=', $id)->first();

        if(!is_null($acc)){
            return response([
                'message' => 'Retrieve Accessory Success',
                'data' => $acc
            ],200);
        }

        return response([
            'message' => 'Product Accessory Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_aksesoris' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_aksesoris' => 'required|numeric',
            'deskripsi_aksesoris' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
            'gambar_aksesoris' => 'required|mimes:jpg,jpeg,png',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('gambar_aksesoris'))
        {
            $file = $request->file('gambar_aksesoris');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);
        }

        $acc = new Accessory;
        $acc->nama_aksesoris = $request->nama_aksesoris;
        $acc->harga_aksesoris = $request->harga_aksesoris;
        $acc->deskripsi_aksesoris = $request->deskripsi_aksesoris;
        $acc->gambar_aksesoris = $filename;
        $acc->stok = $request->stok;

       // $aksesoris = aksesoris::create($storeData);
        
       if($acc->save()){
            return response([
                'message' => 'Add Accessory Success',
                'data' => $acc,
            ],200);
       }else{
            return response([
                'message' => 'Add Accessory Fail',
                'data' => null,
            ],400);
       }
        
    }

    public function destroy($id){
        $acc = Accessory::where('id_aksesoris','=', $id)->first();

        if(is_null($acc)){
            return response([
                'message' => 'Accessory Not Found',
                'data' => $null
            ],404);
        }

        if($acc->delete()){
            return response([
            'message' => 'Delete Accessory Success',
            'data' => $acc,
            ],200);
        } 
        
        return response([
            'message' => 'Delete Accessory Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $acc = Accessory::where('id_aksesoris','=', $id)->first();
        if(is_null($acc)){
            return response([
                'message' => 'Accessory Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_aksesoris' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_aksesoris' => 'required|numeric',
            'deskripsi_aksesoris' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $acc->nama_aksesoris = $updateData['nama_aksesoris'];
        $acc->harga_aksesoris = $updateData['harga_aksesoris'];
        $acc->deskripsi_aksesoris = $updateData['deskripsi_aksesoris'];

        $acc->stok = $updateData['stok'];
        if($acc->save()){
            return response([
            'message' => 'Update Accessory Success',
            'data' => $acc,
            ],200);
        } 
        
        return response([
            'message' => 'Update Accessory Failed',
            'data' => null,
        ],400);
    }

    public function uploadImage(Request $request , $id){
        if($request->hasFile('gambar_aksesoris')){
            $aksesoris = Accessory::find($id);
            if(is_null($aksesoris)){
                return response([
                    'message' => 'Product not found',
                    'data' => null
                ],404);
            }

            $updateData = $request->all();
            $validate =  Validator::make($updateData,[
                'gambar_aksesoris' => 'mimes:jpeg,jpg,png',
            ]);

            if($validate->fails())
                return response(['message' => 'error',400]);


            $file = $request->file('gambar_aksesoris');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);

            $aksesoris->gambar_aksesoris = $filename;

            if($aksesoris->save()){
                return response([
                    'message'=> 'Upload Image Success',
                    'user'=>$aksesoris
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
