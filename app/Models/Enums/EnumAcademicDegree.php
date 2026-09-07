<?php

namespace App\Models\Enums;

use Database\Factories\EnumAcademicDegreeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int|null $id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property boolean $enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class EnumAcademicDegree extends Model
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
     * @return EnumAcademicDegreeFactory|Factory
     */
    protected static function newFactory(): Factory|EnumAcademicDegreeFactory
    {
        return EnumAcademicDegreeFactory::new();
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
