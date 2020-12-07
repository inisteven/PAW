<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Man;

class ManController extends Controller
{
    public function getRandom(){
        $produk = Man::all()->random(1);
        return response([
            'data'=>$produk,
        ]);
    }
    public function index(){
        $men = Man::all();

        // $men = Man::paginate(3)->toArray();

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

        // return array_reverse($men);
    }

    public function show($id){
        $man = Man::where('id_produkM','=', $id)->first();

        if(!is_null($man)){
            return response([
                'message' => 'Retrieve product man Success',
                'data' => $man
            ],200);
        }

        return response([
            'message' => 'product man Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_produkM' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_produkM' => 'required|numeric',
            'deskripsi_produkM' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
            'gambar_produkM' => 'required|mimes:jpg,jpeg,png'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if($request->hasfile('gambar_produkM'))
        {
            $file = $request->file('gambar_produkM');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);
        }

        $man = new Man;
        $man->nama_produkM = $request->nama_produkM;
        $man->harga_produkM = $request->harga_produkM;
        $man->deskripsi_produkM = $request->deskripsi_produkM;
        $man->gambar_produkM = $filename;
        $man->stok = $request->stok;
        $man->save();

       // $man = Man::create($storeData);
        
       if($man->save()){
            return response([
                'message' => 'Add product man Success',
                'data' => $man,
            ],200);
       }else{
            return response([
                'message' => 'Add product man Fail',
                'data' => null,
            ],400);
       }      
    }

    public function destroy($id){
        $man = Man::where('id_produkM','=', $id)->first();

        if(is_null($man)){
            return response([
                'message' => 'product man Not Found',
                'data' => $null
            ],404);
        }

        if($man->delete()){
            return response([
            'message' => 'Delete product man Success',
            'data' => $man,
            ],200);
        } 
        
        return response([
            'message' => 'Delete product man Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $man = Man::where('id_produkM','=', $id)->first();
        if(is_null($man)){
            return response([
                'message' => 'product man Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_produkM' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'harga_produkM' => 'required|numeric',
            'deskripsi_produkM' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $man->nama_produkM = $updateData['nama_produkM'];
        $man->harga_produkM = $updateData['harga_produkM'];
        $man->deskripsi_produkM = $updateData['deskripsi_produkM'];
        $man->stok = $updateData['stok'];

        // if($request->hasfile('gambar_produkM'))
        // {
        //     $file = $request->file('gambar_produkM');
        //     $ekstensi = $file->extension();
        //     $filename = 'IMG_'.time().'.'.$ekstensi;
        //     $path = base_path().'/public/products/';
        //     $file->move($path,$filename);

        //     $man->gambar_produkM = $filename;
        // }

        if($man->save()){
            return response([
            'message' => 'Update product man Success',
            'data' => $man,
            ],200);
        } 
        
        return response([
            'message' => 'Update product man Failed',
            'data' => null,
        ],400);
    }
    
    public function uploadImage(Request $request , $id){
        if($request->hasFile('gambar_produkM')){
            $man = Man::find($id);
            if(is_null($man)){
                return response([
                    'message' => 'Product not found',
                    'data' => null
                ],404);
            }

            $updateData = $request->all();
            $validate =  Validator::make($updateData,[
                'gambar_produkM' => 'mimes:jpeg,jpg,png',
            ]);

            if($validate->fails())
                return response(['message' => 'error',400]);


            $file = $request->file('gambar_produkM');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/products/';
            $file->move($path,$filename);

            $man->gambar_produkM = $filename;

            if($man->save()){
                return response([
                    'message'=> 'Upload Image Success',
                    'user'=>$man
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
