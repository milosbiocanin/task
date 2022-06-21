<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class ResetPasswordController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Password Reset Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling password reset requests
  | and uses a simple trait to include this behavior. You're free to
  | explore this trait and override any methods you wish to tweak.
  |
  */

  use ResetsPasswords;

  /**
   * Where to redirect users after resetting their password.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  public function resetPassword (Request $request) {
    $validator = Validator::make($request->all(), [
			'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:8|confirmed',
		]);
    if ($validator->fails()) {
      return back()->withErrors(['email' => [__('Something went wrong.')]]);
    } else {
      $data = $request->only('email', 'password', 'password_confirmation', 'token');
      $token = DB::table('password_resets')->where('email', $data['email'])->first();
      if($token && $token->token != $data['token']) {
        return back()->withErrors(['email' => [__('Something went wrong.')]]);
      }

      $user = User::where('email', $data['email'])->first();
      $user->forceFill([
        'password' => Hash::make($data['password'])
      ])->setRememberToken(Str::random(60));

      $user->save();
      event(new PasswordReset($user));
    }
    return redirect()->route('login')->with('status', __('Success'));
  }

  public function showResetForm(Request $request, $token = null)
  {
    if($request->invite) {
      return view('auth.passwords.resetForInvite')->with(
        ['token' => $token, 'email' => $request->email]
      );
    } else {
      return view('auth.passwords.reset')->with(
        ['token' => $token, 'email' => $request->email]
      );
    }
  }
}
