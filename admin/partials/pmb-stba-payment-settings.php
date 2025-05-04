<?php
// Ensure only admins can access
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Handle form submission
if (isset($_POST['save_payment_info'])) {
    // Validate nonce for security
    if (isset($_POST['pmb_payment_nonce']) && wp_verify_nonce($_POST['pmb_payment_nonce'], 'pmb_payment_action')) {
        
        // Update options
        update_option('pmb_payment_title', sanitize_text_field($_POST['pmb_payment_title']));
        update_option('pmb_payment_description', wp_kses_post($_POST['pmb_payment_description']));
        update_option('pmb_payment_amount', sanitize_text_field($_POST['pmb_payment_amount']));
        update_option('pmb_payment_page', intval($_POST['pmb_payment_page']));
        
        // Bank accounts
        $bank_accounts = array();
        if (isset($_POST['bank_name']) && is_array($_POST['bank_name'])) {
            for ($i = 0; $i < count($_POST['bank_name']); $i++) {
                if (!empty($_POST['bank_name'][$i])) {
                    $bank_accounts[] = array(
                        'bank_name' => sanitize_text_field($_POST['bank_name'][$i]),
                        'account_number' => sanitize_text_field($_POST['account_number'][$i]),
                        'account_name' => sanitize_text_field($_POST['account_name'][$i]),
                    );
                }
            }
        }
        update_option('pmb_bank_accounts', $bank_accounts);
        
        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Pengaturan pembayaran berhasil disimpan.', 'pmb-stba') . '</p></div>';
    }
}

// Get saved options
$payment_title = get_option('pmb_payment_title', 'Informasi Pembayaran PMB');
$payment_description = get_option('pmb_payment_description', '');
$payment_amount = get_option('pmb_payment_amount', '250000');
$bank_accounts = get_option('pmb_bank_accounts', array());

// Add an empty bank if none exists
if (empty($bank_accounts)) {
    $bank_accounts[] = array(
        'bank_name' => '',
        'account_number' => '',
        'account_name' => '',
    );
}

// Add this to your admin settings page

?>

<div class="wrap">
    <h1><?php _e('Pengaturan Informasi Pembayaran', 'pmb-stba'); ?></h1>
    
    <div class="card">
        <div class="card-header">
            <h2><?php _e('Informasi Rekening Bank', 'pmb-stba'); ?></h2>
            <p><?php _e('Atur informasi rekening bank untuk ditampilkan kepada pendaftar.', 'pmb-stba'); ?></p>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <?php wp_nonce_field('pmb_payment_action', 'pmb_payment_nonce'); ?>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_title"><?php _e('Judul Halaman', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <input name="pmb_payment_title" type="text" id="pmb_payment_title" 
                                       value="<?php echo esc_attr($payment_title); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_description"><?php _e('Deskripsi', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <textarea name="pmb_payment_description" id="pmb_payment_description" 
                                          class="large-text" rows="5"><?php echo esc_textarea($payment_description); ?></textarea>
                                <p class="description">
                                    <?php _e('Berikan petunjuk atau informasi tambahan tentang pembayaran.', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_amount"><?php _e('Nominal Pembayaran', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 5px;">Rp</span>
                                    <input name="pmb_payment_amount" type="text" id="pmb_payment_amount" 
                                           value="<?php echo esc_attr($payment_amount); ?>" class="regular-text">
                                </div>
                                <p class="description">
                                    <?php _e('Contoh: 250000 (tanpa titik atau koma)', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_page"><?php _e('Halaman Pembayaran', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <?php
                                wp_dropdown_pages(array(
                                    'name' => 'pmb_payment_page',
                                    'show_option_none' => __('-- Pilih Halaman --', 'pmb-stba'),
                                    'option_none_value' => '0',
                                    'selected' => get_option('pmb_payment_page'),
                                ));
                                ?>
                                <p class="description">
                                    <?php _e('Pilih halaman yang menampilkan shortcode [pmb_payment_info]', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <h3><?php _e('Daftar Rekening Bank', 'pmb-stba'); ?></h3>
                <div id="bank-accounts-container">
                    <?php foreach ($bank_accounts as $index => $bank) : ?>
                    <div class="bank-account-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nama Bank', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="bank_name[]" value="<?php echo esc_attr($bank['bank_name']); ?>" 
                                   class="regular-text" placeholder="contoh: Bank BCA">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nomor Rekening', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="account_number[]" value="<?php echo esc_attr($bank['account_number']); ?>" 
                                   class="regular-text" placeholder="contoh: 1234-5678-90">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nama Pemilik', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="account_name[]" value="<?php echo esc_attr($bank['account_name']); ?>" 
                                   class="regular-text" placeholder="contoh: STBA Malang">
                        </div>
                        <?php if ($index > 0) : ?>
                        <button type="button" class="button remove-bank" style="margin-top: 10px; color: #a00;">
                            <?php _e('Hapus Bank Ini', 'pmb-stba'); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" id="add-bank" class="button button-secondary" style="margin-bottom: 20px;">
                    <?php _e('+ Tambah Bank Lain', 'pmb-stba'); ?>
                </button>
                
                <p class="submit">
                    <input type="submit" name="save_payment_info" id="submit" class="button button-primary" 
                           value="<?php _e('Simpan Pengaturan', 'pmb-stba'); ?>">
                </p>
            </form>
        </div>
    </div>
</div>

<script>
(function($) {
    // Add new bank
    $('#add-bank').on('click', function() {
        const bankRow = `
            <div class="bank-account-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nama Bank', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="bank_name[]" value="" class="regular-text" placeholder="contoh: Bank BCA">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nomor Rekening', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="account_number[]" value="" class="regular-text" placeholder="contoh: 1234-5678-90">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nama Pemilik', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="account_name[]" value="" class="regular-text" placeholder="contoh: STBA Malang">
                </div>
                <button type="button" class="button remove-bank" style="margin-top: 10px; color: #a00;">
                    <?php _e('Hapus Bank Ini', 'pmb-stba'); ?>
                </button>
            </div>
        `;
        $('#bank-accounts-container').append(bankRow);
    });
    
    // Remove bank
    $(document).on('click', '.remove-bank', function() {
        $(this).closest('.bank-account-row').remove();
    });
})(jQuery);
</script>