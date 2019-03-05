# Svg Picker Plugin for Craft3

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project
        
2. Add Github repo to your `composer.json`

        "repositories": [
            {
              "type":"git",
              "url":"https://github.com/simple-integrated-marketing/svg-picker.git"
            }
        ]

3. Then tell Composer to load the plugin:

        composer require "simple-team/svg-picker:dev-master"

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for Svg Picker.

## Usage

Svg Picker field is the id of the icon.

Example usage:

```twig
<svg>
    <use xlink:href="#{{entry.svgIcon}}">
</svg>
```

There are 3 ways you could "import" svg definition file into the DOM.

1. Manually reference external css/js like Icomoon. Note that you are responsible to keep the icons in sync with the setting.

2. Use `{{ inlineSvgDefsContent() }}` to import the the svg definitions inline, or `{{ inlineSvgDefsContent({sets:['SETNAME']}) }}` to only inline a list of specific sets.

3. Use `{{ ajaxSvgDefsContent() }}` to ajax the svg definitions via javascript, or `{{ ajaxSvgDefsContent({sets:['SETNAME']}) }}` to only ajax a list of specific sets.






Brought to you by [Simple Integrated Marketing](https://simple.com.au)
