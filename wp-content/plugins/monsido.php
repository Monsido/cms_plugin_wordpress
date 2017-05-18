<?php
   /*
   Plugin Name: Monsido
   Plugin URI: http://monsido.com
   Description: Append possible page urls to site html head tag
   Version: 1.0
   Author: Monsido
   Author URI: http://monsido.com
   License: GPL2
   */
   
    /**
     * Output Buffering
     *
     * Buffers the entire WP process, capturing the final output for manipulation.
     */
    
    ob_start();
    
    add_action('shutdown', function() {
        $final = '';
        // We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
        // that buffer's output into the final output.
        $levels = ob_get_level();
        
        for ($i = 0; $i < $levels; $i++)
        {
            $final .= ob_get_clean();
        }
        
        // Apply any filters to the final output
        echo apply_filters('final_output', $final);
    }, 0);
    
    add_filter('final_output', function($output) {
        global $wp;
        
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        
        $screen = get_current_screen();
        
        if ($screen->parent_base != 'edit') return $output;
        
        $current_url = get_permalink();
        
        return str_replace('<head>', "<head><!-- Monsido: public_urls['{$current_url}'] -->", $output);
    });
    