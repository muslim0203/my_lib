<?php

namespace App\Models\Authors;

use App\Models\Enums\EnumAcademicDegree;
use App\Models\Enums\EnumAcademicPosition;
use App\Models\Enums\EnumEducationType;
use App\Models\Files\File;
use App\Models\Steps\ProcessStep;
use App\Models\Users\Merchant;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int|null $id
 * @property int|null $request_id
 * @property int $user_id
 * @property int $academic_degree_id
 * @property int $academic_position_id
 * @property int $education_type_id
 * @property int $pin_fl
 * @property boolean $enabled
 * @property string|null $work_place
 * @property string|null $position
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $description
 * @property string $gender
 * @property string $passport
 * @property string $email
 * @property string $phone
 * @property integer $step_id
 * @property integer $status
 * @property integer $profile_file_id
 * @property integer $diploma_file_id
 * @property string $diploma_file_name
 * @property integer $licence_file_id
 * @property string $licence_file_name
 * @property string $account_number
 * @property string $birthdate
 * @property string $passport_given_date
 * @property string $passport_given_place
 * @property string|null $inn
 *
 * @property-read User $user
 * @property-read EnumAcademicPosition $academicPosition
 * @property-read EnumAcademicDegree $academicDegree
 * @property-read EnumEducationType $educationType
 * @property-read ProcessStep $step
 * @property-read AuthorProfileFile $profileFile
 * @property-read File $extraFiles
 * @property-read File $diplomaFile
 * @property-read File $licenceFile
 * @property-read Merchant $merchant
 */
class Author extends Model
{
    protected $fillable = [
        'request_id',
        'inn',
        'birthdate',
        'passport_given_date',
        'passport_given_place',
        'step_id',
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'passport',
        'pin_fl',
        'email',
        'phone',
        'academic_degree_id',
        'academic_position_id',
        'education_type_id',
        'description',
        'enabled',
        'status',
        'profile_file_id',
        'diploma_file_id',
        'diploma_file_name',
        'licence_file_id',
        'licence_file_name',
        'account_number',
        'work_place',
        'position'
    ];

    public function getRequestId(): ?int
    {
        return $this->request_id;
    }

    public function setRequestId(?int $request_id): void
    {
        $this->request_id = $request_id;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getPassportGivenDate(): string
    {
        return $this->passport_given_date;
    }

    public function setPassportGivenDate(string $passport_given_date): void
    {
        $this->passport_given_date = $passport_given_date;
    }

    public function getPassportGivenPlace(): string
    {
        return $this->passport_given_place;
    }

    public function setPassportGivenPlace(string $passport_given_place): void
    {
        $this->passport_given_place = $passport_given_place;
    }

    /**
     * @return MorphOne
     */
    public function merchant(): MorphOne
    {
        return $this->morphOne(Merchant::class, 'model');
    }

    /**
     * @return BelongsTo
     */
    public function diplomaFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function licenceFile(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * @return BelongsTo
     */
    public function profileFile(): BelongsTo
    {
        return $this->belongsTo(AuthorProfileFile::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function academicDegree(): BelongsTo
    {
        return $this->belongsTo(EnumAcademicDegree::class);
    }

    /**
     * @return BelongsTo
     */
    public function academicPosition(): BelongsTo
    {
        return $this->belongsTo(EnumAcademicPosition::class);
    }

    /**
     * @return BelongsTo
     */
    public function educationType(): BelongsTo
    {
        return $this->belongsTo(EnumEducationType::class);
    }

    /**
     * @return BelongsTo
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProcessStep::class);
    }

    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    public function setAccountNumber(string $account_number): void
    {
        $this->account_number = $account_number;
    }

    public function getId(): ?int
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

    public function getAcademicDegreeId(): int
    {
        return $this->academic_degree_id;
    }

    public function setAcademicDegreeId(int $academic_degree_id): void
    {
        $this->academic_degree_id = $academic_degree_id;
    }

    public function getAcademicPositionId(): int
    {
        return $this->academic_position_id;
    }

    public function setAcademicPositionId(int $academic_position_id): void
    {
        $this->academic_position_id = $academic_position_id;
    }

    public function getEducationTypeId(): int
    {
        return $this->education_type_id;
    }

    public function setEducationTypeId(int $education_type_id): void
    {
        $this->education_type_id = $education_type_id;
    }

    public function getPinFl(): int
    {
        return $this->pin_fl;
    }

    public function setPinFl(int $pin_fl): void
    {
        $this->pin_fl = $pin_fl;
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
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName() . ' ' . $this->getMiddleName();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getPassport(): string
    {
        return $this->passport;
    }

    public function setPassport(string $passport): void
    {
        $this->passport = $passport;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    public function setProfileFileId(int $profile_file_id): void
    {
        $this->profile_file_id = $profile_file_id;
    }

    public function getDiplomaFileId(): int
    {
        return $this->diploma_file_id;
    }

    public function setDiplomaFileId(int $diploma_file_id): void
    {
        $this->diploma_file_id = $diploma_file_id;
    }

    public function getDiplomaFileName(): string
    {
        return $this->diploma_file_name;
    }

    public function setDiplomaFileName(string $diploma_file_name): void
    {
        $this->diploma_file_name = $diploma_file_name;
    }

    public function getLicenceFileId(): int
    {
        return $this->licence_file_id;
    }

    public function setLicenceFileId(int $licence_file_id): void
    {
        $this->licence_file_id = $licence_file_id;
    }

    public function getLicenceFileName(): string
    {
        return $this->licence_file_name;
    }

    public function setLicenceFileName(string $licence_file_name): void
    {
        $this->licence_file_name = $licence_file_name;
    }

    public function getWorkPlace(): ?string
    {
        return $this->work_place;
    }

    public function setWorkPlace(?string $work_place): void
    {
        $this->work_place = $work_place;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return BelongsToMany
     */
    public function extraFiles(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'link_author_files', 'author_id', 'file_id');
    }
}
