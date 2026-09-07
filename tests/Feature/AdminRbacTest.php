<?php

namespace Tests\Feature;

use App\Core\Enums\Users\UserStatusEnum;
use App\Models\Proverbs\Proverb;
use App\Models\Users\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * S2b (1): admin panelidagi RBAC.
 *
 * Ilgari barcha admin marshrutlari faqat `->middleware(['auth'])` bilan
 * himoyalangan edi va "admin" tushunchasi faqat `users.employee_id IS NOT
 * NULL` orqali ifodalangan. Natijada xodimga bog'langan istalgan
 * foydalanuvchi barcha o'chirish amallarini bajara olardi.
 *
 * Bu yerda `proverb.delete` misolida tekshiriladi, chunki `proverbs`
 * jadvali o'z-o'ziga yetarli (tashqi kalitlarsiz) va SQLite'da muammosiz
 * tuziladi. Boshqa bo'limlar aynan shu naqsh bo'yicha himoyalangan.
 *
 * Loyihaning to'liq migratsiya to'plami SQLite'da ishlamaydi (phpunit.xml
 * dagi izohga qarang), shuning uchun kerakli jadvallar shu yerda tuziladi.
 */
class AdminRbacTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('login_type')->nullable();
            $table->unsignedBigInteger('social_user_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('status')->default(UserStatusEnum::_ACTIVE->value);
            $table->timestamps();
        });

        Schema::create('proverbs', function (Blueprint $table) {
            $table->id();
            $table->text('author_oz');
            $table->text('author_uz');
            $table->text('author_ru');
            $table->text('content_oz');
            $table->text('content_uz');
            $table->text('content_ru');
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });

        // spatie jadvallarini haqiqiy migratsiya faylidan tuzamiz.
        (require base_path(
            'database/migrations/2024_04_01_111513_create_permission_tables.php'
        ))->up();

        $this->seed(PermissionSeeder::class);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function makeEmployeeUser(string $username, ?string $role = null): User
    {
        $user = new User();
        $user->fill([
            'username' => $username,
            'password' => 'secret-password-123',
            'email' => $username . '@example.test',
            'employee_id' => random_int(1000, 999999),
            'status' => UserStatusEnum::_ACTIVE->value,
        ]);
        $user->save();

        if ($role !== null) {
            $user->assignRole($role);
        }

        return $user->fresh();
    }

    private function makeProverb(): Proverb
    {
        return Proverb::query()->create([
            'author_oz' => 'a-oz',
            'author_uz' => 'a-uz',
            'author_ru' => 'a-ru',
            'content_oz' => 'c-oz',
            'content_uz' => 'c-uz',
            'content_ru' => 'c-ru',
            'enabled' => true,
        ]);
    }

    /**
     * Asosiy talab: past huquqli xodim (employee_id bor, `moderator` roli)
     * o'chirish amalidan RAD ETILADI, `admin` roli esa bajara oladi.
     */
    public function test_moderator_is_refused_delete_but_admin_is_allowed(): void
    {
        $moderator = $this->makeEmployeeUser('moderator1', PermissionSeeder::ROLE_MODERATOR);
        $admin = $this->makeEmployeeUser('admin1', PermissionSeeder::ROLE_ADMIN);

        $forModerator = $this->makeProverb();

        $this->actingAs($moderator)
            ->delete(route('proverb.delete', ['id' => $forModerator->getKey()]))
            ->assertStatus(403);

        $this->assertDatabaseHas('proverbs', ['id' => $forModerator->getKey()]);

        $forAdmin = $this->makeProverb();

        $this->actingAs($admin)
            ->delete(route('proverb.delete', ['id' => $forAdmin->getKey()]))
            ->assertSuccessful();

        $this->assertDatabaseMissing('proverbs', ['id' => $forAdmin->getKey()]);
    }

    /**
     * Moderator faqat o'chirishdan mahrum: ro'yxatni ko'rish huquqi bor.
     */
    public function test_moderator_keeps_read_and_moderation_abilities(): void
    {
        $moderator = $this->makeEmployeeUser('moderator2', PermissionSeeder::ROLE_MODERATOR);

        $this->assertTrue(Gate::forUser($moderator)->allows('proverb.view'));
        $this->assertTrue(Gate::forUser($moderator)->allows('request.view'));
        $this->assertTrue(Gate::forUser($moderator)->allows('request.moderate'));
        $this->assertTrue(Gate::forUser($moderator)->allows('report.view'));

        $this->assertFalse(Gate::forUser($moderator)->allows('proverb.delete'));
        $this->assertFalse(Gate::forUser($moderator)->allows('company.delete'));
        $this->assertFalse(Gate::forUser($moderator)->allows('enum.delete'));
        $this->assertFalse(Gate::forUser($moderator)->allows('employee.manage'));
    }

    /**
     * DIQQAT: `employee_id` ning o'zi hech qanday huquq bermaydi. Aynan shu
     * dastlabki xatolik edi va Gate::before uni takrorlamasligi kerak.
     */
    public function test_employee_id_alone_grants_nothing(): void
    {
        $roleless = $this->makeEmployeeUser('roleless');

        $this->assertNotNull($roleless->getEmployeeId());
        $this->assertFalse(Gate::forUser($roleless)->allows('proverb.view'));
        $this->assertFalse(Gate::forUser($roleless)->allows('proverb.delete'));

        $proverb = $this->makeProverb();

        $this->actingAs($roleless)
            ->delete(route('proverb.delete', ['id' => $proverb->getKey()]))
            ->assertStatus(403);
    }

    /**
     * Lockout oldini olish: RoleSeeder mavjud bazadagi roli yo'q
     * xodim-foydalanuvchilarga `admin` rolini beradi, roli borlarga esa
     * TEGMAYDI.
     */
    public function test_role_seeder_restores_access_for_existing_admins(): void
    {
        $legacy = $this->makeEmployeeUser('legacy-admin');
        $moderator = $this->makeEmployeeUser('moderator3', PermissionSeeder::ROLE_MODERATOR);

        $this->assertFalse(Gate::forUser($legacy)->allows('proverb.delete'));

        $this->seed(RoleSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $legacy = $legacy->fresh();
        $moderator = $moderator->fresh();

        $this->assertTrue($legacy->hasRole(PermissionSeeder::ROLE_ADMIN));
        $this->assertTrue(Gate::forUser($legacy)->allows('proverb.delete'));

        // Ataylab moderator qilinganlar admin bo'lib qolmaydi.
        $this->assertTrue($moderator->hasRole(PermissionSeeder::ROLE_MODERATOR));
        $this->assertFalse($moderator->hasRole(PermissionSeeder::ROLE_ADMIN));
        $this->assertFalse(Gate::forUser($moderator)->allows('proverb.delete'));
    }

    /**
     * PermissionSeeder idempotent bo'lishi shart: ikki marta ishga tushirish
     * dublikat yaratmaydi va xato bermaydi.
     */
    public function test_permission_seeder_is_idempotent(): void
    {
        $before = \Spatie\Permission\Models\Permission::query()->count();
        $rolesBefore = \Spatie\Permission\Models\Role::query()->count();

        $this->seed(PermissionSeeder::class);
        $this->seed(PermissionSeeder::class);

        $this->assertSame($before, \Spatie\Permission\Models\Permission::query()->count());
        $this->assertSame($rolesBefore, \Spatie\Permission\Models\Role::query()->count());

        // Eski API guard huquqlari saqlanib qolgan.
        foreach (['mail', 'google', 'phone'] as $guard) {
            $this->assertDatabaseHas('permissions', ['name' => 'user', 'guard_name' => $guard]);
            $this->assertDatabaseHas('permissions', ['name' => 'merchant', 'guard_name' => $guard]);
        }
        $this->assertDatabaseHas('permissions', ['name' => 'admin', 'guard_name' => 'web']);
    }

    /**
     * S2b (4): admin login uchun rate limit (`throttle:5,1`).
     * Ilgari parolni cheksiz marta taxmin qilish mumkin edi.
     */
    public function test_admin_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['username' => 'admin', 'password' => 'wrong'])
                ->assertStatus(302);
        }

        $this->post('/login', ['username' => 'admin', 'password' => 'wrong'])
            ->assertStatus(429);
    }

    /**
     * S2b (2): o'chirish marshruti endi GET emas, DELETE.
     * Eski `<img src="/proverb/delete/1">` hujumi ishlamaydi.
     */
    public function test_delete_route_is_no_longer_reachable_with_get(): void
    {
        $admin = $this->makeEmployeeUser('admin2', PermissionSeeder::ROLE_ADMIN);
        $proverb = $this->makeProverb();

        $this->actingAs($admin)
            ->get('/proverb/delete/' . $proverb->getKey())
            ->assertStatus(405);

        $this->assertDatabaseHas('proverbs', ['id' => $proverb->getKey()]);
    }
}
