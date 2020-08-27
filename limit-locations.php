<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Location_Limits
 *
 * @wordpress-plugin
 * Plugin Name:       Listing Pro Location Limits
 * Plugin URI:        https://github.com/peterson-umoke/listing-pro-limits
 * Description:       This is a plugin that adds limits to the locations for pricing plans and used to control what the number of locations a user can select
 * Version:           1.0.0
 * Author:            P.N.U.M.A
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       location-limits
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LOCATION_LIMITS_VERSION', '1.0.0');

/**
 * require t5he autoloader from composer
 */
require_once dirname(__FILE__) . "/vendor/autoload.php";

class umk_limit_locations
{
    /**
     * the post type to apply to
     *
     * @var string
     */
    private $postType;

    /**
     * the plugin id
     *
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $field_key;

    /**
     * the construct method
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->postType = "price_plan";
        $this->field_key = 'location_limit_number';
    }

    /**
     * add the required actions and hooks
     */
    public function init()
    {
        add_action('cmb2_admin_init', array($this, 'metabox'));
        add_action('wp_ajax_get_locations_limits', array($this, 'get_location_limits'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    /**
     * the list of metabox to add to the application
     *
     * @return object|void|string
     */
    public function metabox()
    {
        $cmb = new_cmb2_box(array(
            'id' => $this->id,
            'title' => __('Location Limit', 'location-limits'),
            'object_types' => array('price_plan'), // Post type
            'context' => 'side',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
        ));

        $cmb->add_field(array(
            'name' => __('Location Limits', 'location-limits'),
            'desc' => __('Enter the limit of locations for this pricing plan', 'location-limits'),
            'id' => 'location_limit_number',
            'type' => 'text',
            "default" => 5,
            "attributes" => [
                "type" => "number",
                "min" => 5,
            ],
//            'default_cb' => array($this, 'default_metabox_value'), // function should return a bool value
        ));
    }

    /**
     * ajax used to register the required enpoints for getting the location limits
     * @noinspection PhpParamsInspection
     */
    public function get_location_limits()
    {
        $id = $_REQUEST['plan_id'];
        $location = cmb2_get_field_value($this->id, $this->field_key, $id);
        $location = array("limit" => $location);

        // kill the script
        die(json_encode($location));
    }

    /**
     * enqueue the javascript for the plugin
     */
    public function enqueue_assets()
    {
        wp_register_script('limit-locations-script', plugins_url('limit-locations.js', __FILE__));
        wp_enqueue_script('limit-locations-script');
    }
}

// instantiate the plugin
$umkLimitLocations = new umk_limit_locations("limit-locations");

// init the plugin
$umkLimitLocations->init();