<?php

namespace Tests\Feature;

use App\Core\Enums\Users\UserStatusEnum;
use App\Core\Services\User\AccountDeletionService;
use App\Http\Controllers\Api\User\UserController;
use App\Models\Users\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/**
 * Xaridorlar ofertasi 3.4-band, Maxfiylik siyosati 7.1.3-band.
 */
class AccountDeletionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('social_users', function (Blueprint $table) {
            $table->id();
            foreach (['first_name', 'last_name', 'middle_name', 'current_address', 'description', 'phone', 'birth_date', 'file_name', 'external_picture'] as $column) {
                $table->string($column)->nullable();
            }
            $table->integer('file_id')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('login_type')->nullable();
            $table->unsignedBigInteger('social_user_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('status')->default(UserStatusEnum::_ACTIVE->value);
            $table->timestamps();
        });

        foreach (['socialite_logins', 'users_verify_mail_tokens', 'user_interests', 'notifications', 'authors', 'authority'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->timestamps();
            });
        }

        Schema::create('link_author_subscribers', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id');
            $table->integer('subscriber_id');
        });

        Schema::create('products_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('customer_id');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    private function user(string $email = 'ali@example.com'): User
    {
        $socialId = DB::table('social_users')->insertGetId([
            'first_name' => 'Ali', 'last_name' => 'Karimov', 'phone' => '+998901234567', 'birth_date' => '2000-01-01',
        ]);

        $user = new User();
        $user->email = $email;
        $user->username = $email;
        $user->social_user_id = $socialId;
        $user->status = UserStatusEnum::_ACTIVE->value;
        $user->save();

        foreach (['socialite_logins', 'users_verify_mail_tokens', 'user_interests', 'notifications'] as $name) {
            DB::table($name)->insert(['user_id' => $user->id]);
        }
        DB::table('link_author_subscribers')->insert(['author_id' => 99, 'subscriber_id' => $user->id]);
        DB::table('products_orders')->insert(['product_id' => 1, 'customer_id' => $user->id, 'enabled' => true]);

        return $user;
    }

    public function test_deletion_anonymizes_data_and_closes_access(): void
    {
        $user = $this->user();

        app(AccountDeletionService::class)->delete($user);

        $row = DB::table('users')->find($user->id);
        $this->assertSame(UserStatusEnum::_DELETED->value, (int) $row->status);
        $this->assertNull($row->email);
        $this->assertNull($row->username);

        $profile = DB::table('social_users')->find($user->social_user_id);
        $this->assertSame('Deleted', $profile->first_name);
        $this->assertNull($profile->phone);
        $this->assertNull($profile->birth_date);

        foreach (['socialite_logins', 'users_verify_mail_tokens', 'user_interests', 'notifications'] as $name) {
            $this->assertSame(0, DB::table($name)->count(), $name);
        }
        $this->assertSame(0, DB::table('link_author_subscribers')->count());

        // Buyurtma hisob uchun qoladi, kirish yopiladi.
        $this->assertSame(1, DB::table('products_orders')->count());
        $this->assertFalse((bool) DB::table('products_orders')->value('enabled'));
    }

    public function test_same_email_can_register_again_after_deletion(): void
    {
        $user = $this->user();
        app(AccountDeletionService::class)->delete($user);

        // email unique: o'chirilgan akkaunt yangi ro'yxatdan o'tishga to'sqinlik qilmaydi.
        $this->user();
        $this->assertSame(2, DB::table('users')->count());
    }

    public function test_seller_account_is_not_deleted_by_self_service(): void
    {
        $user = $this->user();
        DB::table('authors')->insert(['user_id' => $user->id]);
        Auth::setUser($user);

        try {
            app(UserController::class)->deleteAccount(
                Request::create('/api/user/delete-account', 'DELETE', ['confirm' => true]),
                app(AccountDeletionService::class)
            );
            $this->fail('Seller deletion must be refused');
        } catch (HttpException $e) {
            $this->assertSame(409, $e->getStatusCode());
        }

        $this->assertSame(UserStatusEnum::_ACTIVE->value, (int) DB::table('users')->value('status'));
    }

    public function test_route_requires_jwt(): void
    {
        $route = Route::getRoutes()->getByName('user.delete-account');

        $this->assertNotNull($route);
        $this->assertSame(['DELETE'], $route->methods());
        $this->assertContains('jwt.verify', $route->gatherMiddleware());
    }
}
