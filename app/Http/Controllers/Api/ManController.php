<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Man;

class ManController extends Controller
{
    public function index(){
        $men = Man::all();

        if(count($men) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $men
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $man = Man::find($id);

        if(!is_null($man)){
            return response([
                'message' => 'Retrieve Product Man Success',
                'data' => $man
            ],200);
        }

        return response([
            'message' => 'Product Man Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_productM' => 'required|alphanumeric',
            'harga_productM' => 'required|numeric',
            'deskripsi_productM' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/man/'.$filename;
            $file->move($path,$filename);

        }

        $man = new Man;
        $man->nama_productM = $request->nama_productM;
        $man->harga_productM = $request->harga_productM;
        $man->deskripsi_productM = $request->deskripsi_productM;
        $man->gambar_productM = $path;
        $man->kategori = $request->kategori;
        $man->stok = $request->stok;
        $product->save();

       // $man = Man::create($storeData);
        
        return response([
            'message' => 'Add Product Man Success',
            'data' => $man,
        ],200);
    }

    public function destroy($id){
        $man = Man::find($id);

        if(is_null($man)){
            return response([
                'message' => 'Product Man Not Found',
                'data' => $null
            ],404);
        }

        if($man->delete()){
            return response([
            'message' => 'Delete Product Man Success',
            'data' => $man,
            ],200);
        } 
        
        return response([
            'message' => 'Delete Product Man Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $man = Man::find($id);
        if(is_null($man)){
            return response([
                'message' => 'Product Man Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_productM' => 'required|alphanumeric',
            'harga_productM' => 'required|numeric',
            'deskripsi_productM' => 'required|alphanumeric',
            'kategori' => 'required|alpha',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $man->nama_productM = $updateData['nama_productM'];
        $man->harga_productM = $updateData['harga_productM'];
        $man->deskripsi_productM = $updateData['deskripsi_productM'];
        $man->kategori = $updateData['kategori'];
        $man->stok = $updateData['stok'];

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/man/'.$filename;
            $file->move($path,$filename);

            $man->gambar_productM = $path;
        }

        if($man->save()){
            return response([
            'message' => 'Update Product Man Success',
            'data' => $man,
            ],200);
        } 
        
        return response([
            'message' => 'Update Product Man Failed',
            'data' => null,
        ],400);
    }
}
