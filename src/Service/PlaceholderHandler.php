<?php


class PlaceholderHandler
{
    private $placeholders = [];

    /**
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * @param array $placeholders
     * @return PlaceholderHandler
     */
    public function setPlaceholders($placeholders)
    {
        $this->placeholders = $placeholders;
        return $this;
    }

    /**
     * @param array $placeholders
     * @return PlaceholderHandler
     */
    public function addPlaceholder(string $placeholder)
    {
        if ('' !== $placeholder) {
            $this->placeholders[] = $placeholder;
        }

        return $this;
    }

    /**
     * @param string $placeholder
     * @param string $realValue
     * @param string $text
     *
     * @return string
     */
    public function handlePlaceholder(string $placeholder, string $realValue, string $text)
    {
        if (!strpos($text, $placeholder)) {
            return $text;
        }

        return str_replace($placeholder, $realValue, $text);
    }

    /**
     * @param array $placeholders
     * @param string $text
     *
     * @return string
     */
    public function handleAllPlaceholders(array $placeholders, string $text)
    {
        foreach ($placeholders as $placeholder => $realValue) {
            $text = $this->handlePlaceholder($placeholder, $realValue, $text);
        }

        return $text;
    }
}