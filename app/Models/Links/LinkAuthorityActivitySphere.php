<?php

namespace App\Models\Links;

use App\Core\Helpers\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $authority_id
 * @property int $sphere_id
 */
class LinkAuthorityActivitySphere extends Model
{
    use HasCompositePrimaryKey;

    public $timestamps = false;

    /**
     * Kompozit birlamchi kalit (HasCompositePrimaryKey trait).
     *
     * @var array<int, string>
     */
    protected $primaryKey = ['authority_id', 'sphere_id'];
    public $incrementing = false;

    protected $fillable = [
        'authority_id',
        'sphere_id'
    ];

    public function getAuthorityId(): int
    {
        return $this->authority_id;
    }

    public function setAuthorityId(int $authority_id): void
    {
        $this->authority_id = $authority_id;
    }

    public function getSphereId(): int
    {
        return $this->sphere_id;
    }

    public function setSphereId(int $sphere_id): void
    {
        $this->sphere_id = $sphere_id;
    }
}
