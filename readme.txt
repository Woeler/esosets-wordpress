=== ESO Sets and Skills ===
Contributors: woeler
Donate link: https://patreon.com/woeler
Tags: Elder Scrolls Online Sets Skills Tooltip
Requires at least: 4.9
Tested up to: 5.1.1
Requires PHP: 5.6
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Embed tooltips for sets and skills related to the Elder Scrolls Online into your pages and posts.

== Description ==

# EsoSets WordPress

Welcome to the eso-sets plugin for WordPress. With this plugin you can show tooltips in WordPress with gear set information about the Elder Scrolls Online. All data is directly pulled from https://eso-sets.com and https://eso-skillbook.com

## Sets
In order to use this plugin you must use shortcodes in your WordPress posts and pages. You need the set id of a specific set. Lets say you want to include the Death's Wind set, which has id 1. You would use the following shortcode.

`[esoset id="1"]`

Note that some WordPress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

`[esoset id="1" tooltip="true"]`

You can find all the set ids [here](https://eso-sets.com/sets/byid).

## Skills
In order to use this plugin you must use shortcodes in your WordPress posts and pages. You need the set id of a specific skill. Lets say you want to include the Dragonknight Standard skill, which has id 8. You would use the following shortcode.

`[esoskill id="8"]`

Note that some WordPress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

`[esoskill id="8" tooltip="true"]`

You can find all the skill ids [here](https://eso-skillbook.com/skills/byid).

## Skill bars
In order to use this plugin you must use shortcodes in your WordPress posts and pages. You need the skill id of all the skills on the bar. Lets say you want to include the Dragonknight Standard skill and fill a skillbar with it, which has id 8. You would use the following shortcode.

`[esoskillbar skill_1="8" skill_2="8" skill_3="8" skill_4="8" skill_5="8" skill_ult="8"]`

Note that some WordPress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

`[esoskillbar tooltip="true" skill_1="8" skill_2="8" skill_3="8" skill_4="8" skill_5="8" skill_ult="8"]`

Please do not access the eso-sets api with a modified version of this plugin. Accessing the API with a modified version of this plugin may result in the permanent suspension of your domain from the eso-sets api.

## 3rd party api
This plugin uses the Pathfinder Beast api to retrieve data about ESO sets and skills. You can find the full documentation of this api [here](https://docs.pathfindermediagroup.com).

This api is only used to retrieve set and skill data. No data from your Wordpress instance will be sent or stored on the external api. You can find the privacy policy [here](https://eso-sets.com/doc/privacypolicy.pdf).

== Installation ==

1. Install the plugin via the zip file.
2. Activate the plugin.
3. Start using the shortcodes described in the readme.