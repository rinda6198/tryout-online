<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;

class QuestionController extends BaseController
{
    protected PackageModel $packageModel;
    protected QuestionModel $questionModel;
    protected OptionModel $optionModel;

    private function getBase()
    {
        return session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';
    }

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
    }


    /**
     * FIX: pastikan paket memang milik guru yang login (atau admin).
     * Admin diberi akses penuh karena route guru juga di-filter
     * dengan 'role:guru,admin'.
     */
    private function findOwnedPackage($packageId)
    {
        $package = $this->packageModel->find($packageId);

        if (!$package) {
            return null;
        }

        if (session()->get('role_slug') === 'admin') {
            return $package;
        }

        if ((int) $package['created_by'] !== (int) session()->get('user_id')) {
            return null;
        }

        return $package;
    }

    private function findOwnedQuestion($questionId)
    {
        $question = $this->questionModel->find($questionId);

        if (!$question) {
            return null;
        }

        $package = $this->findOwnedPackage($question['package_id']);

        if (!$package) {
            return null;
        }

        return ['question' => $question, 'package' => $package];
    }

    public function index($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $questions = $this->questionModel->getWithOptions($packageId);

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/questions/index', [
            'title' => 'Kelola Soal',
            'package' => $package,
            'questions' => $questions,
            'base' => $base,
        ]);
    }

    public function create($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/questions/create', [
            'title' => 'Tambah Soal',
            'package' => $package,
            'packageId' => $packageId,
            'base' => $base,
        ]);
    }

    public function store($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        // FIX: validasi lengkap (sebelumnya tidak ada validasi sama sekali di versi Admin lama)
        $rules = [
            'question_text' => 'required|min_length[3]',
            'points' => 'permit_empty|integer|greater_than[0]',
            'option_A' => 'required',
            'option_B' => 'required',
            'option_C' => 'required',
            'option_D' => 'required',
            'option_E' => 'required',
            'correct_answer' => 'required|in_list[A,B,C,D,E]',
        ];

        // FIX: validasi gambar hanya dijalankan jika file benar-benar diupload
        // (mencegah error validasi saat field 'image' kosong / tidak ada file dipilih)
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && $imageFile->getSize() > 0) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // FIX: handle upload gambar soal
        $imageName = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved() && $imageFile->getSize() > 0) {
            $imageName = $imageFile->getRandomName();

            // FIX: pastikan folder tujuan ada sebelum move (mencegah error "failed to move")
            $uploadPath = FCPATH . 'uploads/questions';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageFile->move($uploadPath, $imageName);
        }

        $questionId = $this->questionModel->insert([
            'package_id' => $packageId,
            'question_text' => $this->request->getPost('question_text'),
            'points' => $this->request->getPost('points') ?: 1,
            'image' => $imageName,
        ]);

        $labels = ['A', 'B', 'C', 'D', 'E'];
        $correctAnswer = $this->request->getPost('correct_answer');

        foreach ($labels as $label) {
            $this->optionModel->insert([
                'question_id' => $questionId,
                'option_label' => $label,
                'option_text' => $this->request->getPost('option_' . $label),
                'is_correct' => $correctAnswer === $label ? 1 : 0,
            ]);
        }
        return redirect()->to('/' . $this->getBase() . '/packages/' . $packageId . '/questions')
            ->with('success', 'Soal berhasil ditambahkan.');

    }

    public function edit($id)
    {
        $found = $this->findOwnedQuestion($id);

        if (!$found) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Soal tidak ditemukan atau bukan milik anda.');
        }

        $question = $found['question'];
        $package = $found['package'];

        // FIX: ambil options agar bisa ditampilkan di form edit (sesuai view edit.php yang mengiterasi $options)
        $options = $this->optionModel->getByQuestion($question['id']);

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/questions/edit', [
            'title' => 'Edit Soal',
            'package' => $package,
            'question' => $question,
            'options' => $options,
            'base' => $base,
        ]);
    }

    public function update($id)
    {
        $found = $this->findOwnedQuestion($id);

        if (!$found) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Soal tidak ditemukan atau bukan milik anda.');
        }

        $question = $found['question'];
        $package = $found['package'];

        $rules = [
            'question_text' => 'required|min_length[3]',
            'points' => 'permit_empty|integer|greater_than[0]',
            'option_A' => 'required',
            'option_B' => 'required',
            'option_C' => 'required',
            'option_D' => 'required',
            'option_E' => 'required',
            'correct_answer' => 'required|in_list[A,B,C,D,E]',
        ];

        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && $imageFile->getSize() > 0) {
            $rules['image'] = 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'question_text' => $this->request->getPost('question_text'),
            'points' => $this->request->getPost('points') ?: 1,
        ];

        // FIX: handle ganti gambar (hapus file lama jika ada gambar baru)
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved() && $imageFile->getSize() > 0) {
            if (!empty($question['image'])) {
                $oldPath = FCPATH . 'uploads/questions/' . $question['image'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $uploadPath = FCPATH . 'uploads/questions';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
            $updateData['image'] = $imageName;
        }

        $this->questionModel->update($id, $updateData);

        // FIX: update semua 5 opsi + kunci jawaban berdasarkan option_label
        // (mencegah salah pasang jika urutan $options dari DB berbeda)
        $existingOptions = $this->optionModel->getByQuestion($id);
        $optionsByLabel = [];
        foreach ($existingOptions as $opt) {
            $optionsByLabel[$opt['option_label']] = $opt;
        }

        $labels = ['A', 'B', 'C', 'D', 'E'];
        $correctAnswer = $this->request->getPost('correct_answer');

        foreach ($labels as $label) {
            $optionData = [
                'option_text' => $this->request->getPost('option_' . $label),
                'is_correct' => $correctAnswer === $label ? 1 : 0,
            ];

            if (isset($optionsByLabel[$label])) {
                $this->optionModel->update($optionsByLabel[$label]['id'], $optionData);
            } else {
                // FIX: jika opsi dengan label ini belum ada (data lama tidak lengkap), buat baru
                $optionData['question_id'] = $id;
                $optionData['option_label'] = $label;
                $this->optionModel->insert($optionData);
            }
        }

        return redirect()->to('/' . $this->getBase() . '/packages/' . $package['id'] . '/questions')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function delete($id)
    {
        $found = $this->findOwnedQuestion($id);

        if (!$found) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Soal tidak ditemukan atau bukan milik anda.');
        }

        $question = $found['question'];
        $package = $found['package'];

        // FIX: hapus file gambar terkait sebelum menghapus record
        if (!empty($question['image'])) {
            $path = FCPATH . 'uploads/questions/' . $question['image'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->questionModel->delete($id);

      return redirect()->to('/' . $this->getBase() . '/packages/' . $package['id'] . '/questions')
    ->with('success', 'Soal berhasil dihapus.');
    }

    // ===================== IMPORT EXCEL =====================

    public function importForm($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $base = session()->get('role_slug') === 'admin'
            ? 'admin'
            : 'guru';

        return view('guru/questions/import', [
            'title' => 'Import Soal dari Excel',
            'package' => $package,
            'packageId' => $packageId,
            'base' => $base,
        ]);
    }

    public function import($packageId)
    {
        $package = $this->findOwnedPackage($packageId);

        if (!$package) {
            return redirect()->to('/' . $this->getBase() . '/packages')->with('error', 'Paket tidak ditemukan atau bukan milik anda.');
        }

        $rules = [
            'excel_file' => 'uploaded[excel_file]|max_size[excel_file,5120]|ext_in[excel_file,xlsx,xls,csv]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('excel_file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid: ' . $file->getErrorString());
        }

        // FIX: cek ketersediaan PhpSpreadsheet sebelum dipakai agar tidak fatal error "Class not found"
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            return redirect()->back()->with('error', 'Library PhpSpreadsheet belum terinstall. Jalankan: composer require phpoffice/phpspreadsheet');
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $imported = 0;
            $skipped = 0;

            foreach ($rows as $i => $row) {
                // FIX: skip baris header (baris pertama)
                if ($i === 1) {
                    continue;
                }

                // Format kolom:
                // A: question_text | B-F: option A-E | G: correct_answer (A-E) | H: points
                $questionText = trim((string) ($row['A'] ?? ''));

                if ($questionText === '') {
                    $skipped++;
                    continue;
                }

                $optA = trim((string) ($row['B'] ?? ''));
                $optB = trim((string) ($row['C'] ?? ''));
                $optC = trim((string) ($row['D'] ?? ''));
                $optD = trim((string) ($row['E'] ?? ''));
                $optE = trim((string) ($row['F'] ?? ''));
                $correct = strtoupper(trim((string) ($row['G'] ?? 'A')));
                $points = (int) ($row['H'] ?? 1);

                if (!in_array($correct, ['A', 'B', 'C', 'D', 'E'], true)) {
                    $correct = 'A';
                }

                if ($optA === '' || $optB === '' || $optC === '' || $optD === '' || $optE === '') {
                    $skipped++;
                    continue;
                }

                $questionId = $this->questionModel->insert([
                    'package_id' => $packageId,
                    'question_text' => $questionText,
                    'points' => $points > 0 ? $points : 1,
                ]);

                $optionsMap = ['A' => $optA, 'B' => $optB, 'C' => $optC, 'D' => $optD, 'E' => $optE];

                foreach ($optionsMap as $label => $text) {
                    $this->optionModel->insert([
                        'question_id' => $questionId,
                        'option_label' => $label,
                        'option_text' => $text,
                        'is_correct' => $label === $correct ? 1 : 0,
                    ]);
                }

                $imported++;
            }

            $message = "Berhasil import {$imported} soal.";
            if ($skipped > 0) {
                $message .= " {$skipped} baris dilewati karena data tidak lengkap.";
            }

            return redirect()->to('/' . $this->getBase() . '/packages/' . $packageId . '/questions')
    ->with('success', $message);

        } catch (\Throwable $e) {
            // FIX: tangkap semua error parsing file agar tidak menampilkan error 500 mentah ke user
            return redirect()->back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
