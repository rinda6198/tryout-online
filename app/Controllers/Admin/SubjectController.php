<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubjectModel;

class SubjectController extends BaseController
{
    protected SubjectModel $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $data = [
            'title'    => 'Manajemen Mata Pelajaran',
            'subjects' => $this->subjectModel->findAll(),
        ];

        return view('admin/subjects/index', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'code' => 'required|max_length[20]|is_unique[subjects.code]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->subjectModel->insert([
            'name'        => $this->request->getPost('name'),
            'code'        => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/subjects')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update($id)
    {
        $subject = $this->subjectModel->find($id);

        if (! $subject) {
            return redirect()->to('/admin/subjects')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'code' => "required|max_length[20]|is_unique[subjects.code,id,{$id}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->subjectModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'code'        => $this->request->getPost('code'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/subjects')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $subject = $this->subjectModel->find($id);

        if (! $subject) {
            return redirect()->to('/admin/subjects')->with('error', 'Data tidak ditemukan.');
        }

        $this->subjectModel->delete($id);

        return redirect()->to('/admin/subjects')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}