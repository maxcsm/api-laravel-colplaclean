<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Auth;
use Validator;
use Password;
//use Illuminate\Foundation\Auth\VerifiesEmails;
//use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewUser;
use App\Mail\NewPassword;
use App\Mail\AdminnewuserEmail;
use App\Mail\RegisterUser;



class AuthController extends Controller
{

      //  use VerifiesEmails;
        public $successStatus = 200;
        /**
        * login api
        *
        * @return \Illuminate\Http\Response
        */

        public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
        $user = Auth::user();
        if($user->email_verified_at !== NULL){
        $success['message'] = "Login successfull";
        $token = $user->createToken('LaravelAuthApp')->accessToken;
        return response()->json(['success' => $success,$user,'token' => $token], $this-> successStatus);
        }else{
        return response()->json(['error'=>'Vous devez validez votre compte', $user,'verif' => 0]);
     
        }
        }
        else{
        return response()->json(['error'=>'Mot de passe ou identifiant incorrect'], 401);
        }
        }


        /**
        * Register api
        *
        * @return \Illuminate\Http\Response
        */
        public function register(Request $request)
        {
      

        $randomcode=random_int(10000,99999);
         Validator::make($request->all(), [
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string','min:5','max:20'],
        ])->validate();
     
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $input['role'] =1;
        $input['code']=$randomcode;


        $user = User::create($input);

     //   Mail::to($user['email'])->send(new NewUser($user));

       // EMAIL VERIF ADMIN
       // Mail::to($request->email)->send(new RegisterUser($user));

       // Mail::to(env('ADMIN_EMAILS'))->send(new AdminnewuserEmail($user));
        //EMAIL VERIF


      //  $user->sendApiEmailVerificationNotification();
       // $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';

       return response()->json( $user, 200);

       /// return response()->json(['success'=>$success]);
        }





        /**
        * details api
        *
        * @return \Illuminate\Http\Response
        */
        public function details()
        {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
        }




        /**
        * Register api
        *
        * @return \Illuminate\Http\Response
        */
        public function adduser(Request $request)
        {
        $validator = Validator::make($request->all(), [
        'lastname' => 'required',
        'firstname' => 'required',
        'email' => 'required|email',
        ]);


        $newpassword =Str::random(6);
        $input = $request->all();
        $input['password'] = Hash::make($newpassword );
        $user = User::create($input);
        $user = [
            'email' => $request->email,
            'password' => $newpassword,
            
        ];


   
        ///EMAIL VERIF
        // $user->sendApiEmailVerificationNotification();

        Mail::to($user['email'])->send(new NewUser($user));


        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return response()->json(['success'=>$success], $this-> successStatus);
        }




            //Reset password
            public function resetpassword(Request $request){
                $input = $request->all();
                $email= $input['email'];
                $newpassword =Str::random(6);
                $user = User::where('email',$email)->first();

                
                $user->password = Hash::make($newpassword);
                $user->save();


                $user = [
                    'email' => $request->email,
                    'password' => $newpassword,
                ];
           
              Mail::to($user['email'])->send(new NewPassword($user));
               
               return response()->json([
                'message' => 'Successfully password changed',$user
            ]);

                }

               //Update password

                public function change_password(Request $request,$id)
                {
                    $validator = Validator::make($request->all(), [
                        'new_password' => 'required|min:5',
                        'confirm_password' => 'required|same:new_password',
                    ]);

                    if ($validator->fails()) {
                        return response()->json(['error'=>$validator->errors()], 401);
                    }
                    $user = User::where('id',$id)->first();
                    $user->password = Hash::make($request->new_password);


                    $user->save();
                    return response()->json([
                        'message' => 'Successfully password changed NEW'
                    ]);
                }



                    /**
                 * Logout user (Revoke the token)
                 *
                 * @return [string] message
                 */
                public function logout(Request $request)
                {
                    $request->user()->token()->revoke();
                    return response()->json([
                        'message' => 'Successfully logged out'
                    ]);
                }


                public function testemail()
{




                
                   $user = [
                    'title' => 'Mail from ItSolutionStuff.com',
                    'body' => 'This is for testing email using smtp'
                ];
               
                \Mail::to('maximbuisson@gmail.com')->send(new \App\Mail\WelcomeMail($user));

                  
    }



}
