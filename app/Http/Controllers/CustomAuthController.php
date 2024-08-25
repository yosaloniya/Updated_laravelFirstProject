<?php
namespace App\Http\Controllers;
use Hash;
use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Models\Sales;
use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function customLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        if ($user->status == 1) {
            return redirect()->intended('/')
                ->withSuccess('You have successfully signed-in.');
        } else {
            Auth::logout();
            return redirect("login")->with('error', 'This user does not have login permission.');
        }
    }

    return redirect("login")->with('error', 'Login details are not valid');
}
public function forgotpass(){
    return view('auth.forgotpass');
}

public function changepass(){
    return view('auth.changepass');
}
    
    public function dashboard()
    {
        
        $dbproduct = count(DB::table('products')->get());
        $subproduct = count(DB::table('subskus')->get());
        // $rproduct = $subproduct-1;
        $totalpr = $dbproduct+$subproduct;
        if (Auth::check()) {
            $loan=Sales::all();
            // return $loan;
            
            
            return view('welcome',compact('loan','totalpr'))->with('success','You have Successfully logged in');
        }else{
            return redirect("login")->with('error', 'Please Login First.');
        }
    }
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
    
    public function cheack_email(Request $request){
        date_default_timezone_set("Asia/Calcutta");
        if (User::where('email',$request->email)->exists()) {
            $id=User::where('email',$request->email)->value('id');
        // Delete any existing OTPs for the user
        Otp::where('user_id', $id)->delete();
            $otp=rand(1000,9999);
            $mailData = [
                'title' => 'Mail from TheCozycreations.com',
                'otp' =>$otp
            ];
            $data=new Otp();
            $data->user_id=$id;
            $data->otp=$otp;
            $data->time=date('h:i:s:a');
            $data->save();
            Mail::to($request->email)->send(new DemoMail($mailData));
            return redirect()->route('verify-otp', ['userId' => $id])->withSuccess('OTP sent to your registered email.');           // Session::flash('success', 'OTP Successfully sent to your registered email-id.');
        }else{
            //  Session::flash('error', 'This email is not available'); 
            return back()->witherror('Email not found.');
        }
    }

    public function resendOtp(Request $request, $id) {
        // Check if the user exists
        $user = User::find($id);
        if (!$user) {
            return back()->withError('User not found.');
        }
    
        // Generate a new OTP
        $otp = rand(1000, 9999);
    
        // Delete any existing OTPs for the user
        Otp::where('user_id', $id)->delete();
    
        // Save the new OTP
        $newOtp = new Otp();
        $newOtp->user_id = $id;
        $newOtp->otp = $otp;
        $newOtp->time = Carbon::now();
        $newOtp->save();
    
        // Send the OTP via email
        $mailData = [
            'title' => 'Mail from TheCozycreations.com',
            'otp' => $otp
        ];
        Mail::to($user->email)->send(new DemoMail($mailData));
    
        return redirect()->route('verify-otp', $id)->withSuccess('New OTP sent to your registered email.');
    }
    
    
    public function otp_varification(Request $request,$id){
        if (Otp::where('user_id',$id)->where('otp',$request->otp)->exists()) {
            Otp::where('user_id',$id)->where('otp',$request->otp)->delete();
            return redirect('change-password/'.$id);
        }else{
            // Session::flash('error', 'This otp is not available'); 
            return back()->witherror('Invalid OTP.');
        }
    }
    public function validateotp($userId){
    // Calculate the time 5 minutes ago
    $expiryTime = Carbon::now()->subMinutes(5);
    
    // Find the OTP for the given user ID
    $otp = Otp::where('user_id', $userId)->first();

    if($otp) {
        // Get the creation time of the OTP
        $otpCreationTime = $otp->created_at;

        // Check if the OTP is older than 5 minutes
        if ($otpCreationTime->lte($expiryTime)) {
            // If it's older, delete the OTP
            $otp->delete();
        } else {
            // Otherwise, handle the case where the OTP is still valid
        }
    }

    // Render the OTP verification view
    return view('auth.otp', ['userId' => $userId]);
}
    
    public function updatepass(Request $request,$id){
        $user=User::find($id);
        $user->password=hash::make($request->password);
        $user->update();
        return redirect('login')->withsuccess('Password Successfully changed.');
    }
}