<?php
/**
 * Shortcodes
 * 
 * @package Slick_WP_Gallery/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Slick_WP_Gallery_Shortcodes {

	/**
	 * Constructor Function
	 */
	public function __construct() {
		// Upon WordPress initization, run Slick_WP_Gallery_Shortcodes::register().
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register all shortcodes used in this plugin
	 *
	 * @return void
	 */
	public function register() {
        add_filter( 'post_gallery', array( $this, 'gallery' ), 100, 2 );
    }
    
    public function gallery($instance, $attr) {
        // This is straight C+P from wp-includes/media.php:1914
        // Got to get the attachments somehow!
        $post = get_post();

        $html5 = current_theme_supports( 'html5', 'gallery' );
        $atts  = shortcode_atts(
            array(
                'order'      => 'ASC',
                'orderby'    => 'menu_order ID',
                'id'         => $post ? $post->ID : 0,
                'itemtag'    => $html5 ? 'figure' : 'dl',
                'icontag'    => $html5 ? 'div' : 'dt',
                'captiontag' => $html5 ? 'figcaption' : 'dd',
                'columns'    => 3,
                'size'       => 'thumbnail',
                'include'    => '',
                'exclude'    => '',
                'link'       => '',
            ),
            $attr,
            'gallery'
        );

        $id = intval( $atts['id'] );

        if ( ! empty( $atts['include'] ) ) {
            $_attachments = get_posts(
                array(
                    'include'        => $atts['include'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
    
            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[ $val->ID ] = $_attachments[ $key ];
            }
        } elseif ( ! empty( $atts['exclude'] ) ) {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'exclude'        => $atts['exclude'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        } else {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        }
    
        if ( empty( $attachments ) ) {
            return '';
        }

        // Since we have attachments, it's time to include the styles and scripts for slick
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'slick-theme' );
        wp_enqueue_script( 'slick' );

        ob_start();

        $id = bin2hex(random_bytes(10));
        ?>

        <div class="gallery" id="<?php echo $id; ?>">
            <?php foreach ($attachments as $aid => $attachment): ?>
                <div>
                <?php
                    if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
                        $image_output = wp_get_attachment_link( $aid, $atts['size'], false, false, false, $attr );
                    } elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
                        $image_output = wp_get_attachment_image( $aid, $atts['size'], false, $attr );
                    } else {
                        $image_output = wp_get_attachment_link( $aid, $atts['size'], true, false, false, $attr );
                    }    

                    echo $image_output;
                ?>
                </div>
            <?php endforeach; ?>
        </div>

        <script>
            (function ($) {
                $(document).ready(() => {
                    $('#<?php echo $id ?>').slick({
                        dots: true,
                        slidesToShow: <?php echo intval( $atts['columns'] ); ?>,
                        adaptiveHeight: true
                    });
                });
            })(jQuery);
        </script>

        <?php
        return ob_get_clean();
    }

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Slick_WP_Gallery_Shortcodes is forbidden' ) ), esc_attr( $this->_version ) );

	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Slick_WP_Gallery_Shortcodes is forbidden' ) ), esc_attr( $this->_version ) );
	} // End __wakeup ()
}
