<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libs\Cipher;
use App\Libs\Flash;
use App\Libs\Translation;
use App\Libs\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return $this->view('auth.login');
    }

    public function login()
    {
        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $rules = [
            'username' => 'required|min:5|max:255',
            'password' => 'required|min:8'
        ];

        $fieldNames = [
            'username' => strtolower(Translation::trans('auth', 'username')),
            'password' => strtolower(Translation::trans('auth', 'password'))
        ];

        $validator = new Validator($data, $rules, $fieldNames);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            return $this->view('auth.login', compact('data', 'errors'));
        }

        $userModel = new User();
        $user = $userModel->where('username', $data['username'])->first();

        if($user && Cipher::isEqual($data['password'], $user['password'])){
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
            return $this->redirect('/admin/tasks');
        }else{
            Flash::set('error', Translation::trans('auth', 'credentials_error'));
            return $this->view('auth.login', compact('data'));
        }
    }

    public function logout()
    {
        session_destroy();
        return $this->redirect('/');
    }
}