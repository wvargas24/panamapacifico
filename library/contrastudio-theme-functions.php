<?php
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * User OS
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if (!function_exists('contrastudio_user_os')) {
    function contrastudio_user_os()
    {
        $os = false;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($user_agent, 'iPad;') || strpos($user_agent, 'iPhone;')) {
            $os = ' ios';
        }
        return $os;
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * User Agent // For: Parallax - Safari detect (& future use)
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if (!function_exists('contrastudio_user_agent')) {
    function contrastudio_user_agent()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (stripos($user_agent, 'Chrome/') !== false) {
            $user_agent = 'chrome';
        } elseif ((stripos($user_agent, 'Safari/') !== false) && (stripos($user_agent, 'Mobile/') !== false)) {
            $user_agent = 'safari mobile';
        } elseif (stripos($user_agent, 'Safari/') !== false) {
            $user_agent = 'safari';
        } else {
            // for future use
            $user_agent = false;
        }

        return $user_agent;
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Remove RSS Version
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_rss_version()
{
    return '';
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Remove injected CSS for recent comments widget
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_remove_wp_widget_recent_comments_style()
{
    if (has_filter('wp_head', 'wp_widget_recent_comments_style')) {
        remove_filter('wp_head', 'wp_widget_recent_comments_style');
    }
}
function contrastudio_remove_recent_comments_style()
{
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Unregister all widgets
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function unregister_default_widgets()
{
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('Twenty_Eleven_Ephemera_Widget');
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Replace gallery styles
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_gallery_style($css)
{
    return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Add global classes to the 'body' tag
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function browser_body_class($classes = '')
{
    $classes[] = 'body';
    return $classes;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Clean up p tags around block elements
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function remove_empty_p($content)
{
    $content = preg_replace(array(
        '#<p>\s*<(div|aside|section|article|header|footer)#',
        '#</(div|aside|section|article|header|footer)>\s*</p>#',
        '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
        '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
        '#<p>\s*</(div|aside|section|article|header|footer)#',
    ), array(
        '<$1',
        '</$1>',
        '</$1>',
        '<$1$2>',
        '</$1',
    ), $content);
    return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Modified the_author_posts_link() to just return author post link
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function contrastudio_get_the_author_posts_link()
{
    global $authordata;
    if (!is_object($authordata))
        return false;
    $link = sprintf(
        '<a href="%1$s" title="%2$s" rel="author" class="underline">%3$s</a>',
        get_author_posts_url($authordata->ID, $authordata->user_nicename),
        esc_attr(sprintf(__('Posts by %s'), get_the_author())),
        get_the_author()
    );
    return $link;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Converts hex color to rgb
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function hex2rgb($hex)
{
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);

    return implode(", ", $rgb);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Disable the emoji's
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Replace the 'Howdy' message in the admin bar
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function replace_howdy($wp_admin_bar)
{
    $my_account = $wp_admin_bar->get_node('my-account');
    if ($my_account) {
        $newtitle = str_replace('Howdy,', '', $my_account->title);
        $wp_admin_bar->add_node(array(
            'id' => 'my-account',
            'title' => $newtitle,
        ));
    }

}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Check for logged in user via ajax
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function ajax_check_user_logged_in()
{
    echo is_user_logged_in() ? 'yes' : 'no';
    die();
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Determine if the value is in fact a number
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function checkNumberValue($chk)
{
    $chklength = preg_replace('/[0-9]+/', '', $chk);
    $rtn = false;
    if (strlen($chklength) > 0)
        $rtn = true;
    return $rtn;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Determine whether the padding value needs 'px' added to it or not
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function checkPadding($val)
{
    $rtn = '0px';
    $val = str_replace('px', '', $val);
    if (strpos($val, '%') > 0) {
        $rtn = $val;
    } else {
        $rtn = "{$val}px";
    }
    return $rtn;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Format phone numbers for tel: links and formatted display
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function formatTelNumber($tel, $visual_format = false, $ext = false)
{
    $strlen = strlen($tel);
    $linkFormat = '$1$2$3';
    $visualFormat = '$1-$2-$3';

    if ($ext) {
        $linkFormat = '$1$2$3,$4';
        $visualFormat = '$1-$2-$3 ext. $4';
    }
    $telFormat = $linkFormat;
    if ($visual_format)
        $telFormat = $visualFormat;

    return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4})(?:[ \D#\-]*(\d{3,6}))?.*~', $telFormat, $tel);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns an object // Handles custom fields for images
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function get_image_info($image_id, $image_size = 'full', $max_width = '2000px')
{
    if ($image_id == '' || !$image_id) {
        return false;
    } else {
        //$image_src = wp_get_attachment_image_url($image_id, 'full');
        $image_srcset = wp_get_attachment_image_srcset($image_id, $image_size);
        $image_data = wp_get_attachment_image_src($image_id, $image_size);
        $image_info['srcset'] = $image_srcset;
        $image_info['url'] = $image_data[0];
        $image_info['width'] = $image_data[1];
        $image_info['height'] = $image_data[2];
        $image_info['alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        if (!$image_data) {
            $image_info = false;
        }
        return $image_info;
    }
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns a string // Video file url
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function get_video_info($image_id)
{
    $video_data = wp_get_attachment_url($image_id);
    if (!$video_data)
        $video_data = false;
    return $video_data;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns an object // Handles custom fields for file attachments & downloadables
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function get_file_info($image_id)
{
    $file_info = wp_get_attachment_url($image_id);
    $file_array['url'] = $file_info;
    $file_array['filesize'] = size_format(filesize(get_attached_file($image_id)), 2);

    if (!$file_info)
        $file_array = false;
    return $file_array;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns an object // Handles post objects
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function get_post_info($post_objects, $def = false)
{
    if ($post_objects):
        $post_info_id = $post_objects;
        $post_info['id'] = $post_info_id;
        $post_info['link'] = get_the_permalink($post_info_id);
        $post_info['date'] = get_the_date('m/d/y', $post_info_id);
        $post_info['title'] = get_the_title($post_info_id);
        $post_info['excerpt'] = get_the_excerpt($post_info_id);
        $post_info['avatar'] = get_avatar(get_the_author_meta($post_info_id), 48);
        $post_info['author'] = get_the_author($post_info_id);
    endif;

    if ($def) {
        $post_info = $post_info ? $post_info : $def;
    }

    if (!$post_info)
        $post_info = false;
    return $post_info;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns custom field values // replaces ACF's get_field
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function get_pm_info($fd, $def = false, $theID = false, $term = false)
{
    $pm_info = false;

    if (!$theID) {
        $theID = get_the_ID();
    }

    if (strpos($fd, 'options_') === 0) {
        $pm_info = get_option($fd);
    } else {
        $pm_info = get_post_meta($theID, $fd, true);
    }

    if ($def) {
        $pm_info = ($pm_info !== false) ? $pm_info : $def;
    }

    return $pm_info;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Clean up element attribute strings (remove invalid characters)
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function cleanString($string)
{
    // replace spaces and underscores with dashes first
    $res = preg_replace(array("/\s+/"), "-", $string);
    // allow letters, numbers and dashes
    $res = preg_replace('/[^a-zA-Z0-9\-_]/', '', $res);
    // make lowercase
    $res = strtolower($res);
    return $res;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns a randomized number for element IDs
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function getModuleID($string_start = 'mod')
{
    $mod_return = $string_start;
    $mod_cnt = rand(1, 100000);
    return $mod_return . '_' . $mod_cnt;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns an formatted string for an element ID // defaults to use getModuleID
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function pageBlockID($id_string = false, $section_string_start = '')
{
    if (!$id_string) {
        $id_string = getModuleID($section_string_start);
    }
    return cleanString($id_string);
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Returns a value between a min and a max value
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function getMinMax($val, $min, $max)
{
    if ($val < $min) {
        $val = $min;
    }
    if ($val > $max) {
        $val = $max;
    }
    return $val;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Used for tracking error messages
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function tigerlily_errors()
{
    static $wp_error;
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * List Posts by Year
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

function posts_by_year()
{
    // array to use for results
    $years = array();

    // get posts from WP
    $posts = get_posts(array(
        'numberposts' => -1,
        'orderby' => 'post_date',
        'order' => 'ASC',
        'post_type' => 'post',
        'post_status' => 'publish'
    ));

    // loop through posts, populating $years arrays
    foreach ($posts as $post) {
        $years[date('Y', strtotime($post->post_date))][] = $post;
    }

    // reverse sort by year
    krsort($years);

    return $years;
}


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 * Provide Backup Alt Text if it's not provided already
 * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
function img_alt($img = false)
{
    $img_alt_return = false;
    if ($img) {
        if (is_string($img)) {
            $img_url = $img;
        } else {
            $img_url = $img['url'];
        }
        $base_filename = substr(strrchr($img_url, "/"), 1);
        $backup_alt = strtok(str_replace(array('-', '_'), ' ', $base_filename), '.');
        $img_alt_return = $img['alt'];
        if (!$img_alt_return) {
            $img_alt_return = $backup_alt;
        }
    }
    return $img_alt_return;
}


function inchesToCentimeters($inches)
{
    return strval(round($inches * 2.54)) . ' cm';
}
function poundsToKilograms($pounds)
{
    return strval(round($pounds * 0.453592)) . ' kg';
}

function empty_content($str)
{
    return trim(str_replace('&nbsp;', '', strip_tags($str))) == '';
}

function current_url()
{
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $validURL = str_replace("&", "&amp", $url);
    return $validURL;
}

function PageURL()
{
    $pageURL = 'http';
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    if ($_SERVER['SERVER_PORT'] != '80') {
        $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
    } else {
        $pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    if (strpos($pageURL, 'admin-ajax.php') !== false) {
        $pageURL = get_permalink(get_the_ID());
    }

    return $pageURL;
}

function stringWordCount($full_string = '', $word_limit = 20)
{
    $str = $full_string;
    $str = wordwrap($str, $word_limit);
    $str = explode("\n", $str);
    $str = $str[0];

    return $str;
}

function is_published()
{
    $post = get_post();
    if ($post->post_status == 'publish')
        return true;

    return false;
}

function is_draft()
{
    $post = get_post();
    if ($post->post_status == 'draft')
        return true;

    return false;
}

function getClientIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function ip_details($ip)
{
    $details = '';

    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, 'https://ipinfo.io/' . $ip . '/country?token=69c5df63e6a1ef');
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);

    if (empty($buffer)) {
        $details = "Nothing returned from url.<p>";
    } else {
        $details = $buffer;
    }

    return $details;
}

function format_phone_number($phoneNumber)
{
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    if (strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+' . $countryCode . ' (' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
    } else if (strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = '(' . $areaCode . ') ' . $nextThree . '-' . $lastFour;
    } else if (strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree . '-' . $lastFour;
    }

    return $phoneNumber;
}

function dot_format_phone_number($phoneNumber)
{
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    if (strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber) - 10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+' . $countryCode . '.' . $areaCode . '.' . $nextThree . '.' . $lastFour;
    } else if (strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = $areaCode . '.' . $nextThree . '.' . $lastFour;
    } else if (strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree . '.' . $lastFour;
    }

    return $phoneNumber;
}

function my_bb_custom_fonts($system_fonts)
{

    $system_fonts['PP Supply Mono'] = array(
        'fallback' => 'monospace',
        'weights' => array(
            '400',
            '200'
        ),
    );

    return $system_fonts;

}
add_filter('fl_theme_system_fonts', 'my_bb_custom_fonts');
add_filter('fl_builder_font_families_system', 'my_bb_custom_fonts');

function create_linked_user_role()
{
    add_role(
        'linked_user',
        'Linked User',
        array(
            'read' => true, // Permission to read
            'edit_posts' => true, // Permission to create and edit their own posts
            'publish_posts' => true, // Permission to publish posts
            'edit_contenidos_propios' => true, // Permission to edit their own events
            'publish_contenidos_propios' => true, // Permission to publish events
            'read_private_contenidos_propios' => true, // Permission to read private events
            'read_private_posts' => true, // Permission to read exclusive content
            'upload_files' => true,
        )
    );
}
add_action('init', 'create_linked_user_role');

function add_dark_logo_option($customizer)
{
    // Añadir configuración para el logo dark
    $customizer['fl-header-logo']['options']['fl-logo-dark-image'] = array(
        'setting' => array(
            'default' => '',
        ),
        'control' => array(
            'class' => 'WP_Customize_Image_Control',
            'label' => __('Logo Dark', 'your-child-theme-textdomain'),
            'description' => __('Sube un logo alternativo para fondos oscuros.', 'your-child-theme-textdomain'),
        ),
    );

    return $customizer;
}
add_filter('fl_customize_register', 'add_dark_logo_option');


add_action('save_post', function ($post_id) {
    // Verificar que sea del tipo de contenido "project"
    if ('project' !== get_post_type($post_id)) {
        return;
    }

    // Verificar el valor del campo ACF
    $acf_amenities = get_field('acf_project_amenities', $post_id); // Cambia 'acf_project_amenities' por el nombre del campo ACF

    if (!empty($acf_amenities) && is_array($acf_amenities)) {
        // Asignar las amenidades del ACF a la taxonomía
        wp_set_object_terms($post_id, $acf_amenities, 'project_amenity');
    }
});
