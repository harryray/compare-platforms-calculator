<?php
/**
 * @author    info@netbrained.com
 * @license   GPL-2.0+
 * @link      http://netbrained.com/plugins
 * @copyright Netbrained
 *
 * Plugin Name: Compare Platform Calulator
 * Plugin URI:  http://netbrained.com/plugins
 * Description: Description
 * Version:     1.0.0
 * Author:      info@netbrained.com
 * Author URI:  http://netbrained.com
 * Text Domain: cplat
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

if ( ! defined( 'CPC_PLUGIN_DIR' ) ) {
	define( 'CPC_PLUGIN_DIR',  plugin_dir_path( __FILE__ )  );
}
include 'class-calculator-error.php';

include 'class-calculator-custody-charges-method-1.php';
include 'class-calculator-custody-charges-method-2.php';
include 'class-calculator-custody-charges-method-3.php';
include 'class-calculator-custody-charges-method-4.php';
include 'class-calculator-custody-charges-method-5.php';

include 'class-calculator-product-charges.php';
include 'class-calculator-dealing-charges.php';
include 'class-acc-openning-charges.php';
include 'class-calculator-compare.php';

include 'post-types.php';
include 'shortcodes.php';
include 'functions.php';
include 'class-subscriber-data.php';
include 'class-platform-vendor-data.php';
include 'ajax.php';
include 'libs/cmb2-config.php';
include 'questions-config.php';
include 'jargon.php';
