<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Pengguna</h5>
    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pengguna</a>
</div>

<div class="card">
    <div class="card-body">
        <table id="usersTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user) : ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($user['name']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><span class="badge bg-secondary"><?= esc($user['role_name']) ?></span></td>
                        <td>
                            <?php if ((int) $user['is_active'] === 1) : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/users/delete/' . $user['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#usersTable').DataTable();

        $('.btn-delete').on('click', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>