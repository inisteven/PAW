<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\OrderPayment;
use Illuminate\Support\Facades\DB;

class OrderPaymentController extends Controller
{
    public function index(){
        $orders = OrderPayment::all();

        if(count($orders) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $orders
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],404);
    }

    public function show($id){
        $order = OrderPayment::find($id);

        if(!is_null($order)){
            return response([
                'message' => 'Retrieve Order Payment Success',
                'data' => $order
            ],200);
        }

        return response([
            'message' => 'Order Payment Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_user' => 'required|numeric',
            'address' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'city' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'province' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'postal_code' => 'required|numeric',
            'total_harga' => 'required|numeric',
            'phoneNumber' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/order/'.$filename;
            $file->move($path,$filename);
            $order->bukti_tf = $path;
        }

        $order = new OrderPayment;
        $order->id_user = $request->id_user;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->province = $request->province;
        $order->postal_code = $request->postal_code;
        $order->total_harga = $request->total_harga;
        $order->phoneNumber = $request->phoneNumber;
        $order->bukti_tf = 0;
        $order->save();
        
        return response([
            'message' => 'Add Order Payment Success',
            'data' => $order,
        ],200);
    }

    public function destroy($id){
        $order = OrderPayment::find($id);

        if(is_null($order)){
            return response([
                'message' => 'Order Payment Not Found',
                'data' => $null
            ],404);
        }

        if($order->delete()){
            return response([
            'message' => 'Delete Order Payment Success',
            'data' => $order,
            ],200);
        } 
        
        return response([
            'message' => 'Delete Order Payment Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $order = OrderPayment::find($id);
        if(is_null($order)){
            return response([
                'message' => 'Order Payment Not Found',
                'data' => $null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_user' => 'numeric',
            'address' => 'regex:/^[a-zA-Z0-9\s]+$/',
            'city' => 'regex:/^[a-zA-Z0-9\s]+$/',
            'province' => 'regex:/^[a-zA-Z0-9\s]+$/',
            'postal_code' => 'numeric',
            'total_harga' => 'numeric',
            'phoneNumber' => 'numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $order->id_user = $updateData['id_user'];
        $order->address = $updateData['address'];
        $order->city = $updateData['city'];
        $order->province = $updateData['province'];
        $order->postal_code = $updateData['postal_code'];
        $order->total_harga = $updateData['total_harga'];
        $order->phoneNumber = $updateData['phoneNumber'];
        $order->bukti_tf = $updateData['bukti_tf'];

        if($order->save()){
            return response([
            'message' => 'Update Order Payment Success',
            'data' => $order,
            ],200);
        } 
        
        return response([
            'message' => 'Update Order Payment Failed',
            'data' => null,
        ],400);
    }

    public function find($id){
        $order = DB::table('order_payments')->where('id_user', $id)->where('bukti_tf',0)->first();

        if(!is_null($order)){
            return response([
                'message' => 'Retrieve Order Payment Success',
                'data' => $order
            ],200);
        }

        return response([
            'message' => 'Order Payment Not Found',
            'data' => null
        ],404);
    }

    public function uploadImage(Request $request , $id){
        if($request->hasFile('image')){
            $order = OrderPayment::find($id);
            if(is_null($order)){
                return response([
                    'message' => 'Order Payment not found',
                    'data' => null
                ],404);
            }

            $updateData = $request->all();
            $validate =  Validator::make($updateData,[
                'image' => 'mimes:jpeg,jpg,png',
            ]);

            if($validate->fails())
                return response(['message' => 'error',400]);


            $file = $request->file('image');
            $ekstensi = $file->extension();
            $filename = 'IMG_'.time().'.'.$ekstensi;
            $path = base_path().'/public/order/';
            $file->move($path,$filename);

            $order->bukti_tf = $filename;

            if($order->save()){
                return response([
                    'message'=> 'Upload Image Success',
                    'user'=>$order
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
