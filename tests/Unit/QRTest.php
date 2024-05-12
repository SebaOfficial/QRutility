<?php

use Bot\QR;
use Bot\Helper;

define("QR_PATH", Helper::getRandomFilePath('qr'));
define("QR_TEXT", "Hello World");

it("creates a QR Code as CURLFile", function () {
    $path = (new QR(QR_PATH))->generate(QR_TEXT);

    $this->assertTrue(file_exists($path));
    $this->assertTrue(is_readable($path));
});

it("reads a QR code", function () {
    $this->assertEquals((new QR(QR_PATH))->read(), QR_TEXT);
});

it("removes the QR code", function () {
    $this->assertTrue((new QR(QR_PATH))->unlink());
});
