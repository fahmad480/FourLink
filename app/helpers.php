<?php

if (!function_exists('system_param')) {
    /**
     * Get system parameter value by code
     *
     * @param string $code
     * @param mixed $default
     * @return mixed
     */
    function system_param($code, $default = null)
    {
        try {
            return \App\Models\SystemParameter::getValue($code, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('set_system_param')) {
    /**
     * Set system parameter value by code
     *
     * @param string $code
     * @param mixed $value
     * @return void
     */
    function set_system_param($code, $value)
    {
        try {
            \App\Models\SystemParameter::setValue($code, $value);
        } catch (\Exception $e) {
            // Ignore if table doesn't exist
        }
    }
}

if (!function_exists('app_url')) {
    /**
     * Get application URL based on system parameters
     *
     * @return string
     */
    function app_url()
    {
        try {
            $usePublicUrl = system_param('use_public_url', '0');
            
            if ($usePublicUrl === '1') {
                return system_param('public_url', config('app.url'));
            }
            
            return config('app.url');
        } catch (\Exception $e) {
            return config('app.url');
        }
    }
}

if (!function_exists('public_route')) {
    /**
     * Generate a URL to a named route using public_url if use_public_url is enabled
     *
     * @param string $name
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function public_route($name, $parameters = [], $absolute = true)
    {
        try {
            $usePublicUrl = system_param('use_public_url', '0');
            
            if ($usePublicUrl === '1') {
                $publicUrl = system_param('public_url');
                if ($publicUrl) {
                    $route = route($name, $parameters, false); // Get relative URL
                    return rtrim($publicUrl, '/') . $route;
                }
            }
            
            return route($name, $parameters, $absolute);
        } catch (\Exception $e) {
            return route($name, $parameters, $absolute);
        }
    }
}
