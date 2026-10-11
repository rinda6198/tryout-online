<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

class AnnouncementController extends BaseController
{
    protected AnnouncementModel $announcementModel;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
    }

    public function index()
    {
        return view('admin/announcements/index', [
            'title' => 'Pengumuman',
            'announcements' => $this->announcementModel
                ->orderBy('id', 'DESC')
                ->findAll()
        ]);
    }

    public function store()
    {
        $data = [
            'title'      => $this->request->getPost('title'),
            'content'    => $this->request->getPost('content'),
            'created_by' => session()->get('user_id'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->announcementModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->announcementModel->errors());
        }

        return redirect()->to('/admin/announcements')
            ->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function update($id)
    {
        $data = [
            'title'     => $this->request->getPost('title'),
            'content'   => $this->request->getPost('content'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if (!$this->announcementModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->announcementModel->errors());
        }

        return redirect()->to('/admin/announcements')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->announcementModel->delete($id);

        return redirect()->to('/admin/announcements')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}