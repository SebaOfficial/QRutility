<?php

namespace Bot;

use Bot\Exception\i18nException;

class i18n
{
    private string $path;
    private string $lang;
    private ?string $fallback;
    private array $translations;

    public function __construct(string $path, string $lang, ?string $fallback = null, array $translations = [])
    {
        $this->path = $path;
        $this->lang = $lang;
        $this->fallback = $fallback;
        $this->translations = $translations;
    }

    public function setFallback(string $fallback)
    {
        $this->fallback = $fallback;
    }

    public function appendTranslations(array $translations)
    {
        $this->translations = array_merge($this->translations, $translations);
    }

    public function load(): object
    {
        return json_decode(strtr(file_get_contents(
            $this->path .
                (
                    file_exists($this->path . $this->lang . '.json') ?
                        $this->lang :
                        ($this->fallback ?: throw new i18nException('You must specify the fallback or load another language'))
                ) .
            '.json'
        ), $this->translations), false);
    }
}
