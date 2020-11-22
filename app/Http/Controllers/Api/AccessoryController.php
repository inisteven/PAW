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
        $acc = Accessory::find($id);

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
            'nama_aksesoris' => 'required|alphanumeric',
            'harga_aksesoris' => 'required|numeric',
            'deskripsi_aksesoris' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/aksesoris/'.$filename;
            $file->move($path,$filename);

        }

        $acc = new Accessory;
        $acc->nama_aksesoris = $request->nama_aksesoris;
        $acc->harga_aksesoris = $request->harga_aksesoris;
        $acc->deskripsi_aksesoris = $request->deskripsi_aksesoris;
        $acc->gambar_aksesoris = $path;
        $acc->kategori = $request->kategori;
        $acc->stok = $request->stok;
        $acc->save();

       // $man = Man::create($storeData);
        
        return response([
            'message' => 'Add Accessory Success',
            'data' => $acc,
        ],200);
    }

    public function destroy($id){
        $acc = Accessory::find($id);

        if(is_null($acc)){
            return response([
                'message' => 'Accessory Not Found',
                'data' => $null
            ],404);
        }

        if($man->delete()){
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
        $acc = Accessory::find($id);
        if(is_null($acc)){
            return response([
                'message' => 'Accessory Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_aksesoris' => 'required|alphanumeric',
            'harga_aksesoris' => 'required|numeric',
            'deskripsi_aksesoris' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $acc->nama_aksesoris = $updateData['nama_aksesoris'];
        $acc->harga_aksesoris = $updateData['harga_aksesoris'];
        $acc->deskripsi_aksesoris = $updateData['deskripsi_aksesoris'];
        $acc->kategori = $updateData['kategori'];
        $acc->stok = $updateData['stok'];

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/aksesoris/'.$filename;
            $file->move($path,$filename);

            $man->gambar_aksesoris = $path;
        }

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
}
