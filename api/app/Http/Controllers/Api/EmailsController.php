<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Notif;
use App\Models\Question;

use Illuminate\Support\Str;
use App\Mail\VerificationEmail;
use App\Mail\NewPassword;
use App\Mail\WelcomeMail;
use App\Mail\RdvEmail;
use App\Mail\MessageEmail;
use App\Mail\Form1Mail;
use App\Mail\MailtrapExample;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Storage;
use PDF;

use Carbon\Carbon;

class EmailsController extends Controller
{
   //  Register
 public function sendrdv(Request $request) {

    $user = new user;
    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->email = $request->email;
    $user->subject = "Demande de rendez-vous";
    $user->phone_number = $request->phone_number;
    $user->message = $request->message;
    $test=$request->date;
    $test = strtotime($test);
    $user->jour= date('d-m-Y',$test);
    $user->heure= date('H:i',$test);


    Mail::to(env('ADMIN_EMAILS'))->send(new RdvEmail($user));
    return response()->json([
        'message' => 'Successfully created', $test
    ]);

    }


 //  Register
 public function sendform1(Request $request) {


   $fileName=time().'.pdf';

   $user = new user;
   $user->firstname =$request->firstname;
   $user->lastname = $request->lastname;
   $user->phone_number= $request->phone_number;
   $user->email = $request->email;
   $user->subject = "Questionnaire 1";
   $user->message=$request->message;
   $user->fileName=$fileName;

   $pdf = PDF::loadView('pdf.form1',['user' => $user],  )->save('pdf/'.$fileName);

   Mail::to(env('ADMIN_EMAILS'))->send(new Form1Mail($user,$fileName));



   $notif = Notif::create([
        'title' => "PDF",
        'content' => "Pdf à télécharger",
        'edited_by' => $request->id,
        'link' => 'pdf/'.$fileName,
        'category' => "PDF",
        'view'=>0
    ]);

   return response()->json([
    'message' => 'Successfully created',$user
]);



}


   //  Register
   public function testemail(Request $request) {

    $user = new user;
    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->subject = "Demande de rendez-vous";


    Mail::to(env('ADMIN_EMAILS'))->send(new MessageEmail($user));
    return response()->json([
        'message' => 'Successfully created', $user
    ]);

    }

  //  Register
  public function savepdfwithoutemail(Request $request) {

    $user = new user;

    $user->lastname = $request->lastname;
    $user->subject = "Demande de rendez-vous";


    $data = Question::all();


  //  view()->share('user',$data);

    $pdf = PDF::loadView('pdf.survey1',['data' => $data] );

    $randomtitle=random_int(100000,999999);
    $path = public_path('pdf/');
    $fileName =  $randomtitle . '.' . 'pdf' ;
    $pdf->save($path . '/' . $fileName);
    return $pdf->download($fileName);


    }




}
