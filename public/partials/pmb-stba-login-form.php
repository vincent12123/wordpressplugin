<div class="pmb-stba-login-container" style="min-height: 100vh; display: flex; align-items: center; background: #f7f7f7;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card floating-card border-0">
                    <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 0.5rem 0.5rem 0 0;">
                        <h2 class="mb-0"><?php _e('Login PMB STBA', 'pmb-stba'); ?></h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_GET['login']) && $_GET['login'] == 'failed') : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php _e('Username atau password salah.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form id="pmb-login-form" method="post">
                            <?php wp_nonce_field('pmb_login_nonce', 'pmb_nonce'); ?>

                            <div class="form-group mb-3">
                                <label for="username"><?php _e('Username', 'pmb-stba'); ?></label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="<?php esc_attr_e('Username', 'pmb-stba'); ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password"><?php _e('Password', 'pmb-stba'); ?></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="<?php esc_attr_e('Password', 'pmb-stba'); ?>" required>
                            </div>
                            
                            <?php
                            // Check if there's a redirect parameter
                            $redirect_to = isset($_GET['redirect_to']) ? esc_url($_GET['redirect_to']) : '';
                            if (!empty($redirect_to)) {
                                echo '<input type="hidden" name="redirect_to" value="' . esc_attr($redirect_to) . '">';
                            }
                            ?>
                            
                            <div class="form-group">
                                <button type="submit" name="pmb_login" class="btn btn-primary btn-block"><?php _e('Login', 'pmb-stba'); ?></button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p class="mb-0">
                                    <?php _e('Belum punya akun?', 'pmb-stba'); ?> 
                                    <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_registration_page')); ?>"><?php _e('Daftar', 'pmb-stba'); ?></a>
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-light py-3" style="border-radius: 0 0 0.5rem 0.5rem;">
                        <small><?php _e('© ' . date('Y') . ' PMB STBA. All rights reserved.', 'pmb-stba'); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>