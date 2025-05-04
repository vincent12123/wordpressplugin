<?php
/**
 * PMB Navigation Widget
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * PMB Navigation Widget
 */
class PMB_Navigation_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'pmb_navigation_widget', // Base ID
            __('PMB Navigation Menu', 'pmb-stba'), // Name
            array('description' => __('Menu navigasi untuk PMB STBA', 'pmb-stba')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Buat container khusus untuk sidebar full height
        echo '<div class="pmb-full-sidebar">';
        
        // (Opsional) Hilangkan title widget
        // if (!empty($instance['title'])) {
        //    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        // }
        
        // Get page IDs dari Carbon Fields
        $dashboard_page   = carbon_get_theme_option('pmb_home_page');
        $login_page       = carbon_get_theme_option('pmb_login_page');
        $register_page    = carbon_get_theme_option('pmb_user_registration_page');
        $registration_form= carbon_get_theme_option('pmb_registration_page');
        $profile_page     = carbon_get_theme_option('pmb_profile_page');
        
        echo '<ul class="pmb-navigation-menu">';
        
        // Dashboard
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
        if (!empty($registration_form)) {
            echo '<li><a href="' . esc_url(get_permalink($registration_form)) . '">' .
                '<span class="dashicons dashicons-clipboard"></span> ' .
                esc_html__('Formulir Pendaftaran', 'pmb-stba') . '</a></li>';
        }
        
        // Profile - Replaced Upload Dokumen with Profile
        if (!empty($profile_page)) {
            echo '<li><a href="' . esc_url(get_permalink($profile_page)) . '">' .
                '<span class="dashicons dashicons-id"></span> ' .
                esc_html__('Profil Saya', 'pmb-stba') . '</a></li>';
        }
        
        // Payment
        $payment_page = carbon_get_theme_option('pmb_payment_page');
        $payment_enabled = carbon_get_theme_option('pmb_payment_enabled') === 'yes';

        if (!empty($payment_page) && $payment_enabled) {
            $is_active = get_the_ID() == $payment_page ? 'active' : '';
            echo '<li class="' . $is_active . '"><a href="' . esc_url(get_permalink($payment_page)) . '">' .
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

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title (leave blank to hide):', 'pmb-stba'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" 
                value="<?php echo esc_attr($title); ?>" placeholder="<?php esc_attr_e('Leave blank to hide title', 'pmb-stba'); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}