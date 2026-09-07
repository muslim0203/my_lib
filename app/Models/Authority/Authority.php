<?php

namespace App\Models\Authority;

use App\Models\Enums\EnumActivityType;
use App\Models\Files\File;
use App\Models\Steps\ProcessStep;
use App\Models\Users\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int|null $id
 * @property int|null $request_id
 * @property string $activity_type_id
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property integer $inn
 * @property string $account_number
 * @property string $email
 * @property string $address
 * @property string $phone
 * @property integer $user_id
 * @property integer $step_id
 * @property integer $certificate_file_id
 * @property string $certificate_file_name
 * @property integer $patent_file_id
 * @property string $patent_file_name
 * @property string $description
 * @property boolean $enabled
 * @property integer $status
 * @property integer|null $profile_file_id
 *
 * @property-read File $certificateFile
 * @property-read File $patentFile
 * @property-read ProcessStep $step
 * @property-read AuthorityFile $profileFile
 * @property-read EnumActivityType $activityType
 * @property-read Merchant $merchant
 */
class Authority extends Model
{
    protected $table = 'authority';

    protected $fillable = [
        'activity_type_id',
        'name_oz',
        'name_uz',
        'name_ru',
        'inn',
        'account_number',
        'email',
        'address',
        'phone',
        'certificate_file_id',
        'certificate_file_name',
        'enabled',
        'step_id',
        'user_id',
        'status',
        'profile_file_id',
        'description',
        'patent_file_id',
        'patent_file_name',
        'request_id'
    ];

    public function getRequestId(): ?int
    {
        return $this->request_id;
    }

    public function setRequestId(?int $request_id): void
    {
        $this->request_id = $request_id;
    }

    /**
     * @return BelongsTo
     */
    public function patentFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function getPatentFileId(): int
    {
        return $this->patent_file_id;
    }

    public function setPatentFileId(int $patent_file_id): void
    {
        $this->patent_file_id = $patent_file_id;
    }

    public function getPatentFileName()
    {
        return $this->patent_file_name;
    }

    public function setPatentFileName(string $patent_file_name): void
    {
        $this->patent_file_name = $patent_file_name;
    }

    public function getActivityTypeId(): string
    {
        return $this->activity_type_id;
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(EnumActivityType::class);
    }

    public function setActivityTypeId(string $activity_type_id): void
    {
        $this->activity_type_id = $activity_type_id;
    }

    /**
     * @return MorphOne
     */
    public function merchant(): MorphOne
    {
        return $this->morphOne(Merchant::class, 'model');
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return BelongsTo
     */
    public function certificateFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProcessStep::class);
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getProfileFileId(): int
    {
        return $this->profile_file_id;
    }

    public function setProfileFileId(?int $profile_file_id): void
    {
        $this->profile_file_id = $profile_file_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
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

    public function getInn(): int
    {
        return $this->inn;
    }

    public function setInn(int $inn): void
    {
        $this->inn = $inn;
    }

    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    public function setAccountNumber(string $account_number): void
    {
        $this->account_number = $account_number;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getStepId(): int
    {
        return $this->step_id;
    }

    public function setStepId(int $step_id): void
    {
        $this->step_id = $step_id;
    }

    public function getCertificateFileId(): int
    {
        return $this->certificate_file_id;
    }

    public function setCertificateFileId(int $certificate_file_id): void
    {
        $this->certificate_file_id = $certificate_file_id;
    }

    public function getCertificateFileName(): string
    {
        return $this->certificate_file_name;
    }

    public function setCertificateFileName(string $certificate_file_name): void
    {
        $this->certificate_file_name = $certificate_file_name;
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
     * @return BelongsTo
     */
    public function profileFile(): BelongsTo
    {
        return $this->belongsTo(AuthorityProfileFile::class);
    }
}
