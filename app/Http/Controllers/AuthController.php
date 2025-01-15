<?php

namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Exception;
    use Illuminate\Validation\Rule;
    

class AuthController extends Controller
{
    function register(Request $request) {
        $validator = Validator::make($request->all(),[
            "name"=>'required',
            "email"=>'required|unique:users',
            "address"=>'required',
            "birthday"=>'required',
            "role"=>'required',
            "password"=>'required',
        ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }   
        $data = [
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password')),
            'role'=>$request->get('role'),
            'adress'=>$request->get('address'),
            'birthday'=>$request->get("birthday"),
        ];
        try {
            $insert = User::create($data);
            return Response()->json(["status"=>true, "message"=>"Data berhasil ditambahkan"]);
        } catch (Exception $e) {
            return Response()->json(["status"=>false,"message"=>$e]);
        }
    }

    function getUser(){
        try {
            $user = User::get();
            return response()->json([
                'status' => true,
                'message'=>'berhasil load data user',
                'data'=> $user,
            ]);
        } catch (exception $e) {
            return response()->json([
                'status'=>false,
                'message'=>'gagal load data user. ', $e,
            ]);
        }
    }

    function getDetailUser($id){
        try {
            $user = User::where('id',$id)->first();
            return response()->json([
            'status'=>true,
            'message'=>'berhasil load data detail user',
            'data'=>$user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> false,
                'message'=>'gagal load data detail user. ', $e,
            ]);
        }
    }

    function update_user($id, Request $request){
        $validator = Validator ::make ($request->all(),[
            "name"=>'required',
            "email"=>['required', Rule::unique('users')->ignore($id)],
            "address"=>'required',
            "birthday"=>'required',
            "role"=>'required',
            "password"=>'required',
        ]);

        if ($validator-> fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors(),
            ]);
        }
        $data = [
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password')),
            'role'=>$request->get('role'),
            'adress'=>$request->get('address'),
            'birthday'=>$request->get("birthday"),            
        ];
        try {
            $update = User::where('id',$id)->update($data);
            return Response()->json([
                'status'=>true,
                'message'=>'Data berhasil di update'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                "status"=>false,
                'message'=>$e,
            ]);
        }
    }
    function hapus_user($id){
        try {
            User::where('id',$id)->delete();
            return Response()->json([
                "status"=>true,
                'message'=>'Data Berhasil Dihapus'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                "status"=>false,
                'message'=>'gagal hapus user.',$e,
            ]);
        }
    }
};
