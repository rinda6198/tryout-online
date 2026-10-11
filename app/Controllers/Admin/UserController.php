<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class UserController extends BaseController
{
    protected UserModel $userModel;
    protected RoleModel $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->getUserWithRole(),
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pengguna',
            'roles' => $this->roleModel->findAll(),
        ];

        return view('admin/users/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[50]|alpha_numeric|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role_id'  => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'  => $this->request->getPost('role_id'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pengguna',
            'user'  => $user,
            'roles' => $this->roleModel->findAll(),
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username' => "required|min_length[3]|max_length[50]|alpha_numeric|is_unique[users.username,id,{$id}]",
            'role_id'  => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'role_id'  => $this->request->getPost('role_id'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ];

        $newPassword = $this->request->getPost('password');
        if (! empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        if ((int) $user['id'] === (int) session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun anda sendiri.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}