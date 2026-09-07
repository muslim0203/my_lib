<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Author\AuthorController;
use App\Http\Controllers\Api\Authority\AuthorityController;
use App\Http\Controllers\Api\Company\CompanyController;
use App\Http\Controllers\Api\Company\CompanyPartnerController;
use App\Http\Controllers\Api\Company\CompanySocialNetworkController;
use App\Http\Controllers\Api\Enums\AcademicDegreeController;
use App\Http\Controllers\Api\Enums\AcademicPositionController;
use App\Http\Controllers\Api\Enums\ActivityTypeController;
use App\Http\Controllers\Api\Enums\EducationTypeController;
use App\Http\Controllers\Api\Enums\EnumCategoriesController;
use App\Http\Controllers\Api\Enums\ProductGenreController;
use App\Http\Controllers\Api\Enums\ProductTagController;
use App\Http\Controllers\Api\Enums\ProductTypeController;
use App\Http\Controllers\Api\FileManager\FileManagerController;
use App\Http\Controllers\Api\FileManager\FileViewController;
use App\Http\Controllers\Api\Links\LinkAuthorSubscriberController;
use App\Http\Controllers\Api\MainBanner\MainBannerController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Pay\ClickController;
use App\Http\Controllers\Api\Pay\PaymeController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\ProductPriceTypeController;
use App\Http\Controllers\Api\Proverb\ProverbController;
use App\Http\Controllers\Api\Question\QuestionController;
use App\Http\Controllers\Api\Reports\ReportController;
use App\Http\Controllers\Api\Request\RequestController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)
    ->group(function () {

        // OTP chiqarish va tekshirish - spam, enumeratsiya va kodni
        // taxminlashning asosiy yo'llari. Bu yerda IP/qabul qiluvchi
        // bo'yicha cheklov qo'llaniladi.
        Route::post('auth/login-by-email', 'loginByEmail')
            ->middleware('throttle:otp-verify')
            ->name('auth.login-by-email');

        Route::post('auth/send-token-to-mail', 'sendTokenToMail')
            ->middleware('throttle:otp-send')
            ->name('auth.send-token-to-mail');

        Route::get('auth/login-by-google', 'loginByGoogle')
            ->name('auth.login-by-google');

        Route::get('auth/redirect-to-auth-by-google', 'redirectToAuthByGoogle')
            ->name('auth.redirect-auth-by-google');

        Route::post('auth/login-by-sms', 'loginBySms')
            ->name('auth.login-by-sms');

        Route::get('auth/refresh-token', 'refreshToken')
            ->name('auth.refresh-token');

    })
    ->middleware('guest');

// logout autentifikatsiyani talab qiladi, shuning uchun `guest`
// guruhida tura olmaydi: u yerda RedirectIfAuthenticated tokeni bor
// foydalanuvchini `/` ga yo'naltirib, marshrutga yetib bormas edi.
Route::controller(AuthController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::get('auth/logout', 'logout')
            ->name('auth.logout');

    });

Route::controller(UserController::class)
    ->middleware('jwt.verify')
    ->group(function () {

        Route::get('user/detail-list', 'list')
            ->name('user.detail-list');

        Route::put('user/edit', 'edit')
            ->name('user.edit');

        Route::post('user/select-interest', 'selectInterest')
            ->name('user.select-interest');

        Route::get('user/interest-list', 'interestList')
            ->name('user.interest-list');

    });

Route::controller(AuthorController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::post('author/show-profile', 'showProfile')
            ->withoutMiddleware(['jwt.verify'])
            ->name('author.showProfile');

        Route::post('author/comment-list/{id}', 'commentList')
            ->withoutMiddleware(['jwt.verify'])
            ->name('author.commentList');

        Route::post('author/comment/{id}', 'comment')
            ->name('author.comment');
    });

Route::controller(AuthorityController::class)
    ->middleware('jwt.verify')
    ->group(function () {

        Route::put('authority/edit/{id}', 'editRegister')
            ->name('authority.edit-register');

    });

Route::controller(NotificationController::class)
    ->middleware('jwt.verify')
    ->group(function () {

        Route::get('notification/list', 'list')
            ->name('notification.list');

    });

Route::controller(FileManagerController::class)
    ->middleware('jwt.verify')
    ->group(function () {

        Route::post('file/upload', 'upload')
            ->name('file.upload');

    });

Route::controller(EnumCategoriesController::class)
    ->group(function () {
        Route::get('/enum-categories/list', 'list')
            ->name('enumCategories.list');
    });

Route::controller(CompanyController::class)
    ->group(function () {
        Route::get('/company/view', 'view')
            ->name('company.view');
    });

Route::controller(CompanyPartnerController::class)
    ->group(function () {
        Route::get('/company-partner/view', 'view')
            ->name('company-partner.view');
    });

Route::controller(CompanySocialNetworkController::class)
    ->group(function () {
        Route::get('/company-social-network/view', 'view')
            ->name('company-social-network.view');
    });

Route::controller(QuestionController::class)
    ->group(function () {
        Route::get('/question/list', 'list')
            ->name('question.list');
    });

Route::controller(MainBannerController::class)
    ->group(function () {
        Route::get('/main-banner/list', 'list')
            ->name('mainBanner.list');
    });

