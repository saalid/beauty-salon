<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\UserBoughtLicense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Kavenegar;
use App\Services\UserBoughtLicenseService;

class AuthController extends Controller
{
    private $kavenegar;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'loginVerify']]);
        $this->kavenegar = new Kavenegar();
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('phone', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
	}

	$randomNumber = rand(11111, 99999);
        if(config('app.mode') === "production")
        {
            $this->kavenegar->sendOtp($request->phone, $randomNumber);
	}

        $user = Auth::user();
        User::where('id', '=', $user->id)->update([
            'verification_code_sms' => $randomNumber,
            'status_code' => config('user.statusTitle.not verified')
        ]);
        return response()->json([
            'user' => $user->phone,
            'verifyCode' => $randomNumber,
        ]);
    }

    public function loginVerify(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
            'verification_code_sms' => 'required',
        ]);
        $user = User::where('phone', $request->post('phone')) -> first();
        if($user->verification_code_sms === $request->post('verification_code_sms'))
        {
            $credentials = $request->only('phone', 'password', 'verification_code_sms');
            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            User::where('id', '=', $user->id)->update([
                'verification_code_sms' => null,
                'status_code' => config('user.statusTitle.verified')
            ]);
            
            // Values to update or set if the record doesn't exist
            $attributes = ['user_id' => $user->id];
            $values = ['sum' => 0];

            $user = Cart::firstOrCreate($attributes, $values);;

            return response()->json([
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        return response()->json([
            'message' => 'Unauthorized',
        ], 401);


    }

    public function getUser()
    {
        return response()->json(auth()->user());
    }

    public function getLicense()
    {
        $infoLicense = (UserBoughtLicense::where('user_id', auth()->user()->id))->first();
        if($infoLicense->count === 0){
            $result = [
                "status" => false,
                "message" => "user not yet buy course"
            ];
        }else{
            $result = [
                "status" => true,
                "license" => $infoLicense->license_key
            ];
        }
        return response()->json($result);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:11',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if (User::where('phone', '=', $request->phone)->exists()) {
            return response()->json([
                'message' => 'User before exist',
                'status' => false
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Cart::create([
            'user_id' => $user->id,
            'sum' => 0
        ]);


        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
