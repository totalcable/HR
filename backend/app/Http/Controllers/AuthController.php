<?php

namespace App\Http\Controllers;


use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $r)
    {

        $credentials = request(['email', 'password']);

//        return $credentials;

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does not match'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
//        return auth()->user()->id;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out','flag'=>'true']);
    }

    /**
     * Refresh a token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {


        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 1 * (60 * 24),
            'user' => auth()->user(),

        ]);
    }



    public function changePassword(Request $r){
        $user=User::findOrFail(auth()->user()->id);
        $currentPass= Hash::make($r->currentPassword);
        $newPass=Hash::make($r->password);


        if(Hash::check($r->currentPassword, $user->password)){
            $user->password= $newPass;
            $user->save();
//            Session::flash('message', 'Password Changed successfully');
            return response()->json('Password Changed successfully');
        }

//        Session::flash('message', 'Password did not match');
        return response()->json('Password did not match');

    }

}