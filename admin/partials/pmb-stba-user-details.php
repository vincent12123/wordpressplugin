<?php
// Check user capabilities and get user ID
if (!current_user_can('manage_options') || empty($_GET['user_id'])) {
    wp_die(__('Anda tidak memiliki izin untuk mengakses halaman ini.', 'pmb-stba'));
}

$user_id = intval($_GET['user_id']);
$user = get_userdata($user_id);

if (!$user) {
    wp_die(__('User tidak ditemukan.', 'pmb-stba'));
}

// Get user meta
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
$jenis_kelamin = get_user_meta($user_id, 'jenis_kelamin', true);
$no_hp = get_user_meta($user_id, 'no_hp', true);
$alamat = get_user_meta($user_id, 'alamat', true);
$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
$jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
$status = get_user_meta($user_id, 'status_pmb', true);
$nomor_pendaftaran = get_user_meta($user_id, 'nomor_pendaftaran', true);
$waktu_kuliah = get_user_meta($user_id, 'waktu_kuliah', true);
$jenis_sekolah = get_user_meta($user_id, 'jenis_sekolah', true);
$tahun_lulus = get_user_meta($user_id, 'tahun_lulus', true);
$status_mahasiswa = get_user_meta($user_id, 'status_mahasiswa', true);
$status_pekerjaan = get_user_meta($user_id, 'status_pekerjaan', true);
$sumber = get_user_meta($user_id, 'sumber', true);
$keterangan1 = get_user_meta($user_id, 'keterangan1', true);
$keterangan2 = get_user_meta($user_id, 'keterangan2', true);
$tanggal_pengisian = get_user_meta($user_id, 'tanggal_pengisian', true);

// Get file paths - remove duplicate variables
$foto_path = get_user_meta($user_id, 'foto_path', true);
$ijazah_path = get_user_meta($user_id, 'ijazah_path', true);

// Format jenis kelamin
$jenis_kelamin = $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

// Status badge
switch ($status) {
    case 'approved':
        $status_badge = '<span class="badge bg-success">Diterima</span>';
        break;
    case 'rejected':
        $status_badge = '<span class="badge bg-danger">Ditolak</span>';
        break;
    default:
        $status_badge = '<span class="badge bg-warning">Menunggu</span>';
}

// Tambahkan data lain yang mungkin diperlukan
$nilai_un = get_user_meta($user_id, 'nilai_un', true);
$status_history = get_user_meta($user_id, 'status_history', true);
if (!is_array($status_history)) {
    $status_history = array();
}
?>

