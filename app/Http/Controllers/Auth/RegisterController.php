<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libs\Cipher;
use App\Libs\Translation;
use App\Libs\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        return $this->view('auth.register');
    }

    public function register()
    {
        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $rules = [
            'username' => 'required|min:5|max:255|exists:users,username',
            'password' => 'required|min:8|max:255'
        ];

        $fieldNames = [
            'username' => strtolower(Translation::trans('auth', 'username')),
            'password' => strtolower(Translation::trans('auth', 'password'))
        ];

        $validator = new Validator($data, $rules, $fieldNames);

        if (!$validator->validate()) {
            $errors = $validator->errors();
            return $this->view('auth.register', compact('data', 'errors'));
        }

        $data['password'] = Cipher::encrypt($data['password']);

        $userModel = new User();
        $user = $userModel->create($data);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
        ];
        
        return $this->redirect('/admin/tasks');
    }
}