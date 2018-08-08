<?php

/*
Plugin Name: Eso Sets
Plugin URI: https://github.com/Woeler/esosets-wordpress
Description: Embeds eso-sets tooltips in wordpress.
Version: 1.0
Author: Woeler
Author URI: https://www.woeler.eu
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

        $ch = curl_init('https://eso-sets.com/api/tooltip/set/' . $atts['id']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ex = curl_exec($ch);
        $result = json_decode($ex, true);
        curl_close($ch);

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
        $return .= 'data-html="true" title="' . $tooltip . '">' . $name . '</a>';

        if (!is_preview()) {

            set_transient(md5('esosets_' . serialize($atts)), $return, 3600);

        }
        return $return;
    }

    /**
     * Convert all esoskillbook shortcode elements to tooltips upon post/page save
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

        $ch = curl_init('https://eso-skillbook.com/api/tooltip/' . $atts['id']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ex = curl_exec($ch);
        $result = json_decode($ex, true);
        curl_close($ch);

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
        $return .= 'data-html="true" title="' . $tooltip . '">' . $name . '</a>';

        if (!is_preview()) {

            set_transient(md5('esoskills_' . serialize($atts)), $return, 3600);

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
