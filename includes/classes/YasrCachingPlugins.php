<?php

/*

Copyright 2020 Dario Curvino (email : d.curvino@gmail.com)

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

/**
 * Check if caching plugin is active
 *
 * @author Dario Curvino <@dudo>
 * @since 2.7.7
 * Class YasrFindCachingPlugins
 */
class YasrCachingPlugins {

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return false|string
     */
    public function cachingPluginFound () {
        $methods = get_class_methods($this);

        foreach($methods as $method) {
            if((substr( $method, 0, 4 ) === "find") && $this->{$method}()) {
                $plugin_name = str_replace('find', '', $method);
                return $plugin_name;
            }
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findWpRocket() {
        if (is_plugin_active('wp-rocket/wp-rocket.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findCacheEnabler() {
        if (is_plugin_active('cache-enabler/cache-enabler.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findLitespeed() {
        if (is_plugin_active('litespeed-cache/litespeed-cache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findW3TotalCache() {
        if (is_plugin_active('w3-total-cache/w3-total-cache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findWpFastestCache() {
        if (is_plugin_active('wp-fastest-cache/wpFastestCache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findWpSuperCache() {
        if (is_plugin_active('wp-super-cache/wp-cache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findWpOptimize() {
        if (is_plugin_active('wp-optimize/wp-optimize.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findBreeze() {
        if (is_plugin_active('breeze/breeze.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findCometCache() {
        if (is_plugin_active('comet-cache/comet-cache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findHummingbird() {
        if (is_plugin_active('hummingbird-performance/wp-hummingbird.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findPantheon() {
        if (is_plugin_active('pantheon-advanced-page-cache/pantheon-advanced-page-cache.php')) {
            return true;
        }
        return false;
    }

    /**
     * @author Dario Curvino <@dudo>
     * @since  2.7.7
     * @return bool
     */
    public function findPerformanceScoreBooster() {
        if (is_plugin_active('wp-performance-score-booster/wp-performance-score-booster.php')) {
            return true;
        }
        return false;
    }

}