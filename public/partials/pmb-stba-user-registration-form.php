<div class="pmb-stba-user-registration-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php _e('Buat Akun PMB STBA', 'pmb-stba'); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['registration'])) : ?>
                        <?php if ($_GET['registration'] == 'email_exists') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Email sudah terdaftar.', 'pmb-stba'); ?>
                            </div>
                        <?php elseif ($_GET['registration'] == 'username_exists') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Username sudah digunakan.', 'pmb-stba'); ?>
                            </div>
                        <?php elseif ($_GET['registration'] == 'failed') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Registrasi gagal. Silakan coba lagi.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <form id="pmb-user-registration-form" method="post">
                        <?php wp_nonce_field('pmb_user_registration_nonce', 'pmb_nonce'); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap"><?php _e('Nama Lengkap', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="username"><?php _e('Username', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email"><?php _e('Email', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password"><?php _e('Password', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="pmb_user_register" class="btn btn-primary w-100"><?php _e('Daftar Akun', 'pmb-stba'); ?></button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p><?php _e('Sudah punya akun?', 'pmb-stba'); ?> <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_login_page')); ?>"><?php _e('Login', 'pmb-stba'); ?></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>