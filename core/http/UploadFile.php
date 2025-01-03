<?php

namespace Core\Http;

class UploadFile
{
    private string $name;
    private string $type;
    private string $tmpName;
    private int $error;
    private int $size;

    public function __construct(string $name, string $type, string $tmpName, int $error, int $size)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->error = $error;
        $this->size = $size;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function tmpName(): string
    {
        return $this->tmpName;
    }

    public function error(): int
    {
        return $this->error;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function extension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK;
    }

    public function move(string $path, string $name): bool
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return move_uploaded_file($this->tmpName, $path . $name);
    }
}

// EOF
