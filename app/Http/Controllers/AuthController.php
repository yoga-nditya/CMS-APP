<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
	public function register()
	{
		return view('auth/register');
	}

	public function doRegister(AuthRequest $request)
	{
		User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password),
			'role' => 'admin'
		]);

		return redirect('/login');
	}

	public function login()
	{
		return view('auth/login');
	}

	public function doLogin(Request $request)
	{
		Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required'
		])->validate();

		if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
			throw ValidationException::withMessages([
				'email' => trans('auth.failed')
			]);
		}

		$request->session()->regenerate();

		return redirect()->route('dashboard');
	}

	public function logout(Request $request)
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		return redirect('/');
	}
}