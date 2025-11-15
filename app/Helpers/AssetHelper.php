<?php

if (!function_exists('secure_asset')) {
    /**
     * Generate a secure asset URL (force HTTPS)
     * 
     * @param string $path
     * @return string
     */
    function secure_asset($path)
    {
        // Get the asset URL
        $url = asset($path);
        
        // Force HTTPS if not already
        if (strpos($url, 'http://') === 0) {
            $url = str_replace('http://', 'https://', $url);
        }
        
        return $url;
    }
}

