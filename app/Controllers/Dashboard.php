<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role_slug');

        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'guru') {
            return redirect()->to('/guru/dashboard');
        }

        if ($role === 'peserta') {
            return redirect()->to('/peserta/dashboard');
        }

        

        return redirect()->to('/login');
    }
}