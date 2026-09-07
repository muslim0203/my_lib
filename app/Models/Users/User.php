<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Enums\Users\UserStatusEnum;
use App\Models\Links\LinkAuthorSubscribers;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string|null $username
 * @property string|null $password
 * @property string|null $email
 * @property string|null $phone
 * @property string $login_type
 * @property int|null $social_user_id
 * @property int|null $employee_id
 * @property int $status
 *
 * @property-read SocialUser|null $socialUser
 * @property-read SocialiteLogin|null $socialiteLogin
 * @property-read Employee|null $employee
 * @property-read UsersVerifyMailToken|null $verifyMailToken
 * @property-read Merchant|null $merchant
 * @property-read Collection $interests
 * @property-read LinkAuthorSubscribers[] $subscribers
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'phone',
        'login_type',
        'social_user_id',
        'employee_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * @return HasMany
     */
    public function interests(): HasMany
    {
        return $this->hasMany(UserInterest::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'id', 'user_id');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getLoginType(): string
    {
        return $this->login_type;
    }

    public function setLoginType(string $login_type): void
    {
        $this->login_type = $login_type;
    }

    public function getSocialUserId(): ?int
    {
        return $this->social_user_id;
    }

    public function setSocialUserId(?int $social_user_id): void
    {
        $this->social_user_id = $social_user_id;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employee_id;
    }

    public function setEmployeeId(?int $employee_id): void
    {
        $this->employee_id = $employee_id;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::_ACTIVE->value;
    }

    /**
     * @return bool
     */
    public function isUnConfirmed(): bool
    {
        return $this->status === UserStatusEnum::_UN_CONFIRMED->value;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->status === UserStatusEnum::_DELETED->value;
    }

    /**
     * @return bool
     */
    public function isEmptySocialUser(): bool
    {
        return empty($this->social_user_id);
    }

    /**
     * @return BelongsTo
     */
    public function socialUser(): BelongsTo
    {
        return $this->belongsTo(SocialUser::class);
    }

    /**
     * @return BelongsTo
     */
    public function socialiteLogin(): BelongsTo
    {
        return $this->belongsTo(SocialiteLogin::class, 'id', 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return BelongsTo
     */
    public function verifyMailToken(): BelongsTo
    {
        return $this->belongsTo(UsersVerifyMailToken::class, 'id', 'user_id');
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @return UserFactory|Factory
     */
    protected static function newFactory(): Factory|UserFactory
    {
        return UserFactory::new();
    }


    /**
     * @return HasMany
     */
    public function subscribers(): HasMany
    {
        return $this->hasMany(LinkAuthorSubscribers::class, 'author_id', 'id');
    }
}
