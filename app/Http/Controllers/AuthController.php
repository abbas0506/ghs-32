<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AcademicSession;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //
    public function signup(Request $request) {}

    public function  viewLogin()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',

        ]);

        // 
        if (Auth::attempt($credentials)) {
            //user verified, now redirect to his/her dashboard
            $role = Auth::user()->roles->first()->name;
            session([
                'role' => $role,
            ]);

            // Auto-activate academic session matching current date
            $today = now()->toDateString();
            $activeSession = AcademicSession::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            if ($activeSession && !$activeSession->is_current) {
                $activeSession->update(['is_current' => true]);
            }

            session(['academic_session_id' => $activeSession?->id]);

            // go to related dashboard
            return redirect("/");
        } else {
            //user not verified
            return redirect()->back()->with(['warning' => 'User credentials incorrect !']);
        }
    }
    // login step2

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function signout()
    {
        //destroy session
        session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        //change password process
        $request->validate([
            'current' => 'required',
            'new' => 'required',
        ]);

        try {

            if (Hash::check($request->current, $user->password)) {
                $user->password = Hash::make($request->new);
                $user->save();
                return redirect()->back()->with('success', 'successfuly changed');
            } else {
                //password not found
                return redirect()->back()->with('warning', 'Oops, something wrong!');;
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors($e->getMessage());
            // something went wrong
        }
    }
    public function  forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $code = Str::random(5);

            $user->update([
                'password' => Hash::make($code),
            ]);

            try {

                Mail::raw('Password has been reset by ghs32.txdevs.com : ' . $code, function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('Password reset');
                });

                return redirect('login')->with('success', 'Password sent to your email');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            return redirect()->back()->with('warning', 'The email does not exist in record');
        }
    }
}
