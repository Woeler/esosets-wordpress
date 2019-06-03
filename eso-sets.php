<?php

/*
Plugin Name: ESO Sets and Skills
Plugin URI: https://github.com/Woeler/esosets-wordpress
Description: Embed tooltips for sets and skills related to the Elder Scrolls Online into your pages and posts.
Version: 1.3.1
Author: Woeler
Author URI: https://www.github.com/woeler
License: GPL-3
*/

defined('ABSPATH') || exit;

add_action('plugins_loaded', 'EsoSets::setup');

final class EsoSets
{
    protected $skillBarItems = [1, 2, 3, 4, 5, 'ult'];

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
        add_shortcode('esoskilllist', [$esosets, 'esoskill_skilllist_func']);
        add_action('wp_enqueue_scripts', [$esosets, 'addStyle']);
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
        foreach ($atts as $key => $skill_id) {
            if (false !== strpos($key, 'skill_')) {
                $data[$key] = $skill_id;
            }
        }

        $result = wp_remote_get('https://beast.pathfindermediagroup.com/api/eso/skills/skillbar?' . http_build_query($data));
        $result = json_decode(wp_remote_retrieve_body($result), true);

        $return = '<div class="esoskill-skillbar">';

        foreach ($result as $key => $skill) {
            $tooltip = str_replace('"', "'", $skill['tooltip']);
            $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $atts[$key] . '" target="_blank" ';
            if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
                $return .= 'data-toggle="tooltip" ';
            }
            if ($key === 'skill_ult') {
                $return .= 'data-html="true" title="' . htmlspecialchars($tooltip) . '"><img class="skill-img" style="margin-left:25px;" src="' . $skill['img'] . '" /></a> ';
            } else {
                $return .= 'data-html="true" title="' . htmlspecialchars($tooltip) . '"><img class="skill-img" src="' . $skill['img'] . '" /></a> ';
            }
        }

        $return .= '</div>';

        if (!is_preview()) {
            set_transient(md5('esoskillsbar_' . serialize($atts)), $return, 3600);
        }

        return $return;
    }

    /**
     * Convert all esoskilllist shortcode elements to tooltips upon post/page save
     *
     * @param $atts
     * @return string
     */
    public function esoskill_skilllist_func($atts)
    {
        $cache = get_transient(md5('esoskillslist_' . serialize($atts)));

        if ($cache) {
            return $cache;
        }

        $data = [];
        foreach ($atts as $key => $skill_id) {
            if (false !== strpos($key, 'skill_')) {
                $data[$key] = $skill_id;
            }
        }

        $result = wp_remote_get('https://beast.pathfindermediagroup.com/api/eso/skills/skilllist?' . http_build_query($data));
        $result = json_decode(wp_remote_retrieve_body($result), true);

        $return = '<div class="esoskill-skillbar">';

        foreach ($result as $skill) {
            $tooltip = str_replace('"', "'", $skill['tooltip']);
            $return .= '<a class="eso-set" href="https://www.eso-skillbook.com/skill/' . $skill['id'] . '" target="_blank" ';
            if (isset($atts['tooltip']) && $atts['tooltip'] == 'true') {
                $return .= 'data-toggle="tooltip" ';
            }
            $return .= 'data-html="true" title="' . htmlspecialchars($tooltip) . '"><img class="skill-img';
            if ($skill['type'] === 2) {
                $return .= ' passive-skill';
            }
            $return .= '" src="' . $skill['img'] . '" /></a> ';
        }

        $return .= '</div>';

        if (!is_preview()) {
            set_transient(md5('esoskillslist_' . serialize($atts)), $return, 3600);
        }

        return $return;
    }

    /**
     * Add the tooltip stylesheet to the frontend of Wordpress
     */
    public function addStyle()
    {
        $plugin_url = plugin_dir_url(__FILE__);

        wp_enqueue_style('ESO-Sets-Skills-CSS', $plugin_url . '/esosets_tooltips.css');
    }
}
