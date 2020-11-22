<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\OrderPayment;

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
            'address' => 'required|alphanumeric',
            'city' => 'required|alphanumeric',
            'province' => 'required|alphanumeric',
            'postal_code' => 'required|numeric',
            'total_harga' => 'required|numeric',
            'phoneNumber' => 'required|alphanumeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/order/'.$filename;
            $file->move($path,$filename);

        }

        $order = new OrderPayment;
        $order->id_user = $request->id_user;
        $product->address = $request->address;
        $product->city = $request->city;
        $product->province = $request->province;
        $product->postal_code = $request->postal_code;
        $product->total_harga = $request->total_harga;
        $product->bukti_tf = $path;
        $product->phoneNumber = $request->phoneNumber;
        $product->save();
        
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
            'id_user' => 'required|numeric',
            'address' => 'required|alphanumeric',
            'city' => 'required|alphanumeric',
            'province' => 'required|alphanumeric',
            'postal_code' => 'required|numeric',
            'total_harga' => 'required|numeric',
            'phoneNumber' => 'required|alphanumeric'
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
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time().$file->getClientOriginalName();
            $path = base_path().'/public/uploads/order/'.$filename;
            $file->move($path,$filename);
            $order->bukti_tf = $path;
        }

        if($cart->save()){
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
}
