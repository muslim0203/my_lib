<?php

namespace App\Models\Enums;

use Database\Factories\EnumEducationTypeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $enabled
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class EnumEducationType extends Model
{
    use HasFactory;

    protected $table = 'enum_education_types';

    protected $fillable = [
        'name_oz',
        'name_uz',
        'name_ru',
        'enabled',
    ];

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
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

    /**
     * @return Factory|EnumEducationTypeFactory
     */
    protected static function newFactory(): Factory|EnumEducationTypeFactory
    {
        return EnumEducationTypeFactory::new();
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}
