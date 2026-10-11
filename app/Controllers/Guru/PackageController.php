<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\SubjectModel;

class PackageController extends BaseController
{
    protected PackageModel $packageModel;
    protected SubjectModel $subjectModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        if (session()->get('role_slug') === 'admin') {

            $packages = $this->packageModel->getWithSubject();

            $title = 'Semua Paket Try Out';

        } else {

            $packages = $this->packageModel
                ->getByCreator(session()->get('user_id'));

            $title = 'Paket Try Out Saya';
        }

        foreach ($packages as &$pkg) {
            $pkg['question_count'] =
                $this->packageModel
                    ->getQuestionCount($pkg['id']);
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('admin/packages/index', [
            'title' => $title,
            'base' => $base,
            'packages' => $packages,
        ]);
    }

    public function create()
    {
        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        $data = [
            'title' => 'Tambah Paket Try Out',
            'base' => $base,
            'subjects' => $this->subjectModel->findAll(),
            'created_by' => session()->get('user_id'),
        ];

        return view('admin/packages/create', $data);
    }

    public function store()
    {
        $rules = [
            'subject_id' => 'required|integer',
            'title' => 'required|min_length[5]|max_length[150]',
            'duration' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->packageModel->insert([
            'subject_id' => $this->request->getPost('subject_id'),
            'created_by' => session()->get('user_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'duration' => $this->request->getPost('duration'),
            'token' => $this->request->getPost('token') ?: null,
            'is_random_question' => $this->request->getPost('is_random_question') ? 1 : 0,
            'is_random_option' => $this->request->getPost('is_random_option') ? 1 : 0,
            'passing_grade' => $this->request->getPost('passing_grade') ?: 0,
            'status' => $this->request->getPost('status') ?: 'draft',
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time' => $this->request->getPost('end_time') ?: null,
        ]);

        return redirect()->to('/guru/packages')->with('success', 'Paket try out berhasil ditambahkan.');
    }

    /**
     * FIX: pastikan paket yang diakses memang milik guru yang login.
     * Mengembalikan null jika tidak ditemukan ATAU bukan milik guru ini.
     */
    private function findOwnedPackage($id)
    {
        $package = $this->packageModel->find($id);

        if (!$package) {
            return null;
        }

        // ADMIN BOLEH AKSES SEMUA
        if (session()->get('role_slug') === 'admin') {
            return $package;
        }

        // GURU HANYA MILIK SENDIRI
        if (
            (int) $package['created_by']
            !==
            (int) session()->get('user_id')
        ) {
            return null;
        }

        return $package;
    }

    public function edit($id)
    {
        $package = $this->findOwnedPackage($id);

        if (!$package) {
            return redirect()->to('/guru/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';


        $data = [
            'title' => 'Edit Paket Try Out',
            'base' => $base,
            'package' => $package,
            'subjects' => $this->subjectModel->findAll(),
        ];

        return view('admin/packages/edit', $data);
    }

    public function update($id)
    {
        $package = $this->findOwnedPackage($id);

        if (!$package) {
            return redirect()->to('/guru/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $rules = [
            'subject_id' => 'required|integer',
            'title' => 'required|min_length[5]|max_length[150]',
            'duration' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->packageModel->update($id, [
            'subject_id' => $this->request->getPost('subject_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'duration' => $this->request->getPost('duration'),
            'token' => $this->request->getPost('token') ?: null,
            'is_random_question' => $this->request->getPost('is_random_question') ? 1 : 0,
            'is_random_option' => $this->request->getPost('is_random_option') ? 1 : 0,
            'passing_grade' => $this->request->getPost('passing_grade') ?: 0,
            'status' => $this->request->getPost('status') ?: 'draft',
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time' => $this->request->getPost('end_time') ?: null,
        ]);

        return redirect()->to('/guru/packages')->with('success', 'Paket try out berhasil diperbarui.');
    }

    public function delete($id)
    {
        $package = $this->findOwnedPackage($id);

        if (!$package) {
            return redirect()->to('/guru/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $this->packageModel->delete($id);

        return redirect()->to('/guru/packages')->with('success', 'Paket try out berhasil dihapus.');
    }
}
