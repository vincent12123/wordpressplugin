<?php
// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">';
    echo __('Anda harus login terlebih dahulu untuk melihat informasi pembayaran.', 'pmb-stba');
    echo ' <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">' . __('Login', 'pmb-stba') . '</a>';
    echo '</div>';
    return;
}

// Get current user data
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$payment_enabled = carbon_get_theme_option('pmb_payment_enabled') === 'yes';
$payment_title = carbon_get_theme_option('pmb_payment_title');
$payment_description = carbon_get_theme_option('pmb_payment_description');
$payment_amount = carbon_get_theme_option('pmb_payment_amount');
$bank_accounts = carbon_get_theme_option('pmb_bank_accounts');

// Check if payment is enabled
if (!$payment_enabled) {
    echo '<div class="alert alert-info">';
    echo __('Informasi pembayaran belum tersedia.', 'pmb-stba');
    echo '</div>';
    return;
}
?>

<div class="pmb-stba-payment-container bg-light py-4">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation - Left Column -->
            <div class="col-md-3">
                <?php 
                // Display sidebar if it exists
                $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
                
                // Check if sidebar exists and is active
                if ($sidebar_id && is_active_sidebar($sidebar_id)) {
                    dynamic_sidebar($sidebar_id);
                } else {
                    // Fallback navigation menu
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
                    
                    // Profile
                    $profile_page = carbon_get_theme_option('pmb_profile_page');
                    if (!empty($profile_page)) {
                        echo '<li><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                            '<span class="dashicons dashicons-id"></span> ' .
                            esc_html__('Profil PMB', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Payment - active
                    $payment_page = carbon_get_theme_option('pmb_payment_page');
                    if (!empty($payment_page)) {
                        echo '<li class="active"><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
                            '<span class="dashicons dashicons-money-alt"></span> ' .
                            esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Logout
                    echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
                        '<span class="dashicons dashicons-exit"></span> ' .
                        esc_html__('Keluar', 'pmb-stba') . '</a></li>';

                    echo '</ul>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <!-- Main Content - Right Column -->
            <div class="col-md-9">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-money-alt mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo !empty($payment_title) ? esc_html($payment_title) : __('Informasi Pembayaran', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($payment_description)) : ?>
                            <div class="alert alert-info mb-4">
                                <?php echo wpautop(esc_html($payment_description)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="border-bottom pb-2 mb-3">
                            <?php _e('Nominal Pembayaran', 'pmb-stba'); ?>
                        </h5>
                        <div class="mb-4">
                            <h2 class="text-primary">Rp <?php echo number_format(!empty($payment_amount) ? (int) $payment_amount : 0, 0, ',', '.'); ?></h2>
                        </div>
                        
                        <?php if (!empty($bank_accounts)) : ?>
                            <h5 class="border-bottom pb-2 mb-3">
                                <?php _e('Metode Pembayaran', 'pmb-stba'); ?>
                            </h5>
                            
                            <div class="row">
                                <?php foreach ($bank_accounts as $bank) : ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <?php if (!empty($bank['bank_logo'])) : ?>
                                                        <div class="mr-3">
                                                            <img src="<?php echo esc_url($bank['bank_logo']); ?>" alt="<?php echo esc_attr($bank['bank_name']); ?>" class="bank-logo" style="max-height: 40px; max-width: 80px;">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h5 class="mb-0"><?php echo esc_html($bank['bank_name']); ?></h5>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <small class="text-muted d-block"><?php _e('Nomor Rekening:', 'pmb-stba'); ?></small>
                                                    <h5 class="font-weight-bold mb-0"><?php echo esc_html($bank['account_number']); ?></h5>
                                                </div>
                                                
                                                <div>
                                                    <small class="text-muted d-block"><?php _e('Atas Nama:', 'pmb-stba'); ?></small>
                                                    <p class="font-weight-bold mb-0"><?php echo esc_html($bank['account_name']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="alert alert-warning mt-3">
                                <h6 class="mb-2"><i class="dashicons dashicons-info mr-2"></i> <?php _e('Petunjuk Pembayaran:', 'pmb-stba'); ?></h6>
                                <ol class="mb-0 pl-3">
                                    <li><?php _e('Transfer sesuai nominal yang tertera.', 'pmb-stba'); ?></li>
                                    <li><?php _e('Simpan bukti pembayaran.', 'pmb-stba'); ?></li>
                                    <li><?php _e('Upload bukti pembayaran melalui tombol di bawah.', 'pmb-stba'); ?></li>
                                </ol>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="#upload-payment" class="btn btn-primary btn-lg" data-toggle="modal">
                                    <i class="dashicons dashicons-upload"></i> 
                                    <?php _e('Upload Bukti Pembayaran', 'pmb-stba'); ?>
                                </a>
                            </div>
                            
                            <!-- Modal for uploading payment proof -->
                            <div class="modal fade" id="upload-payment" tabindex="-1" role="dialog" aria-labelledby="uploadPaymentLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadPaymentLabel"><?php _e('Upload Bukti Pembayaran', 'pmb-stba'); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="payment-proof-form" method="post" enctype="multipart/form-data">
                                                <?php wp_nonce_field('pmb_payment_proof_nonce', 'payment_proof_nonce'); ?>
                                                
                                                <div class="form-group">
                                                    <label for="payment_bank"><?php _e('Bank Tujuan', 'pmb-stba'); ?></label>
                                                    <select class="form-control" id="payment_bank" name="payment_bank" required>
                                                        <option value=""><?php _e('-- Pilih Bank --', 'pmb-stba'); ?></option>
                                                        <?php foreach ($bank_accounts as $bank) : ?>
                                                            <option value="<?php echo esc_attr($bank['bank_name']); ?>"><?php echo esc_html($bank['bank_name']); ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="payment_date"><?php _e('Tanggal Pembayaran', 'pmb-stba'); ?></label>
                                                    <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="payment_amount"><?php _e('Jumlah Pembayaran (Rp)', 'pmb-stba'); ?></label>
                                                    <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="payment_proof"><?php _e('Bukti Pembayaran', 'pmb-stba'); ?></label>
                                                    <input type="file" class="form-control-file" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required>
                                                    <small class="form-text text-muted"><?php _e('Upload file gambar atau PDF (Maks. 2MB)', 'pmb-stba'); ?></small>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="payment_notes"><?php _e('Catatan (Opsional)', 'pmb-stba'); ?></label>
                                                    <textarea class="form-control" id="payment_notes" name="payment_notes" rows="3"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Batal', 'pmb-stba'); ?></button>
                                            <button type="submit" form="payment-proof-form" class="btn btn-primary"><?php _e('Upload', 'pmb-stba'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-warning">
                                <?php _e('Informasi rekening bank belum tersedia. Silakan hubungi admin untuk informasi lebih lanjut.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add styles for bank logo and payment info -->
<style>
.bank-logo {
    object-fit: contain;
}
.dashicons {
    vertical-align: middle;
}
</style>