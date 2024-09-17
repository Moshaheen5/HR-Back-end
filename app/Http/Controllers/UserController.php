<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function index(){
        $admins=User::with('group')->get();
        return response()->json(['data'=>$admins],200);
    }
    public function store(Request $request)
    {   
        $validateData=$request->validate([
            'fullname' =>['required','string','regex:/^[\pL\s\-]+$/u'],
            'email'=>['required','string','email','unique:user_register,email'],
            'username'=>['required','min:5','string'],
            'password'=>['required','string','min:8'],
            'group_id'=>['required','exists:groups,id'],
        ]);
        $user = User::create([
            'fullname' => $validateData['fullname'],
            'email' => $validateData['email'],
            'password' =>bcrypt( $validateData['password']),
            'username' => $validateData['username'],
            'group_id' => $validateData['group_id'],

        ]);
        return response()->json(['message' => 'User added successfully!','recently added'=>$user], 201);
    }
    public function destroy($id)
    {
        $user=User::find($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully!'], 201);
    }
}
