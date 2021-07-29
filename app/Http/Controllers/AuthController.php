<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        // $result = User::where('name','=',$request->username)->first();
        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post('http://localhost:9000/oauth/token', [

            'form_params' => [
                'grant_type' => 'password',
                'client_id' =>2,
                'client_secret' =>'dUvdneeyxMwS8LVw4iEoSSf30RVPSZk0TFSUDlU9',
                'username' => $request->username,
                'password' => $request->password,
            ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() == 400) {
                return response()->json('Invalid Request. Please enter the email or password. ', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server, server might be  off.', $e->getCode());
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('Logged out successfully', 200);
    }
}
