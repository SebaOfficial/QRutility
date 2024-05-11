<?php

namespace Bot;

use Endroid\QrCode\ {
    Builder\Builder,
    Encoding\Encoding,
    ErrorCorrectionLevel,
    Writer\PngWriter,
    RoundBlockSizeMode,
};

use Zxing\QrReader;

class QR
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function generate(string $data): string
    {
        $res = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->validateResult(true)
            ->build()
        ;

        $res->saveToFile($this->path);

        return $this->path;
    }

    public function read(string $sourceType = QrReader::SOURCE_TYPE_FILE): string
    {
        return (new QrReader($this->path))->text();
    }

    public function getPath(): string {
        return $this->path;
    }

    public function unlink(): bool
    {
        return unlink($this->path);
    }
}
