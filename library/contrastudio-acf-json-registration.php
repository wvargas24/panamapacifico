<?php
/**
 * ACF Json Registration
 *
 * PHP version 8
 *
 * @category Themes
 * @package  Theme_Acf
 * @author   Wuilly Vargas <wuilly.vargas22@gmail.com>
 * @license  GPL-2.0-or-later http://www.gnu.org/licenses/gpl-2.0.txt
 * @link     https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

define('ACF_JSON_DIR_PATH', FL_CHILD_THEME_DIR . '/inc');

/** 
 * ACF Json Registration
 *
 * @param $path Path
 *
 * @return string string path
 */
function Acf_Json_Save_point($path)
{
    // Update path
    $path = ACF_JSON_DIR_PATH . '/acf-json';
    // Return path
    return $path;
}
add_filter('acf/settings/save_json', 'Acf_Json_Save_point');


/**
 * Register the path to load the ACF json files so that they are version controlled.
 *
 * @param $paths The default relative path to the folder where ACF saves the files.
 *
 * @return string The new relative path to the folder where we are saving the files.
 */
function Acf_Json_Load_point($paths)
{
    // Remove original path
    unset($paths[0]);// Append our new path
    $paths[] = ACF_JSON_DIR_PATH . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'Acf_Json_Load_point');