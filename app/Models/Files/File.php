<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $file_name
 * @property string $original_name
 * @property string $hash
 * @property string $mime_type
 * @property string $extension
 * @property string $size
 * @property string $path
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'original_name',
        'hash',
        'mime_type',
        'extension',
        'size',
        'path',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFileName(): string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function getOriginalName(): string
    {
        return $this->original_name;
    }

    public function setOriginalName(string $original_name): void
    {
        $this->original_name = $original_name;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    public function setMimeType(string $mime_type): void
    {
        $this->mime_type = $mime_type;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->path . '/' . $this->file_name;
    }
}
