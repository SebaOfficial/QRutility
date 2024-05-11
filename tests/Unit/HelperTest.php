<?php

use Bot\Helper;

it("creates a random file", function () {
    $path = Helper::getRandomFilePath();
    $this->assertTrue(file_exists($path));
    $this->assertTrue(is_writable($path));
    $this->assertTrue(is_readable($path));
    $this->assertTrue(unlink($path));
});
