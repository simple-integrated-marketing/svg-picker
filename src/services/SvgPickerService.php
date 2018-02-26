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
    public function generateSvgDefsContent($options)
    {
        $filterBySets = $options['sets']??[];

        $settings = SvgPicker::getInstance()->getSettings();

        $settingObj = json_decode($settings->json,true);
        $result = "<svg aria-hidden=\"true\" style=\"position: absolute; width: 0; height: 0; overflow: hidden;\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><defs>";
        foreach ($settingObj as $set) {
            if (sizeof($filterBySets) > 0 && !in_array($set['name'],$filterBySets) ) {
                continue;
            }
            foreach ($set['svgs'] as $svg) {
                $result .="<symbol id='".addslashes($svg['id'])."' viewBox='".addslashes($svg['viewBox'])."'>".$svg['symbol']."</symbol>";
            }
        }
        $result .= "</defs></svg>";

        return $result;
    }
}
