<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Ensure only admins can access
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Handle form submission manually for non-Carbon fields
if (isset($_POST['submit_payment_settings']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'pmb_payment_settings_nonce')) {
    // Process any additional settings if needed
}

$payment_enabled = carbon_get_theme_option('pmb_payment_enabled');
?>

<div class="wrap">
    <h1><?php _e('Pengaturan Pembayaran PMB', 'pmb-stba'); ?></h1>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><?php _e('Informasi Pembayaran', 'pmb-stba'); ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('pmb_payment_settings');
                            do_settings_sections('pmb_payment_settings');
                            ?>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php _e('Aktifkan Pembayaran', 'pmb-stba'); ?></label>
                                <div class="col-sm-9">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="pmb_payment_enabled" name="pmb_payment_enabled" value="1" 
                                            <?php checked(carbon_get_theme_option('pmb_payment_enabled'), 'yes'); ?>>
                                        <label class="form-check-label" for="pmb_payment_enabled">
                                            <?php _e('Ya, aktifkan fitur pembayaran', 'pmb-stba'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5><?php _e('Daftar Rekening Bank', 'pmb-stba'); ?></h5>
                            <p class="text-muted"><?php _e('Tambahkan informasi bank untuk pembayaran pendaftaran.', 'pmb-stba'); ?></p>
                            
                            <div class="bank-accounts-container">
                                <?php
                                // Carbon Fields creates the complex repeater fields for bank accounts
                                // This is handled in the carbon-fields-init.php file
                                ?>
                            </div>
                            
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php _e('Judul Halaman Pembayaran', 'pmb-stba'); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="pmb_payment_title" name="pmb_payment_title" 
                                        value="<?php echo esc_attr(carbon_get_theme_option('pmb_payment_title')); ?>" 
                                        placeholder="<?php _e('contoh: Pembayaran Pendaftaran PMB', 'pmb-stba'); ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php _e('Deskripsi', 'pmb-stba'); ?></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="pmb_payment_description" name="pmb_payment_description" rows="4"
                                        placeholder="<?php _e('Informasi tambahan tentang pembayaran...', 'pmb-stba'); ?>"><?php 
                                        echo esc_textarea(carbon_get_theme_option('pmb_payment_description')); 
                                    ?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><?php _e('Nominal Pembayaran', 'pmb-stba'); ?></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="pmb_payment_amount" name="pmb_payment_amount" 
                                            value="<?php echo esc_attr(carbon_get_theme_option('pmb_payment_amount')); ?>" 
                                            placeholder="<?php _e('contoh: 250000', 'pmb-stba'); ?>">
                                    </div>
                                    <small class="form-text text-muted"><?php _e('Masukkan nominal tanpa titik atau koma', 'pmb-stba'); ?></small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <?php submit_button(__('Simpan Pengaturan', 'pmb-stba'), 'primary', 'submit_payment_settings'); ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>