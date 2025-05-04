<?php
// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">';
    echo __('Anda harus login terlebih dahulu untuk melihat informasi pembayaran.', 'pmb-stba');
    echo ' <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">' . __('Login', 'pmb-stba') . '</a>';
    echo '</div>';
    return;
}

// Get payment info
$payment_title = get_option('pmb_payment_title', 'Informasi Pembayaran PMB');
$payment_description = get_option('pmb_payment_description', '');
$payment_amount = get_option('pmb_payment_amount', '0');
$bank_accounts = get_option('pmb_bank_accounts', array());

// Current user
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
?>

<div class="pmb-stba-profile-container bg-light py-4">
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
                    $payment_page = get_option('pmb_payment_page');
                    if (!empty($payment_page) && get_the_ID() == $payment_page) {
                        echo '<li class="active"><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
                            '<span class="dashicons dashicons-money-alt"></span> ' .
                            esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                    } else {
                        echo '<li><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
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
                            <h4 class="mb-0"><?php echo esc_html($payment_title); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Konten halaman pembayaran tetap sama -->
                        <?php if (!empty($payment_description)) : ?>
                            <div class="alert alert-info mb-4">
                                <?php echo wpautop(wp_kses_post($payment_description)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="border-bottom pb-2 mb-3">
                            <?php _e('Nominal Pembayaran', 'pmb-stba'); ?>
                        </h5>
                        <div class="mb-4">
                            <h2 class="text-primary">Rp <?php echo number_format(intval($payment_amount), 0, ',', '.'); ?></h2>
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
                                                <div class="mb-3">
                                                    <h5 class="mb-0"><?php echo esc_html($bank['bank_name']); ?></h5>
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
                                    <li><?php _e('Gunakan nama Anda sebagai keterangan transfer: ', 'pmb-stba'); ?><strong><?php echo esc_html($nama_lengkap); ?></strong></li>
                                    <li><?php _e('Simpan bukti pembayaran untuk verifikasi jika diperlukan.', 'pmb-stba'); ?></li>
                                </ol>
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

<!-- Tambahkan CSS yang sama dengan halaman profile -->
<style>
/* Custom styles for profile page */
.pmb-stba-profile-container .card {
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 1rem;
}

.pmb-stba-profile-container .card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.pmb-stba-profile-container .card-header {
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 0.75rem 1rem;
}

/* Navigation menu improvements */
.pmb-navigation-menu {
    position: sticky;
    top: 1rem;
}

.pmb-navigation-menu .list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 0;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.pmb-navigation-menu .list-group-item:first-child {
    border-top: none;
}

.pmb-navigation-menu .list-group-item:last-child {
    border-bottom: none;
}

.pmb-navigation-menu .list-group-item:hover {
    background-color: #f8f9fa;
}

.pmb-navigation-menu .list-group-item.active {
    background-color: #e9ecef;
    font-weight: bold;
}

.info-item small {
    font-size: 0.75rem;
}

.dashicons {
    line-height: 1.5;
    margin-right: 0.5rem;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mr-3 {
    margin-right: 1rem;
}

/* Container adjustments for better spacing */
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

/* Responsive adjustments - special attention to 1366x768 */
@media (max-width: 1366px) {
    .pmb-stba-profile-container .container {
        max-width: 100%;
        padding: 0 0.5rem;
    }
    
    .pmb-navigation-menu .list-group-item {
        padding: 0.6rem 0.75rem;
        font-size: 0.9rem;
    }
    
    .card-header h4, .card-header h5 {
        font-size: 1.1rem;
    }
    
    .pmb-stba-profile-container .row {
        margin-right: -0.5rem;
        margin-left: -0.5rem;
    }
    
    .pmb-stba-profile-container [class*="col-"] {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }
    
    .pmb-stba-profile-container .card {
        margin-bottom: 0.75rem;
    }
    
    .pmb-stba-profile-container .card-body {
        padding: 0.75rem;
    }
}

@media (max-width: 991.98px) {
    .pmb-navigation-menu {
        margin-bottom: 1rem;
        position: relative;
    }
    
    .col-lg-3, .col-md-3 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 767.98px) {
    .col-md-3, .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .info-item .d-flex {
        flex-direction: column;
    }
    
    .info-item .bg-light.p-2 {
        margin-bottom: 0.5rem;
    }
    
    .pmb-stba-profile-container {
        padding: 0.5rem !important;
    }
}

@media (max-width: 575.98px) {
    .pmb-stba-profile-container .card-body {
        padding: 0.75rem;
    }
    
    .d-flex.align-items-center {
        flex-wrap: wrap;
    }
    
    .bg-light.p-2.rounded-circle.mr-3 {
        margin-bottom: 0.5rem;
    }
    
    .card-header h4, .card-header h5 {
        font-size: 1rem;
    }
    
    .dashicons {
        font-size: 1.1rem !important;
    }
    
    .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Fix for dashboard icons alignment */
.dashicons {
    vertical-align: middle;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Specific adjustments for notification area */
.alert {
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

/* Make form notification more readable */
.pmb-stba-profile-container .alert-info {
    background-color: #e3f2fd;
    border-color: #b3e0ff;
}

/* Improve button appearance */
.btn-primary {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}

/* Fix for the blue header areas */
.card-header.bg-primary {
    color: white;
    font-weight: 500;
}

/* Fix excessive padding on small screens */
@media (max-width: 480px) {
    .pmb-stba-profile-container {
        padding: 0.25rem !important;
    }
    
    .container {
        padding-right: 8px;
        padding-left: 8px;
    }
    
    .card-body {
        padding: 0.625rem;
    }
    
    .mb-4 {
        margin-bottom: 0.75rem !important;
    }
    
    .py-3 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    h5.mb-0 {
        font-size: 0.95rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
}
</style>