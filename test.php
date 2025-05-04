<div class="pmb-stba-registration-container">
    <div class="row">
        <div class="col-md-3">
            <?php 
            // Debug sidebar ID
            $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
            echo '<!-- Debug: Sidebar ID is: ' . esc_html($sidebar_id) . ' -->';
            
            // Check if sidebar exists and is active
            if ($sidebar_id && is_active_sidebar($sidebar_id)) {
                dynamic_sidebar($sidebar_id);
            } else {
                echo '<!-- Debug: Sidebar is not active or does not exist -->';
                
                // Directly output the navigation menu as a fallback
                echo '<div class="pmb-nav-widget">';
                echo '<h4 class="pmb-nav-title">' . esc_html__('Menu PMB', 'pmb-stba') . '</h4>';
                echo '<ul class="pmb-navigation-menu">';
                
                // Dashboard
                $dashboard_page = carbon_get_theme_option('pmb_home_page');
                if (!empty($dashboard_page)) {
                    echo '<li><a href="' . esc_url(get_permalink($dashboard_page)) . '">' . 
                        '<span class="dashicons dashicons-dashboard"></span> ' .
                        esc_html__('Dashboard', 'pmb-stba') . '</a></li>';
                }
                
                // Information
                echo '<li><a href="#">' . 
                    '<span class="dashicons dashicons-info"></span> ' .
                    esc_html__('Informasi', 'pmb-stba') . '</a></li>';
                
                // Registration form
                $registration_form = carbon_get_theme_option('pmb_registration_page');
                if (!empty($registration_form)) {
                    echo '<li><a href="' . esc_url(get_permalink($registration_form)) . '">' . 
                        '<span class="dashicons dashicons-clipboard"></span> ' .
                        esc_html__('Formulir Pendaftaran', 'pmb-stba') . '</a></li>';
                }
                
                // Upload
                $profile_page = carbon_get_theme_option('pmb_profile_page');
                if (!empty($profile_page)) {
                    echo '<li><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                        '<span class="dashicons dashicons-upload"></span> ' .
                        esc_html__('Upload Dokumen', 'pmb-stba') . '</a></li>';
                }
                
                // Payment
                echo '<li><a href="#">' . 
                    '<span class="dashicons dashicons-money-alt"></span> ' .
                    esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                
                // Logout
                echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
                    '<span class="dashicons dashicons-exit"></span> ' .
                    esc_html__('Keluar', 'pmb-stba') . '</a></li>';

                echo '</ul>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php _e('Formulir Pendaftaran Mahasiswa Baru STBA', 'pmb-stba'); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (carbon_get_theme_option('pmb_status') === 'open') : ?>
                    
                    <form id="pmb-registration-form" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('pmb_registration_nonce', 'pmb_nonce'); ?>
                        
                        <!-- 1. Pilihan -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Pilihan', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nomor_pendaftaran"><?php _e('Nomor Pendaftaran', 'pmb-stba'); ?></label>
                                    <input type="text" id="nomor_pendaftaran" class="form-control" value="<?php echo esc_attr($this->generate_registration_number()); ?>" readonly>
                                    <input type="hidden" name="nomor_pendaftaran" value="<?php echo esc_attr($this->generate_registration_number()); ?>">
                                    <small class="form-text text-muted"><?php _e('Nomor pendaftaran otomatis dari sistem', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="jurusan_dipilih"><?php _e('Jurusan / Program Studi', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jurusan_dipilih" id="jurusan_dipilih" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="s1-sastra-inggris"><?php _e('S1 Sastra Inggris', 'pmb-stba'); ?></option>
                                        <option value="d3-bahasa-inggris"><?php _e('D3 Bahasa Inggris', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="waktu_kuliah"><?php _e('Waktu Kuliah', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="waktu_kuliah" id="waktu_kuliah" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="pagi"><?php _e('Pagi', 'pmb-stba'); ?></option>
                                        <option value="sore"><?php _e('Sore', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2. Data Diri -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Data Diri', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap"><?php _e('Nama Lengkap (sesuai Akta Lahir)', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'nama_lengkap', true)); ?>" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="tempat_lahir"><?php _e('Tempat Lahir', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="tanggal_lahir"><?php _e('Tanggal Lahir', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="no_hp"><?php _e('Nomor HP', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="tel" name="no_hp" id="no_hp" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email"><?php _e('E-mail', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="jenis_kelamin"><?php _e('Jenis Kelamin', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="L"><?php _e('Laki-laki', 'pmb-stba'); ?></option>
                                        <option value="P"><?php _e('Perempuan', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="agama"><?php _e('Agama', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="agama" id="agama" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Islam"><?php _e('Islam', 'pmb-stba'); ?></option>
                                        <option value="Kristen"><?php _e('Kristen', 'pmb-stba'); ?></option>
                                        <option value="Katolik"><?php _e('Katolik', 'pmb-stba'); ?></option>
                                        <option value="Hindu"><?php _e('Hindu', 'pmb-stba'); ?></option>
                                        <option value="Budha"><?php _e('Budha', 'pmb-stba'); ?></option>
                                        <option value="Konghucu"><?php _e('Konghucu', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="alamat"><?php _e('Alamat (di Pontianak)', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 3. Sekolah Asal -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Sekolah Asal', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jenis_sekolah"><?php _e('Jenis Sekolah Asal', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jenis_sekolah" id="jenis_sekolah" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="SMA"><?php _e('SMA', 'pmb-stba'); ?></option>
                                        <option value="SMK"><?php _e('SMK', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="asal_sekolah"><?php _e('Nama Sekolah Asal', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_lulus"><?php _e('Tahun Lulus', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="tahun_lulus" id="tahun_lulus" class="form-control" pattern="[0-9]{4}" maxlength="4" required>
                                    <small class="form-text text-muted"><?php _e('Format: YYYY (contoh: 2023)', 'pmb-stba'); ?></small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="status_mahasiswa"><?php _e('Status Mahasiswa', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="status_mahasiswa" id="status_mahasiswa" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Reguler"><?php _e('Reguler', 'pmb-stba'); ?></option>
                                        <option value="Transfer"><?php _e('Transfer', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 4. Status Pekerjaan -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Status Pekerjaan', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_pekerjaan"><?php _e('Status Pekerjaan', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="status_pekerjaan" id="status_pekerjaan" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Sudah Bekerja"><?php _e('Sudah Bekerja', 'pmb-stba'); ?></option>
                                        <option value="Belum Bekerja"><?php _e('Belum Bekerja', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 5. Rekomendasi -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Rekomendasi', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sumber"><?php _e('Sumber', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="sumber" id="sumber" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Expo"><?php _e('Expo', 'pmb-stba'); ?></option>
                                        <option value="Teman Keluarga"><?php _e('Teman Keluarga', 'pmb-stba'); ?></option>
                                        <option value="Sosial Media"><?php _e('Sosial Media', 'pmb-stba'); ?></option>
                                        <option value="Guru"><?php _e('Guru', 'pmb-stba'); ?></option>
                                        <option value="Kepala Sekolah"><?php _e('Kepala Sekolah', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="keterangan1"><?php _e('Keterangan 1', 'pmb-stba'); ?></label>
                                    <input type="text" name="keterangan1" id="keterangan1" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="keterangan2"><?php _e('Keterangan 2', 'pmb-stba'); ?></label>
                                    <input type="text" name="keterangan2" id="keterangan2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_pengisian"><?php _e('Tanggal Pengisian', 'pmb-stba'); ?></label>
                                    <input type="text" id="tanggal_pengisian" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                                    <input type="hidden" name="tanggal_pengisian" value="<?php echo date('Y-m-d'); ?>">
                                    <small class="form-text text-muted"><?php _e('Tanggal pengisian otomatis', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 6. Upload File -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Upload File', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="foto"><?php _e('Pas Foto', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="file" name="foto" id="foto" class="form-control" required>
                                    <small class="form-text text-muted"><?php _e('Format: JPG/PNG, Maks: 2MB', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ijazah"><?php _e('Ijazah', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="file" name="ijazah" id="ijazah" class="form-control" required>
                                    <small class="form-text text-muted"><?php _e('Format: PDF/JPG, Maks: 5MB', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" name="pmb_register" class="btn btn-primary w-100"><?php _e('Kirim Pendaftaran', 'pmb-stba'); ?></button>
                        </div>
                    </form>
                    
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <p><?php _e('Pendaftaran saat ini ditutup.', 'pmb-stba'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>