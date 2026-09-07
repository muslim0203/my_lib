<?php

namespace App\Models\Notifications\Enums;

use Database\Factories\NotificationTypeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property boolean $enabled
 */
class EnumNotificationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'enabled',
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNameOz(): string
    {
        return $this->name_oz;
    }

    public function setNameOz(string $name_oz): void
    {
        $this->name_oz = $name_oz;
    }

    public function getNameUz(): string
    {
        return $this->name_uz;
    }

    public function setNameUz(string $name_uz): void
    {
        $this->name_uz = $name_uz;
    }

    public function getNameRu(): string
    {
        return $this->name_ru;
    }

    public function setNameRu(string $name_ru): void
    {
        $this->name_ru = $name_ru;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return NotificationTypeFactory|Factory
     */
    protected static function newFactory(): Factory|NotificationTypeFactory
    {
        return NotificationTypeFactory::new();
    }
}
