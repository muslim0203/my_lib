<?php

namespace App\Models\Users;

use App\Models\Files\File;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $file_id
 * @property string|null $file_name
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $birth_date
 * @property string $passport
 * @property int $pin_fl
 * @property string $gender
 * @property string $current_address
 * @property int $status
 *
 * @property-read File|null $file
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'birth_date',
        'pin_fl',
        'passport',
        'gender',
        'current_address',
        'file_id',
        'file_name',
        'status'
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

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getMiddleName(): string
    {
        return $this->middle_name;
    }

    public function setMiddleName(string $middle_name): void
    {
        $this->middle_name = $middle_name;
    }

    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    public function setBirthDate(string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getPassport(): string
    {
        return $this->passport;
    }

    public function setPassport(string $passport): void
    {
        $this->passport = $passport;
    }

    public function getPinFl(): int
    {
        return $this->pin_fl;
    }

    public function setPinFl(int $pin_fl): void
    {
        $this->pin_fl = $pin_fl;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getCurrentAddress(): string
    {
        return $this->current_address;
    }

    public function setCurrentAddress(string $current_address): void
    {
        $this->current_address = $current_address;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return EmployeeFactory|Factory
     */
    protected static function newFactory(): EmployeeFactory|Factory
    {
        return EmployeeFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