Route::controller(ProductController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::get('product/list', 'list')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.list');

        Route::get('product/set-view-count/{id}', 'setViewCount')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.set-view-count');

        Route::get('product/view/{id}', 'view')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.view');

        Route::get('product/personal-list', 'personalList')
            ->name('product.personal-list');

        Route::get('product/personal-view/{id}', 'personalView')
            ->name('product.personal-view');

        Route::post('product/assessment/{id}', 'assessment')
            ->name('product.assessment');

        Route::post('product/comment/{id}', 'comment')
            ->name('product.comment');

        Route::get('product/comment-list/{id}', 'commentList')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.comment-list');

        Route::get('product/assessment-list/{id}', 'assessmentList')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.assessment-list');

        Route::post('product/author-product-list', 'authorProductList')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.authorProductList');

        Route::get('product/similar-product/{id}', 'similarProduct')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.similar-product');

        Route::post('product/favorite-list', 'favoriteList')
            ->withoutMiddleware(['jwt.verify'])
            ->name('product.favorite-list');

        // Bepul egallash order yaratadi, ya'ni holatni o'zgartiradi.
        // GET holatni o'zgartirmasligi kerak; eski GET havolasi endi
        // 405 qaytaradi va hech qanday order yaratmaydi.
        Route::post('product/buy/{id}', 'buy')
            ->name('product.buy');

        Route::get('product/my-list', 'myList')
            ->name('product.my-list');

        Route::delete('product/delete/{id}', 'delete')
            ->name('product.delete');

    });

Route::controller(RequestController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::get('request/status', 'status')
            ->name('request.status');

        Route::get('request/list', 'list')
            ->name('request.list');

        Route::get('request/view/{id}', 'view')
            ->name('request.view');

        Route::post('request/create-author/{id?}', 'createAuthor')
            ->name('request.create-author');

        Route::post('request/create-authority/{id?}', 'createAuthority')
            ->name('request.create-authority');

        Route::post('request/create-product/{id?}', 'createProduct')
            ->name('request.create-product');

    });

Route::controller(ProductPriceTypeController::class)
    ->group(function () {

        Route::get('product-price-type/list', 'list')
            ->name('product-price-type.list');

    });

Route::controller(ProductGenreController::class)
    ->group(function () {

        Route::get('product-genre/list', 'list')
            ->name('product-genre.list');

    });

Route::controller(ProductTypeController::class)
    ->group(function () {

        Route::get('product-type/list', 'list')
            ->name('product-type.list');

    });

Route::controller(ProductTagController::class)
    ->group(function () {

        Route::get('product-tag/list', 'list')
            ->name('product-tag.list');

    });

Route::controller(EducationTypeController::class)
    ->group(function () {

        Route::get('education-type/list', 'list')
            ->name('education-type.list');

    });

Route::controller(AcademicDegreeController::class)
    ->group(function () {

        Route::get('academic-degree/list', 'list')
            ->name('academic-degree.list');

    });

Route::controller(AcademicPositionController::class)
    ->group(function () {

        Route::get('academic-position/list', 'list')
            ->name('academic-position.list');

    });

Route::controller(LinkAuthorSubscriberController::class)
    ->group(function () {
        Route::post('link-author-subscriber/subscriber-count', 'subscriberCount')
            ->name('linkAuthorSubscriber.subscriberCount');

        Route::post('link-author-subscriber/subscribe', 'subscribe')
            ->name('linkAuthorSubscriber.subscribe');
    });

Route::controller(ActivityTypeController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::get('activity-type/list', 'list')
            ->name('activity-type.list');


    });

Route::controller(ProverbController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {
        Route::get('proverb/list', 'list')
            ->name('proverb.list');
    });

Route::controller(PaymeController::class)
    ->group(function () {

        Route::post('payme/pay', 'pay')
            ->middleware(['pay.payme'])
            ->name('payme.pay');


        Route::post('payme/check', 'check')
            ->name('payme.check');

        Route::post('payme/get-redirect-url', 'getRedirectUrl')
            ->middleware(['jwt.verify'])
            ->name('payme.get-redirect-url');

        Route::get('payme/generate-qr/{id}', 'generateQr')
            ->middleware(['jwt.verify'])
            ->name('payme.generate-qr');
    });

Route::controller(ClickController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::post('click/get-redirect-url', 'getRedirectUrl')
            ->name('click.get-redirect-url');

        Route::post('click/prepare-payment', 'preparePayment')
            ->withoutMiddleware(['jwt.verify'])
            ->name('click.prepare-payment');

        Route::post('click/complete-payment', 'completePayment')
            ->withoutMiddleware(['jwt.verify'])
            ->name('click.complete-payment');

        Route::post('click/generate-qr-code', 'generateQrCode')
            ->name('click.generate-qr-code');

    });

Route::controller(ReportController::class)
    ->middleware(['jwt.verify'])
    ->group(function () {

        Route::post('report/purchase-statistics', 'purchaseStatistics')
            ->name('report.purchase-statistics');

        Route::get('report/type-list', 'typeList')
            ->name('report.type-list');

        Route::get('report/pay-list', 'payList')
            ->name('report.pay-list');

    });

// Fayllar avtorizatsiyadan o'tgan yagona nuqta orqali beriladi.
// Eski implementatsiya yo'lni birlashtirar (path traversal) va
// User-Agent'dagi "Mozilla" so'zini avtorizatsiya deb qabul qilardi.
Route::get('file-view/{filename}', FileViewController::class)
    ->name('file-view');
