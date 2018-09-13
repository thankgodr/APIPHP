<?php

namespace App\Api\V1\Controllers;

use Config;
use Illuminate\Http\Request; 
use App\User;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Auth;
use Validator;

class SignUpController extends Controller
{
     public $successStatus = 201;
    public function signUp(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }

        if(!Config::get('boilerplate.sign_up.release_token')) {
            return response()->json([
                'status' => 'ok'
            ], 201);
        }

        $token = $JWTAuth->fromUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token
        ], 201);
    }


    public function updateUser(Request $request) { 

        //Validation
        $validator = Validator::make($request->all(), [ 
            'name' => 'string', 
            'email' => 'email|unique:users,email', 
            'password' => 'string', 
            'c_password' => 'same:password', 
        ]);

        //Validation has failed
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 413);            
        }
        

        //Get current login user
        $user = Auth::user();

        //Update user 
        $saved = $user->update($input); 
        if($saved){
            //User Update was succesfull
            $success['error'] = false;
            $success['message'] = "User updated";
            return response()->json($success, $this-> successStatus); 
        }
        else{
            //User update failed
            $success['error'] = True;
            $success['message'] = "User not updated";
            return response()->json($success, 401); 
        }
    }

    public function getUserDetails(){
        //Get current login user
        $user = Auth::user();
        $info['error'] = false;
        $info["name"] = $user->name;
        $info['email'] = $user->email;
        return response()->json($info, $this-> successStatus);
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function delete() { 
        $user = Auth::user(); 
        $user->delete();
        return response()->json(['error' => false, 'message' => 'Account deleted']);
    } 
   
    /***********************************************Admin functions***************************************
    /** 
     * Add user by admin only api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function adminAddUser(Request $request){
         //Validation
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users,email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);

        //Validation has failed
        if ($validator->fails()) { 
            return response()->json(['error' => true,'errors'=>$validator->errors()], 413);            
        }

         $user = new User($request->all());
        if(!$user->save()) {
            throw new HttpException(500);
        }else{
            return response()->json(['status' => 'ok', 'message' => 'Account created']);
        }
    }

     
     /** 
     * Update user by admin only api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function adminUpdateUser(Request $request){

        //Vadation
         $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id',
            'name' => 'string',
            'email' => 'unique:users,email',
            'password' => 'string',
            'c_password' => 'same:password'
        
        ]);

        //Validation fails
          if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }


        $user = User::find(request('user_id'));
        $saved = $user->update($request->all());
        if($saved){
            return response()->json(['error' => false, 'message' => 'User updated'], $this-> successStatus);
        }else{
             return response()->json(['error' => true, 'message' => 'Unable to update user info'], 401);
        }
    }
    

    /** 
     * delete user by admin only api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function adminDeleteUser(Request $request){
        //Vadation
         $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id'
        ]);

         //Validation fails
          if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

         $user = User::find(request('user_id'));

         $delete = $user->delete();

          if($delete){
            return response()->json(['error' => false, 'message' => 'User deleted'], $this-> successStatus);
        }else{
             return response()->json(['error' => true, 'message' => 'Unable to delete user'], 401);
        }
    }


    /** 
     * get user by admin only api 
     * 
     * @return \Illuminate\Http\Response 
    */ 
    public function adminGetUser(Request $request){
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id'
        ]);

         //Validation fails
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }


        $user = User::find(request('user_id'));
        $info['error'] = false;
        $info['name'] = $user->name;
        $info['email'] = $user->email;
        return response()->json($info, $this-> successStatus);

    }

}
