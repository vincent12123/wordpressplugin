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
        // Add sticky class to the before_widget argument
        $args['before_widget'] = str_replace('class="', 'class="pmb-sticky-nav ', $args['before_widget']);
        
        echo $args['before_widget'];
        
        // Get page IDs from Carbon Fields
        $dashboard_page = carbon_get_theme_option('pmb_home_page');
        $login_page = carbon_get_theme_option('pmb_login_page');
        $register_page = carbon_get_theme_option('pmb_user_registration_page');
        $registration_form = carbon_get_theme_option('pmb_registration_page');
        $profile_page = carbon_get_theme_option('pmb_profile_page');
        
        // Get current page ID
        $current_page_id = get_queried_object_id();
        
        // Build menu with modern styling
        echo '<ul class="pmb-navigation-menu">';
        
        // Dashboard
        if (!empty($dashboard_page)) {
            $is_current = ($current_page_id == $dashboard_page) ? 'current-menu-item' : '';
            echo '<li class="' . $is_current . '"><a href="' . esc_url(get_permalink($dashboard_page)) . '">' . 
                '<span class="dashicons dashicons-dashboard"></span> ' .
                '<span class="menu-text">' . esc_html__('Dashboard', 'pmb-stba') . '</span></a></li>';
        }
        
        // Information
        echo '<li><a href="#">' . 
            '<span class="dashicons dashicons-info"></span> ' .
            '<span class="menu-text">' . esc_html__('Informasi', 'pmb-stba') . '</span></a></li>';
        
        // Registration form
        if (!empty($registration_form)) {
            $is_current = ($current_page_id == $registration_form) ? 'current-menu-item' : '';
            echo '<li class="' . $is_current . '"><a href="' . esc_url(get_permalink($registration_form)) . '">' . 
                '<span class="dashicons dashicons-clipboard"></span> ' .
                '<span class="menu-text">' . esc_html__('Formulir Pendaftaran', 'pmb-stba') . '</span></a></li>';
        }
        
        // Profile/Upload
        if (!empty($profile_page)) {
            $is_current = ($current_page_id == $profile_page) ? 'current-menu-item' : '';
            echo '<li class="' . $is_current . '"><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                '<span class="dashicons dashicons-id"></span> ' .
                '<span class="menu-text">' . esc_html__('Profil Saya', 'pmb-stba') . '</span></a></li>';
        }
        
        // Payment
        echo '<li><a href="#">' . 
            '<span class="dashicons dashicons-money-alt"></span> ' .
            '<span class="menu-text">' . esc_html__('Pembayaran Formulir', 'pmb-stba') . '</span></a></li>';
        
        // Logout - always keep this at the bottom
        echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
            '<span class="dashicons dashicons-exit"></span> ' .
            '<span class="menu-text">' . esc_html__('Keluar', 'pmb-stba') . '</span></a></li>';
        
        echo '</ul>';
        
        echo $args['after_widget'];
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