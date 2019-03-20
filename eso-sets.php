<?php

/*
Plugin Name: ESO Sets and Skills
Plugin URI: https://github.com/Woeler/esosets-wordpress
Description: Embed tooltips for sets and skills related to the Elder Scrolls Online into your pages and posts.
Version: 1.2
Author: Woeler
Author URI: https://www.github.com/woeler
License: GPL-3
*/

defined('ABSPATH') || exit;

add_action('plugins_loaded', 'EsoSets::setup');

final class EsoSets
{
    /**
     * EsoSets constructor.
     */
    public function __construct()
    {
    }

    /**
     * Register all the needed functions in Wordpress
     */
    public static function setup()
    {
        $esosets = new EsoSets();
        add_shortcode('esoset', [$esosets, 'esoset_func']);
        add_shortcode('esoskill', [$esosets, 'esoskill_func']);
        add_shortcode('esoskillbar', [$esosets, 'esoskill_skillbar_func']);
        add_action('wp_head', [$esosets, 'addStyle']);
    }

    /**
     * Convert all esoset shortcode elements to tooltips upon post/page save
     *
     * @param $atts
     * @return string
     */
    public function esoset_func($atts)
    {
        if (!isset($atts['id'])) {
            return 'Set id not provided';
        }

        $cache = get_transient(md5('esosets_' . serialize($atts)));

        if ($cache) {
            return $cache;
        }

        $result = wp_remote_get('https://beast.pathfindermediagroup.com/api/eso/sets/tooltip/' . $atts['id']);
        $result = json_decode(wp_remote_retrieve_body($result), true);

        if (!isset($result['name']) || !isset($result['tooltip'])) {
            if (isset($atts['setname'])) {
                return $atts['setname'];
            } else {
                return 'An error occurred retrieving the set data';
            }
        }

        $tooltip = str_replace('"', "'", $result['tooltip']);
        if (isset($atts['setname'])) {
            $name = $atts['setname'];
        } else {
            $name = $result['name'];
        }
        $return = '<a class="eso-set" href="https://www.eso-sets.com/set/' . $atts['id'] . '" target="_blank" rel="noopener" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip) . '">' . $name . '</a>';

        if (!is_preview()) {
            set_transient(md5('esosets_' . serialize($atts)), $return, 3600);
        }

        return $return;
    }

    /**
     * Convert all esoskill shortcode elements to tooltips upon post/page save
     *
     * @param $atts
     * @return string
     */
    public function esoskill_func($atts)
    {
        if (!isset($atts['id'])) {
            return 'Skill id not provided';
        }

        $cache = get_transient(md5('esoskills_' . serialize($atts)));

        if ($cache) {
            return $cache;
        }

        $result = wp_remote_get('https://beast.pathfindermediagroup.com/api/eso/skills/tooltip/' . $atts['id']);
        $result = json_decode(wp_remote_retrieve_body($result), true);

        if (!isset($result['name']) || !isset($result['tooltip'])) {
            if (isset($atts['setname'])) {
                return $atts['setname'];
            } else {
                return 'An error occurred retrieving the skill data';
            }
        }

        $tooltip = str_replace('"', "'", $result['tooltip']);
        if (isset($atts['skillname'])) {
            $name = $atts['skillname'];
        } else {
            $name = $result['name'];
        }
        $return = '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['id'] . '" target="_blank" rel="noopener" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip) . '">' . $name . '</a>';

        if (!is_preview()) {
            set_transient(md5('esoskills_' . serialize($atts)), $return, 3600);
        }

        return $return;
    }

    /**
     * Convert all esoskillbar shortcode elements to tooltips upon post/page save
     *
     * @param $atts
     * @return string
     */
    public function esoskill_skillbar_func($atts)
    {
        $cache = get_transient(md5('esoskillsbar_' . serialize($atts)));

        if ($cache) {
            return $cache;
        }

        $data = [];
        $data['skill_1'] = $atts['skill_1'];
        $data['skill_2'] = $atts['skill_2'];
        $data['skill_3'] = $atts['skill_3'];
        $data['skill_4'] = $atts['skill_4'];
        $data['skill_5'] = $atts['skill_5'];
        $data['skill_ult'] = $atts['skill_ult'];

        $result = wp_remote_get('https://beast.pathfindermediagroup.com/api/eso/skills/skillbar?' . http_build_query($data));
        $result = json_decode(wp_remote_retrieve_body($result), true);

        $tooltip_1 = str_replace('"', "'", $result['skill_1']['tooltip']);
        $tooltip_2 = str_replace('"', "'", $result['skill_2']['tooltip']);
        $tooltip_3 = str_replace('"', "'", $result['skill_3']['tooltip']);
        $tooltip_4 = str_replace('"', "'", $result['skill_4']['tooltip']);
        $tooltip_5 = str_replace('"', "'", $result['skill_5']['tooltip']);
        $tooltip_ult = str_replace('"', "'", $result['skill_ult']['tooltip']);

        $return = '<div class="esoskill-skillbar">';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_1'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_1) . '"><img class="skill-img" width="50px" src="' . $result['skill_1']['img'] . '" /></a> ';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_2'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_2) . '"><img class="skill-img" width="50px" src="' . $result['skill_2']['img'] . '" /></a> ';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_3'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_3) . '"><img class="skill-img" width="50px" src="' . $result['skill_3']['img'] . '" /></a> ';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_4'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_4) . '"><img class="skill-img" width="50px" src="' . $result['skill_4']['img'] . '" /></a> ';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_5'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_5) . '"><img class="skill-img" width="50px" src="' . $result['skill_5']['img'] . '" /></a> ';
        $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts['skill_ult'] . '" target="_blank" ';
        if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
            $return .= 'data-toggle="tooltip" ';
        }
        $return .= 'data-html="true" title="' . htmlspecialchars($tooltip_ult) . '"><img class="skill-img" style="margin-left:25px;" width="50px" src="' . $result['skill_ult']['img'] . '" /></a> ';
        $return .= '</div>';

        if (!is_preview()) {
            set_transient(md5('esoskillsbar_' . serialize($atts)), $return, 3600);
        }

        return $return;
    }

    /**
     * Add the tooltip stylesheet to the frontend of Wordpress
     */
    public function addStyle()
    {
        echo '<style>';
        include plugin_dir_path(__FILE__) . 'esosets_tooltips.css';
        echo '</style>';
    }
}
