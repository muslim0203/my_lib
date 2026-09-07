<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title_oz
 * @property string $title_uz
 * @property string $title_ru
 * @property boolean $enabled
 * @property boolean $is_show_front
 */
class ReportType extends Model
{
    protected $fillable = [
        'title_oz',
        'title_uz',
        'title_ru',
        'enabled',
        'is_show_front'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitleOz(): string
    {
        return $this->title_oz;
    }

    public function setTitleOz(string $title_oz): void
    {
        $this->title_oz = $title_oz;
    }

    public function getTitleUz(): string
    {
        return $this->title_uz;
    }

    public function setTitleUz(string $title_uz): void
    {
        $this->title_uz = $title_uz;
    }

    public function getTitleRu(): string
    {
        return $this->title_ru;
    }

    public function setTitleRu(string $title_ru): void
    {
        $this->title_ru = $title_ru;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
