<?php
/**
 * Svg Picker plugin for Craft CMS 3.x
 *
 * Define your own svg symbols and use a field type to pick it.
 *
 * @link      https://simple.com.au
 * @copyright Copyright (c) 2018 Simple Integrated Marketing
 */

namespace simpleteam\svgpicker\services;

use simpleteam\svgpicker\SvgPicker;

use Craft;
use craft\base\Component;

/**
 * SvgPickerService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Simple Integrated Marketing
 * @package   SvgPicker
 * @since     1.0.0
 */
class SvgPickerService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     SvgPicker::$plugin->svgPickerService->exampleService()
     *
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (SvgPicker::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
