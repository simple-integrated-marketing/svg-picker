<?php
/**
 * Svg Picker plugin for Craft CMS 3.x
 *
 * Define your own svg symbols and use a field type to pick it.
 *
 * @link      https://simple.com.au
 * @copyright Copyright (c) 2018 Simple Integrated Marketing
 */

namespace simpleteam\svgpicker\assetbundles\SvgPicker;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use craft\web\assets\vue\VueAsset;
use yii\web\JqueryAsset;

/**
 * SvgPickerAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Simple Integrated Marketing
 * @package   SvgPicker
 * @since     1.0.0
 */
class SvgPickerAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@simpleteam/svgpicker/assetbundles/svgpicker/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
            VueAsset::class,
            JqueryAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/svgpicker.js',
        ];


        parent::init();
    }
}
