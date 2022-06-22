<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Models\User;
use App\Models\Profile;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteMail;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
	public function index(Request $request)
	{
    $company = Profile::find(auth()->user()->company_id);
    if ($company)
      $members = $company->users;
    else
      $members = [];
    $roles = Level::all();
		return view('pages.members')->with(['members'=>$members, 'roles' => $roles]);
	}
  public function create(Request $request)
	{
    $validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|unique:users',
			'role' => 'required',
		]);
		if ($validator->fails()) {
      return back()->withErrors(['msg' => __('Sorry, Something went wrong. Try again, please.')]);
		} else {
			$data = $request->all();
      $member = new User;
      $member->name = $data['name'];
      $member->email = $data['email'];
      $member->level = $data['role'];
      $member->password = Hash::make($data['password']);
      $member->company_id = auth()->user()->company_id;
      $member->api_token = Str::random(60);
      $member->last_login = date('Y-m-d H:i:s');
      $member->save();
      //Create Password Reset Token
      // DB::table('password_resets')->insert([
      //   'email' => $data['email'],
      //   'token' => Str::random(60),
      //   'created_at' => date('Y-m-d H:i:s')
      // ]);
      //Get the token just created above
      // $tokenData = DB::table('password_resets')
      //   ->where('email', $data['email'])->first();
        
      // $link = url('/') . '/password/reset/' . $tokenData->token . '?invite=1&email=' . urlencode($data['email']);
			// $data['link'] = $link;
			// $data['sender'] = auth()->user()->name;
			// $data['team'] = $data['name'];
			// $data['company'] = auth()->user()->profile->company;
			// $data['subject'] = "{$data['sender']} has invited you to join {$data['company']} on Priority.";
      // $this->sendInviteEmail($data['email'], $data);
    }
		return back()->withStatus( __('Team member successfully created.'));
	}

  public function sendInviteEmail($contact, $data) {
    $mail = Mail::to($contact);
    if (isset($data['bcc']) && $data['bcc']!='')
      $mail = $mail->bcc($data['bcc']);
  
    $mail->send(new InviteMail($data));
  }
}
