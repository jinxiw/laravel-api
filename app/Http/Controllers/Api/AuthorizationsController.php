<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;


        $credentials['password'] = $request->password;

        if(!$token = \Auth::guard('api')->attempt($credentials)){
            throw new AuthenticationException('用户名或者密码错误');
        }

        return $this->responseWithToken($token)->setStatusCode(201);

    }

    public function update()
    {
        $token = auth('api')->refresh();
        return $this->responseWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null, 204);
    }

    protected function responseWithToken($token)
    {
       return response()->json([
           [
               'access_token' => $token,
               'token_type' => 'Bearer',
               'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
           ]
       ]);
    }
}
