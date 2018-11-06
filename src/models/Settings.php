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

        Craft::configure($this, static::retrieveSavedSetting());
    }

    /**
     * Previously, we store the setting in a json file due to the fact that the plugin setting column in Craft is just a TEXT field. And if the svg set is too big, it will get truncated.
     * However, this remains an issue because *.Setting.json stored in the vendor folder and cannot be recovered in migration.
     * This function will check for any legacy Setting.json file and convert it to the new solution - mysql table
     * Getter Function for settings
     */
    public static function retrieveSavedSetting() {
        // Setting default
        $setting = [
            'filePath' => __DIR__.DIRECTORY_SEPARATOR."Setting.json",
            'json' => '[]',
        ];
        // Create mysql table if not exist.
        if (!Craft::$app->db->schema->getTableSchema("{{%simple-svg-picker}}")) {
            Craft::$app->db->createCommand()->createTable("{{%simple-svg-picker}}", [
                "id" => 'pk',
                "json" => 'LONGTEXT',
                "dateCreated" => 'datetime',
                "dateUpdated" => 'datetime',
                "uid" => 'string'
            ])->execute();
        }

        $settingInDb = Craft::$app->db->createCommand("SELECT json FROM {{%simple-svg-picker}}")->queryOne();
        if ($settingInDb) {
            $setting['json'] = $settingInDb['json'];
            return $setting;
        }

        // If there is no record in system try to check if the setting is stored in Setting.json, and insert it into the new mysql table
        $file = $setting['filePath'];
        if (file_exists($file)) {
            $json = file_get_contents($file);
            Craft::$app->db->createCommand()->insert("{{%simple-svg-picker}}", [
                'json' => $json
            ])->execute();
            $setting['json'] = $json;
        }

        return $setting;
    }

    /**
     * Setter function for settings
     * @param $json
     * @throws \yii\db\Exception
     */
    public static function saveSetting($json) {
        $settingInDb = Craft::$app->db->createCommand("SELECT json FROM {{%simple-svg-picker}}")->queryOne();
        if ($settingInDb) {
            return Craft::$app->db->createCommand()->update("{{%simple-svg-picker}}", ['json' => $json])->execute();
        }
        return Craft::$app->db->createCommand()->insert("{{%simple-svg-picker}}", ['json' => $json])->execute();
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
