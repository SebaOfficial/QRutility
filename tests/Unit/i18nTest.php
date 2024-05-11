<?php

use Bot\i18n;
use Bot\Exception\i18nException;

it("loads an existing locale", function () {
    $this->assertTrue(!empty((new i18n(ROOT_DIR . "/locale/", "en"))->load()));
});

it("follows the fallback when the language doesn't exist", function () {
    $this->assertTrue(!empty((new i18n(ROOT_DIR . "/locale/", "non-existing-language", "en"))->load()));
});

it("throws an exception when no fallback is provided", function () {
    (new i18n(ROOT_DIR . "/locale/", "non-existing-language"))->load();
})->throws(i18nException::class);
