<?php

namespace Marcio1002\DiscordWebhook\Helpers;

class ValidatorHelper
{
    /**
     * Checks if the string is a URL
     *
     * @param string $uri
     * @return boolean
     */
    public static function isURL(string $uri): bool
    {
        return filter_var($uri, FILTER_VALIDATE_URL) ?  true : false;
    }

    /**
     * Check if the type image is valid
     * 
     * @param string $image
     * @return boolean
     */
    public static function isValidImage(string $image): bool
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, file_get_contents($image));
        finfo_close($finfo);


        return $mime != false && preg_match("/image\/(png|gif|jpg)/", $mime);
    }

    /**
     *  Checks if at least one element in the array is valid
     *
     * @param callback|\Closure $callback
     * @param array $array
     * @return boolean
     */
    public static function arraySome($callback, array $array): bool
    {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) return true;
        }

        return false;
    }

    /**
     *  Checks if all the elements in the array are valid
     * 
     * @param callback|\Closure $callback
     * @param array $array
     * @return boolean
     */
    public static function arrayEvery($callback, array $array): bool
    {
        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) return false;
        }

        return true;
    }
}