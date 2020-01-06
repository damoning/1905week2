<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Pusers;
class TestController extends Controller
{
    public function test(){
//        echo "111";
        $user_info=[
            'name'=>'zhangsan',
            'age'=>1,
            'email'=>'zhangsan@qq.com'
        ];
        return json_encode($user_info);
    }
    public function reg(Request $request){
        $pass1=$request->input('pass1');
        $pass2=$request->input('pass2');
        if($pass1!=$pass2){
            die('两次输入的密码不一致');
        }
        $password=password_hash($pass1,PASSWORD_BCRYPT);
        $data=[
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>$password,
            'last_login'=>time(),
            'last_ip'=>$_SERVER['REMOTE_ADDR'],
        ];
        $res=Pusers::insertGetId($data);
    }
    public function login(Request $request){
        $name=$request->input('name');
        $pass=$request->input('pass');
        $u=Pusers::where(['name'=>$name])->first();
        if($u){
            if(password_varify($pass,$u->password)) {
                echo "登录成功";
                $token = Str::random(32);
                $response = [
                    'erron' => 0,
                    'msg' => 'ok',
                    'data' => [
                        'token' => $token
                    ]
                ];
            }else {
                $response = [
                    'error' => 400003,
                    'msg' => "密码不正确",
                ];
             }
            }else{
                $response=[
                    'errno'=>400004,
                    'msg'=>"用户不存在"
                ];
            }
            return $response;

    }
    /**
     * 获取用户列表
     * 2020年1月2日16:32:07
     */
    public function userList()
    {
        $data=Pusers::all();
        var_dump($data);
    }
    
}

