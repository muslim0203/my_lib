<?php

namespace App\Models\Users;

use App\Core\Helpers\Mutators\BirthDateMutator;
use App\Models\Files\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $file_id
 * @property string|null $file_name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $current_address
 * @property string|null $description
 * @property string|null $birth_date
 * @property string|null $phone
 * @property string|null $external_picture
 *
 * @property-read File|null $file
 */
class SocialUser extends Model
{
    use HasFactory, BirthDateMutator;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'current_address',
        'description',
        'phone',
        'file_id',
        'file_name',
        'birth_date',
        'external_picture'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFileId(): ?int
    {
        return $this->file_id;
    }

    public function setFileId(?int $file_id): void
    {
        $this->file_id = $file_id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    public function setMiddleName(?string $middle_name): void
    {
        $this->middle_name = $middle_name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->middle_name;
    }

    public function getCurrentAddress(): ?string
    {
        return $this->current_address;
    }

    public function setCurrentAddress(?string $current_address): void
    {
        $this->current_address = $current_address;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    public function setBirthDate(?string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getExternalPicture(): ?string
    {
        return $this->external_picture;
    }

    public function setExternalPicture(?string $external_picture): void
    {
        $this->external_picture = $external_picture;
    }

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
