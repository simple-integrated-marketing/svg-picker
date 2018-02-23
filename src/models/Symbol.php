<?php
/**
 * Svg Picker plugin for Craft CMS 3.x
 *
 * Define your own svg symbols and use a field type to pick it.
 *
 * @link      https://simple.com.au
 * @copyright Copyright (c) 2018 Simple Integrated Marketing
 */

namespace simpleteam\svgpicker\models;

use simpleteam\svgpicker\SvgPicker;

use Craft;
use craft\base\Model;

/**
 * Symbol Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Simple Integrated Marketing
 * @package   SvgPicker
 * @since     1.0.0
 */
class Symbol extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
