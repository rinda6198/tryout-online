<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function attemptLogin()
    {
        $identity = $this->request->getPost('identity');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        $user = $userModel->findByUsernameOrEmail($identity);

        if (!$user) {
            return redirect()->back()->with(
                'error',
                'Username atau email tidak ditemukan'
            );
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with(
                'error',
                'Password salah'
            );
        }

        if (isset($user['is_active']) && !$user['is_active']) {
            return redirect()->back()->with(
                'error',
                'Akun tidak aktif'
            );
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role_id'    => $user['role_id'],
            'role_slug'  => $user['role_slug'],
            'role_name'  => $user['role_name'],
        ]);


        switch ($user['role_slug']) {
            case 'admin':
                return redirect()->to('/admin/dashboard');

            case 'guru':
                return redirect()->to('/guru/dashboard');

            case 'peserta':
                return redirect()->to('/peserta/dashboard');

            default:
                session()->destroy();
                return redirect()->to('/login')
                    ->with('error', 'Role tidak valid');
        }
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        $password = $this->request->getPost('password');

        $data = [
            'role_id'   => 3,
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => 1,
        ];

        if (!$userModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $userModel->errors());
        }

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Berhasil logout');
    }
}