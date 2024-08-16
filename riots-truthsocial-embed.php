<?php
/**
 * Plugin Name: Riot's TruthSocial Embed
 * Description: A plugin to embed Truth Social posts by URL.
 * Version: 1.2
 * Author: riotrequest
 */

// Register oEmbed handler for Truth Social
function riot_truthsocial_embed_handler() {
    wp_embed_register_handler(
        'truthsocial',
        '#https?://truthsocial\.com/@[^/]+/posts/[0-9]+#i',
        'riot_truthsocial_embed_handler_callback'
    );
}
add_action('init', 'riot_truthsocial_embed_handler');

// Callback function to handle the embed
function riot_truthsocial_embed_handler_callback($matches, $attr, $url, $rawattr) {
    // Correct the URL by removing '/posts/' from the URL path
    $embed_url = str_replace('/posts/', '/', esc_url($url));

    $embed_html = '<iframe class="truthsocial-embed" style="max-width: 100%; height: 300px; border: 0;" src="' . $embed_url . '/embed" width="600" allowfullscreen="allowfullscreen"></iframe>';
    $embed_html .= '<script src="https://truthsocial.com/embed.js" async="async"></script>';
    
    return apply_filters('embed_truthsocial', $embed_html, $matches, $attr, $url, $rawattr);
}

// Enqueue JavaScript to trigger oEmbed refresh
function riot_truthsocial_embed_enqueue_script() {
    // This script will force a refresh of the oEmbed when a URL is pasted
    echo "<script type='text/javascript'>
    jQuery(document).ready(function($) {
        $(document).on('input', '.editor-post-title__input, .block-editor-writing-flow', function() {
            wp.autosave.server.triggerSave();
        });
    });
    </script>";
}
add_action('admin_footer', 'riot_truthsocial_embed_enqueue_script');
