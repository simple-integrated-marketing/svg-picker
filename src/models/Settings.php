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
 * SvgPicker Settings Model
 *
 * This is a model used to define the plugin's settings.
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
class Settings extends Model implements \JsonSerializable
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $filePath = "";
    public $json = '[]';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        // Because Craft native settings column cannot handle LONGTEXT, save it to file for now. (EVENT HOOK is used to save to this file)
        $file = dirname(__FILE__).DIRECTORY_SEPARATOR."Setting.json";
        if (file_exists($file)) {
            $this->json = file_get_contents($file);
        } else {
            file_put_contents($file,$this->json);
        }
        $this->filePath = $file;
    }

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
            ['json', 'string'],
            ['json', 'default', 'value' => '[]'],
        ];
    }

    public function jsonSerialize() {
        return ['filePath'=>$this->filePath];
    }
}
