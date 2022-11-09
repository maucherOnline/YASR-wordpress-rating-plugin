<?php
/*
Copyright 2015 Dario Curvino (email : d.curvino@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

//Plugin absolute path
define('YASR_PRO_ABSOLUTE_PATH', __DIR__);

//Plugin RELATIVE PATH without slashes (just the directory's name)
define('YASR_PRO_RELATIVE_PATH', dirname( plugin_basename( __FILE__ )));

//admin absolute path
define('YASR_PRO_ABSOLUTE_PATH_ADMIN', YASR_PRO_ABSOLUTE_PATH . '/admin');

//includes absolute path
define('YASR_PRO_ABSOLUTE_PATH_INCLUDES', YASR_PRO_ABSOLUTE_PATH . '/includes');

//public absolute path
define('YASR_PRO_ABSOLUTE_PATH_PUBLIC', YASR_PRO_ABSOLUTE_PATH . '/public');

//admin relative path
define('YASR_PRO_RELATIVE_PATH_ADMIN', YASR_PRO_RELATIVE_PATH . '/admin');

//includes relative path
define('YASR_PRO_RELATIVE_PATH_INCLUDES', YASR_PRO_RELATIVE_PATH . '/includes');

//public relative path
define('YASR_PRO_RELATIVE_PATH_PUBLIC', YASR_PRO_RELATIVE_PATH . '/public');


define("YASR_PRO_JS_DIR",   plugins_url() . '/' . YASR_RELATIVE_PATH . '/yasr_pro/js/');
define("YASR_PRO_CSS_DIR",  plugins_url() . '/' . YASR_RELATIVE_PATH . '/yasr_pro/css/');

//yasr custom image directory
define('YASR_PRO_ST_IMG_DIR', plugins_url( YASR_PRO_RELATIVE_PATH_INCLUDES ) . '/img/stars/');

//Plugin language directory: here I've to use the absolute path, instead of relative like in the free version
//because I will call directly $mo->import_from_file in function

define('YASR_PRO_LANG_DIR', YASR_PRO_ABSOLUTE_PATH . '/languages/');

if(is_admin()) {
    require YASR_PRO_ABSOLUTE_PATH_ADMIN . '/yasr-pro-admin-init.php';
} else {
    require YASR_PRO_ABSOLUTE_PATH_PUBLIC . '/yasr-pro-public-init.php';
}
//includes
require YASR_PRO_ABSOLUTE_PATH_INCLUDES . '/yasr-pro-includes-init.php';
