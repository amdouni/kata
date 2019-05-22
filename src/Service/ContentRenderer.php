<?php

/**
 * Class ContentRenderer
 */
class ContentRenderer
{
    /**
     * @param string $content
     * @return string
     */
    public static function renderHtml($content)
    {
        return '<p>' . $content . '</p>';
    }


    /**
     * @param string $content
     * @return string
     */
    public static function renderText($content)
    {
        return (string) $content;
    }
}