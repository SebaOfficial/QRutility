<?php

use Bot\QR;
use Bot\Helper;

define("QR_PATH", Helper::getRandomFilePath('qr'));
define("QR_TEXT", "Hello World");

it("creates a QR Code as CURLFile", function () {
    $file = (new QR(QR_PATH))->generate(QR_TEXT);

    $this->assertInstanceOf('CURLFile', $file);
    $this->assertTrue(file_exists($file->getFilename()));
    $this->assertTrue(is_readable($file->getFilename()));
});

it("reads a QR code", function () {
    $this->assertEquals((new QR(QR_PATH))->read(), QR_TEXT);
});

it("removes the QR code", function () {
    $this->assertTrue((new QR(QR_PATH))->unlink());
});
