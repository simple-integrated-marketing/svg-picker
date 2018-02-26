<?php
/**
 * Svg Picker plugin for Craft CMS 3.x
 *
 * Define your own svg symbols and use a field type to pick it.
 *
 * @link      https://simple.com.au
 * @copyright Copyright (c) 2018 Simple Integrated Marketing
 */

namespace simpleteam\svgpicker\twigextensions;

use craft\config\GeneralConfig;
use simpleteam\svgpicker\SvgPicker;

use Craft;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Simple Integrated Marketing
 * @package   SvgPicker
 * @since     1.0.0
 */
class SvgPickerTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'SvgPicker';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return [
        ];
    }

    /**
     * Returns an array of Twig functions, used in Twig templates via:
     *
     *      {% set this = someFunction('something') %}
     *
    * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('inlineSvgDefsContent', [$this, 'inlineSvgDefsContent'],['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ajaxSvgDefsContent', [$this, 'ajaxSvgDefsContent'],['is_safe' => ['html']]),
        ];
    }

    /**
     * Our function called via Twig; it can do anything you want
     *
     * @param null $text
     *
     * @return string
     */
    public function inlineSvgDefsContent($options = [])
    {
        $result = SvgPicker::$plugin->svgPickerService->generateSvgDefsContent($options);
        return $result;
    }

    public function ajaxSvgDefsContent($options = []) {
        $url = Craft::$app->config->general->actionTrigger."/svg-picker?".http_build_query(['options'=>$options]);
        $js = <<<JS
<script>
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "{$url}", true);
    ajax.send();
    ajax.onload = function(e) {
      var div = document.createElement("div");
      div.innerHTML = ajax.responseText;
      document.body.insertBefore(div, document.body.childNodes[0]);
    }
</script>
JS;
        return $js;
    }
}