<style>
.container-fluid {
    margin: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

.card {
    margin: 0 0 20px 0 !important;
    width: 100% !important;
    border-radius: 0.25rem !important;
}

/* Menghapus padding berlebih */
.card-body {
    padding: 15px !important;
}

/* Perbaikan khusus untuk row dengan kolom */
.row {
    margin-left: 0 !important;
    margin-right: 0 !important;
    width: 100% !important;
}

/* Pastikan tabel responsive juga full width */
.table-responsive {
    width: 100% !important;
    overflow-x: auto !important;
}

/* Pastikan preview kartu juga terlihat baik */
.card.mb-0 {
    margin-bottom: 0 !important;
}
</style>

<div class="wrap">
    <div class="container-fluid" style="margin-left:0px; margin-right:0px; margin-top:0px; margin-bottom:0px; padding-left:0px !important; padding-right:0px !important; padding-top:0px; padding-bottom:0px; width:100%;">
        <h1 class="h3 mb-4">
            <a href="<?php echo admin_url('admin.php?page=pmb-stba-registrations'); ?>" class="text-decoration-none">
                <span class="dashicons dashicons-arrow-left-alt"></span>
            </a>
            Detail Pendaftar
            
            <!-- Tambah tombol cetak -->
            <div class="float-end">
                <a href="<?php echo admin_url('admin-post.php?action=pmb_print_card&user_id=' . $user_id); ?>" target="_blank" class="btn btn-info btn-sm">
                    <i class="dashicons dashicons-printer" style="vertical-align: middle;"></i> Cetak Kartu PMB
                </a>
            </div>
        </h1>
        
        <!-- Status berkas - indikator visual - UPDATED -->
        <div class="alert <?php echo (empty($foto_path) || empty($ijazah_path)) ? 'alert-warning' : 'alert-success'; ?>" style="width:100%; margin-left:0; margin-right:0; margin-top:0; margin-bottom:20px;">
            <h5 class="alert-heading">Status Berkas</h5>
            <div class="progress mb-3">
                <?php
                $completed = 0;
                if (!empty($nama_lengkap)) $completed++;
                if (!empty($tempat_lahir) && !empty($tanggal_lahir)) $completed++;
                if (!empty($alamat)) $completed++;
                if (!empty($asal_sekolah)) $completed++;
                if (!empty($foto_path)) $completed++;
                if (!empty($ijazah_path)) $completed++;
                
                $percentage = ($completed / 6) * 100;
                ?>
                <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                    aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                    <?php echo round($percentage); ?>%
                </div>
            </div>
            <ul class="list-unstyled mb-0">
                <li><i class="dashicons <?php echo !empty($nama_lengkap) ? 'dashicons-yes' : 'dashicons-no'; ?>"></i> Data Pribadi</li>
                <li><i class="dashicons <?php echo !empty($foto_path) ? 'dashicons-yes' : 'dashicons-no'; ?>"></i> Foto</li>
                <li><i class="dashicons <?php echo !empty($ijazah_path) ? 'dashicons-yes' : 'dashicons-no'; ?>"></i> Ijazah</li>
            </ul>
        </div>
        
        <div class="row" style="width:100%; margin-left:0; margin-right:0;">
            <div class="col-md-8">
                <!-- Ganti class card dengan styling inline -->
                <div style="width:100%; margin-left:0; margin-right:0; margin-top:0; margin-bottom:20px; border-radius:0.25rem; border:1px solid rgba(0,0,0,.125);">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Data Pendaftar</h5>
                    </div>
                    <div class="card-body">
                        <!-- Data Pendaftar content with updated fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama Lengkap:</strong> <?php echo esc_html($nama_lengkap); ?></p>
                                <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
                                <p><strong>Tempat, Tanggal Lahir:</strong> <?php echo esc_html($tempat_lahir . ', ' . $tanggal_lahir); ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?php echo esc_html($jenis_kelamin); ?></p>
                                <p><strong>Agama:</strong> <?php echo esc_html(get_user_meta($user_id, 'agama', true)); ?></p>
                                <p><strong>No. HP:</strong> <?php echo esc_html($no_hp); ?></p>
                                <p><strong>Alamat:</strong> <?php echo esc_html($alamat); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nomor Pendaftaran:</strong> <?php echo esc_html(get_user_meta($user_id, 'nomor_pendaftaran', true)); ?></p>
                                <p><strong>Jurusan Dipilih:</strong> 
                                    <?php 
                                    $jurusan_raw = get_user_meta($user_id, 'jurusan_dipilih', true);
                                    $jurusan_display = '';
                                    switch ($jurusan_raw) {
                                        case 's1-sastra-inggris':
                                            $jurusan_display = 'S1 Sastra Inggris';
                                            break;
                                        case 'd3-bahasa-inggris':
                                            $jurusan_display = 'D3 Bahasa Inggris';
                                            break;
                                        default:
                                            $jurusan_display = $jurusan_raw;
                                    }
                                    echo esc_html($jurusan_display); 
                                    ?>
                                </p>
                                <p><strong>Waktu Kuliah:</strong> <?php echo esc_html(ucfirst(get_user_meta($user_id, 'waktu_kuliah', true))); ?></p>
                                <p><strong>Jenis Sekolah:</strong> <?php echo esc_html(get_user_meta($user_id, 'jenis_sekolah', true)); ?></p>
                                <p><strong>Asal Sekolah:</strong> <?php echo esc_html($asal_sekolah); ?></p>
                                <p><strong>Tahun Lulus:</strong> <?php echo esc_html(get_user_meta($user_id, 'tahun_lulus', true)); ?></p>
                                <p><strong>Status Mahasiswa:</strong> <?php echo esc_html(get_user_meta($user_id, 'status_mahasiswa', true)); ?></p>
                            </div>
                        </div>
                        
                        <!-- Additional Information Section -->
                        <h6 class="border-bottom pb-2 mb-2">Informasi Tambahan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status Pekerjaan:</strong> <?php echo esc_html(get_user_meta($user_id, 'status_pekerjaan', true)); ?></p>
                                <p><strong>Sumber Informasi:</strong> <?php echo esc_html(get_user_meta($user_id, 'sumber', true)); ?></p>
                                <p><strong>Tanggal Pengisian:</strong> <?php echo esc_html(get_user_meta($user_id, 'tanggal_pengisian', true)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Keterangan 1:</strong> <?php echo esc_html(get_user_meta($user_id, 'keterangan1', true)); ?></p>
                                <p><strong>Keterangan 2:</strong> <?php echo esc_html(get_user_meta($user_id, 'keterangan2', true)); ?></p>
                                <p><strong>Status:</strong> <?php echo $status_badge; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Kelola Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="pmb_update_status">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <?php wp_nonce_field('pmb_update_status_nonce', 'pmb_status_nonce'); ?>
                            
                            <div class="form-group mb-3">
                                <label for="status">Status Pendaftaran</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu</option>
                                    <option value="approved" <?php selected($status, 'approved'); ?>>Diterima</option>
                                    <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="catatan">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3"><?php echo esc_textarea(get_user_meta($user_id, 'catatan_admin', true)); ?></textarea>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="send_notification" id="send_notification" value="1" checked>
                                <label class="form-check-label" for="send_notification">
                                    Kirim notifikasi email ke pendaftar
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
                
                <!-- Tambahkan riwayat perubahan status -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title m-0">Riwayat Status</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($status_history)): ?>
                            <p class="text-muted">Belum ada perubahan status.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Admin</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($status_history as $history): ?>
                                            <tr>
                                                <td><?php echo date_i18n('d M Y H:i', $history['timestamp']); ?></td>
                                                <td>
                                                    <?php 
                                                    $badge_class = '';
                                                    switch ($history['status']) {
                                                        case 'approved':
                                                            $badge_class = 'bg-success';
                                                            $status_text = 'Diterima';
                                                            break;
                                                        case 'rejected':
                                                            $badge_class = 'bg-danger';
                                                            $status_text = 'Ditolak';
                                                            break;
                                                        default:
                                                            $badge_class = 'bg-warning';
                                                            $status_text = 'Menunggu';
                                                    }
                                                    echo '<span class="badge ' . $badge_class . '">' . $status_text . '</span>';
                                                    ?>
                                                </td>
                                                <td><?php echo esc_html($history['admin_name']); ?></td>
                                                <td><?php echo esc_html($history['catatan']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Foto</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($foto_path)) : ?>
                            <img src="<?php echo esc_url($foto_path); ?>" alt="Foto Pendaftar" class="img-fluid mb-2" style="max-height: 200px;">
                            <a href="<?php echo esc_url($foto_path); ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-block">Lihat Foto</a>
                        <?php else : ?>
                            <div class="alert alert-warning">Foto belum diunggah</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Pas Foto</h6>
                            <?php if (!empty($foto_path)) : ?>
                                <img src="<?php echo esc_url($foto_path); ?>" alt="Foto Pendaftar" class="img-fluid mb-2" style="max-height: 200px;">
                                <a href="<?php echo esc_url($foto_path); ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-block">Lihat Foto</a>
                            <?php else : ?>
                                <div class="alert alert-warning">Foto belum diunggah</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <h6>Ijazah</h6>
                            <?php if (!empty($ijazah_path)) : ?>
                                <?php $file_ext = pathinfo($ijazah_path, PATHINFO_EXTENSION); ?>
                                <?php if (strtolower($file_ext) === 'pdf') : ?>
                                    <a href="<?php echo esc_url($ijazah_path); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <span class="dashicons dashicons-pdf"></span> Lihat PDF
                                    </a>
                                <?php else : ?>
                                    <img src="<?php echo esc_url($ijazah_path); ?>" alt="Ijazah" class="img-fluid mb-2" style="max-height: 200px;">
                                    <a href="<?php echo esc_url($ijazah_path); ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-block">Lihat Ijazah</a>
                                <?php endif; ?>
                            <?php else : ?>
                                <div class="alert alert-warning">Ijazah belum diunggah</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Pendaftaran Preview -->
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title m-0">Kartu Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="card mb-0" style="border: 1px dashed #ccc;">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <h5 class="mb-1">KARTU BUKTI PENDAFTARAN</h5>
                                    <h6>PMB STBA Pontianak <?php echo date('Y'); ?></h6>
                                </div>
                                
                                <div class="row">
                                    <div class="col-4">
                                        <?php if (!empty($foto_path)) : ?>
                                            <img src="<?php echo esc_url($foto_path); ?>" alt="Foto Pendaftar" class="img-fluid" style="max-height: 100px;">
                                        <?php else : ?>
                                            <div class="bg-secondary" style="height: 100px; width: 80px;"></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-8">
                                        <p class="mb-1"><small>Nama: <strong><?php echo esc_html($nama_lengkap); ?></strong></small></p>
                                        <p class="mb-1"><small>No. Pendaftaran: <strong><?php echo esc_html($nomor_pendaftaran); ?></strong></small></p>
                                        <p class="mb-1"><small>Jurusan: <strong><?php echo esc_html($jurusan_display); ?></strong></small></p>
                                        <p class="mb-0"><small>Status: <?php echo $status_badge; ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?php echo admin_url('admin-post.php?action=pmb_print_card&user_id=' . $user_id); ?>" target="_blank" class="btn btn-info btn-sm">
                                <i class="dashicons dashicons-printer" style="vertical-align: middle;"></i> Cetak
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Fungsi untuk menerapkan full width
    function applyFullWidth() {
        // Apply to container
        $('.container-fluid').css({
            'margin': '0',
            'padding-left': '0',
            'padding-right': '0',
            'max-width': '100%',
            'width': '100%'
        });
        
        // Apply to all cards
        $('.card').css({
            'width': '100%',
            'margin-left': '0',
            'margin-right': '0'
        });
        
        // Remove specific classes that limit width
        $('.table-responsive').css({
            'width': '100%',
            'overflow-x': 'auto'
        });
    }
    
    // Call function on page load
    applyFullWidth();
    
    // Also call it after a small delay to ensure it applies after any other scripts
    setTimeout(applyFullWidth, 100);
});
</script>