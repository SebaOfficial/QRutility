<?php

use Bot\Helper;

it("loads environment variables", function () {
    Helper::loadDotEnv(ROOT_DIR);
    $this->assertTrue(isset($_ENV['BOT_TOKEN']));
});

it("creates a random file", function () {
    $path = Helper::getRandomFilePath();
    $this->assertTrue(file_exists($path));
    $this->assertTrue(is_writable($path));
    $this->assertTrue(is_readable($path));
    $this->assertTrue(unlink($path));
});

it("removes null values", function () {
    $this->assertTrue(count(Helper::arrayFilter([0, 1, null, 2])) === 3);
});
