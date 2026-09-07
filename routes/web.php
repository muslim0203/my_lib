<?php

use App\Http\Controllers\Web\Author\AuthorController;
use App\Http\Controllers\Web\Authority\AuthorityController;
use App\Http\Controllers\Web\Company\CompanyController;
use App\Http\Controllers\Web\Company\CompanyFileController;
use App\Http\Controllers\Web\Company\CompanyPartnerController;
use App\Http\Controllers\Web\Company\CompanySocialNetworkController;
use App\Http\Controllers\Web\Employee\EmployeeController;
use App\Http\Controllers\Web\Enums\EnumAcademicDegreesController;
use App\Http\Controllers\Web\Enums\EnumAcademicPositionsController;
use App\Http\Controllers\Web\Enums\EnumCategoriesController;
use App\Http\Controllers\Web\Enums\EnumEducationTypesController;
use App\Http\Controllers\Web\Enums\EnumLanguagesController;
use App\Http\Controllers\Web\Enums\EnumProductGenresController;
use App\Http\Controllers\Web\Enums\EnumProductStatusController;
use App\Http\Controllers\Web\Enums\EnumProductTagsController;
use App\Http\Controllers\Web\Enums\EnumProductTypesController;
use App\Http\Controllers\Web\MainBanner\MainBannerController;
use App\Http\Controllers\Web\Product\ProductController;
use App\Http\Controllers\Web\Proverb\ProverbController;
use App\Http\Controllers\Web\Questions\QuestionAnswerController;
use App\Http\Controllers\Web\Questions\QuestionController;
use App\Http\Controllers\Web\Report\ReportController;
use App\Http\Controllers\Web\Request\RequestController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.dashboard');
})->middleware('auth');

Route::get('dashboard', function () {
    return view('pages.dashboard');
})->middleware('auth');

Route::controller(EmployeeController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('employee/profile', 'profile')
            ->name('employee.profile');

        Route::get('employee/update-profile', 'updateProfile')
            ->name('employee.updateProfile');

        Route::put('employee/edit-profile/{id}', 'editProfile')
            ->name('employee.editProfile');

        Route::get('employee/update-user', 'updateUser')
            ->name('employee.updateUser');

        Route::put('employee/edit-user/{id}', 'editUser')
            ->name('employee.editUser');
    });

Route::controller(EnumAcademicDegreesController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-academic-degree/create', 'create')
            ->name('enum-academic-degree.create');

        Route::post('enum-academic-degree/store', 'store')
            ->name('enum-academic-degree.store');

        Route::get('enum-academic-degree/filter', 'filter')
            ->name('enum-academic-degree.filter');

        Route::get('/enum-academic-degree/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-academic-degree.delete');

        Route::get('enum-academic-degree/view/{id}', 'view')
            ->name('enum-academic-degree.view');

        Route::get('enum-academic-degree/update/{id}', 'update')
            ->name('enum-academic-degree.update');

        Route::put('enum-academic-degree/edit/{id}', 'edit')
            ->name('enum-academic-degree.edit');
    });


Route::controller(EnumAcademicPositionsController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-academic-position/create', 'create')
            ->name('enum-academic-position.create');

        Route::post('enum-academic-position/store', 'store')
            ->name('enum-academic-position.store');

        Route::get('enum-academic-position/filter', 'filter')
            ->name('enum-academic-position.filter');

        Route::get('/enum-academic-position/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-academic-position.delete');

        Route::get('enum-academic-position/view/{id}', 'view')
            ->name('enum-academic-position.view');

        Route::get('enum-academic-position/update/{id}', 'update')
            ->name('enum-academic-position.update');

        Route::put('enum-academic-position/edit/{id}', 'edit')
            ->name('enum-academic-position.edit');
    });

// Enum categories
Route::controller(EnumCategoriesController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('enum-categories/filter', 'filter')
            ->name('enum-categories.filter');

        Route::get('/enum-categories/create', 'create')
            ->name('enum-categories.create');

        Route::get('/enum-categories/update/{id}', 'update')
            ->name('enum-categories.update');

        Route::get('/enum-categories/view/{id}', 'view')
            ->name('enum-categories.view');

        Route::post('/enum-categories/store', 'store')
            ->name('enum-categories.store');

        Route::put('/enum-categories/edit/{id}', 'edit')
            ->name('enum-categories.edit');

        Route::get('/enum-categories/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-categories.delete');
    });

Route::controller(EnumEducationTypesController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-education-type/create', 'create')
            ->name('enum-education-type.create');

        Route::post('enum-education-type/store', 'store')
            ->name('enum-education-type.store');

        Route::get('enum-education-type/filter', 'filter')
            ->name('enum-education-type.filter');

        Route::get('enum-education-type/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-education-type.delete');

        Route::get('enum-education-type/view/{id}', 'view')
            ->name('enum-education-type.view');

        Route::get('enum-education-type/update/{id}', 'update')
            ->name('enum-education-type.update');

        Route::put('enum-education-type/edit/{id}', 'edit')
            ->name('enum-education-type.edit');
    });

Route::controller(EnumLanguagesController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-language/create', 'create')
            ->name('enum-language.create');

        Route::post('enum-language/store', 'store')
            ->name('enum-language.store');

        Route::get('enum-language/filter', 'filter')
            ->name('enum-language.filter');

        Route::get('enum-language/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-language.delete');

        Route::get('enum-language/view/{id}', 'view')
            ->name('enum-language.view');

        Route::get('enum-language/update/{id}', 'update')
            ->name('enum-language.update');

        Route::put('enum-language/edit/{id}', 'edit')
            ->name('enum-language.edit');
    });

Route::controller(AuthorityController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('authority/filter', 'filter')
            ->name('authority.filter');

        Route::get('authority/view/{id}', 'view')
            ->name('authority.view');

        Route::post('authority/cancel/{id}', 'cancel')
            ->name('authority.cancel');

        Route::post('authority/confirm/{id}', 'confirm')
            ->name('authority.confirm');

    });

Route::controller(AuthorController::class)
    ->middleware('auth')
    ->group(function () {

        Route::get('author/filter', 'filter')
            ->name('author.filter');

        Route::get('author/view/{id}', 'view')
            ->name('author.view');

        Route::post('author/confirm/{id}', 'confirm')
            ->name('author.confirm');

        Route::post('author/cancel/{id}', 'cancel')
            ->name('author.cancel');

    });

Route::controller(EnumProductTagsController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-product-tag/create', 'create')
            ->name('enum-product-tag.create');

        Route::post('enum-product-tag/store', 'store')
            ->name('enum-product-tag.store');

        Route::get('enum-product-tag/filter', 'filter')
            ->name('enum-product-tag.filter');

        Route::get('enum-product-tag/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-product-tag.delete');

        Route::get('enum-product-tag/view/{id}', 'view')
            ->name('enum-product-tag.view');

        Route::get('enum-product-tag/update/{id}', 'update')
            ->name('enum-product-tag.update');

        Route::put('enum-product-tag/edit/{id}', 'edit')
            ->name('enum-product-tag.edit');
    });


Route::controller(EnumProductTypesController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-product-type/create', 'create')
            ->name('enum-product-type.create');

        Route::post('enum-product-type/store', 'store')
            ->name('enum-product-type.store');

        Route::get('enum-product-type/filter', 'filter')
            ->name('enum-product-type.filter');

        Route::get('enum-product-type/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-product-type.delete');

        Route::get('enum-product-type/view/{id}', 'view')
            ->name('enum-product-type.view');

        Route::get('enum-product-type/update/{id}', 'update')
            ->name('enum-product-type.update');

        Route::put('enum-product-type/edit/{id}', 'edit')
            ->name('enum-product-type.edit');
    });

Route::controller(EnumProductGenresController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-product-genre/create', 'create')
            ->name('enum-product-genre.create');

        Route::post('enum-product-genre/store', 'store')
            ->name('enum-product-genre.store');

        Route::get('enum-product-genre/filter', 'filter')
            ->name('enum-product-genre.filter');

        Route::get('enum-product-genre/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-product-genre.delete');

        Route::get('enum-product-genre/view/{id}', 'view')
            ->name('enum-product-genre.view');

        Route::get('enum-product-genre/update/{id}', 'update')
            ->name('enum-product-genre.update');

        Route::put('enum-product-genre/edit/{id}', 'edit')
            ->name('enum-product-genre.edit');
    });

Route::controller(EnumProductStatusController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('enum-product-status/create', 'create')
            ->name('enum-product-status.create');

        Route::post('enum-product-status/store', 'store')
            ->name('enum-product-status.store');

        Route::get('enum-product-status/filter', 'filter')
            ->name('enum-product-status.filter');

        Route::get('enum-product-status/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('enum-product-status.delete');

        Route::get('enum-product-status/view/{id}', 'view')
            ->name('enum-product-status.view');

        Route::get('enum-product-status/update/{id}', 'update')
            ->name('enum-product-status.update');

        Route::put('enum-product-status/edit/{id}', 'edit')
            ->name('enum-product-status.edit');
    });

Route::controller(RequestController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('request/filter/{type?}', 'filter')
            ->name('request.filter');

        Route::get('request/view/{id}/{type}', 'view')
            ->name('request.view');

        Route::post('request/reject/{id}', 'reject')
            ->name('request.reject');

        Route::post('request/confirm/{id}', 'confirm')
            ->name('request.confirm');

    });

Route::controller(CompanyController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('company/filter', 'filter')
            ->name('company.filter');

        Route::get('/company/create', 'create')
            ->name('company.create');

        Route::get('/company/update/{id}', 'update')
            ->name('company.update');

        Route::get('/company/view/{id}', 'view')
            ->name('company.view');

        Route::post('/company/store', 'store')
            ->name('company.store');

        Route::put('/company/edit/{id}', 'edit')
            ->name('company.edit');

        Route::get('/company/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('company.delete');
    });

Route::controller(CompanyFileController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('company-file/filter', 'filter')
            ->name('company-file.filter');

        Route::get('/company-file/create', 'create')
            ->name('company-file.create');

        Route::get('/company-file/update/{id}', 'update')
            ->name('company-file.update');

        Route::get('/company-file/view/{id}', 'view')
            ->name('company-file.view');

        Route::post('/company-file/store', 'store')
            ->name('company-file.store');

        Route::put('/company-file/edit/{id}', 'edit')
            ->name('company-file.edit');

        Route::get('/company-file/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('company-file.delete');
    });

Route::controller(CompanyPartnerController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('company-partner/filter', 'filter')
            ->name('company-partner.filter');

        Route::get('/company-partner/create', 'create')
            ->name('company-partner.create');

        Route::get('/company-partner/update/{id}', 'update')
            ->name('company-partner.update');

        Route::get('/company-partner/view/{id}', 'view')
            ->name('company-partner.view');

        Route::post('/company-partner/store', 'store')
            ->name('company-partner.store');

        Route::put('/company-partner/edit/{id}', 'edit')
            ->name('company-partner.edit');

        Route::get('/company-partner/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('company-partner.delete');
    });

Route::controller(CompanySocialNetworkController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('company-social-network/filter', 'filter')
            ->name('company-social-network.filter');

        Route::get('/company-social-network/create', 'create')
            ->name('company-social-network.create');

        Route::get('/company-social-network/update/{id}', 'update')
            ->name('company-social-network.update');

        Route::get('/company-social-network/view/{id}', 'view')
            ->name('company-social-network.view');

        Route::post('/company-social-network/store', 'store')
            ->name('company-social-network.store');

        Route::put('/company-social-network/edit/{id}', 'edit')
            ->name('company-social-network.edit');

        Route::get('/company-social-network/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('company-social-network.delete');
    });


Route::controller(QuestionController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('question/filter', 'filter')
            ->name('question.filter');

        Route::get('/question/create', 'create')
            ->name('question.create');

        Route::get('/question/update/{id}', 'update')
            ->name('question.update');

        Route::get('/question/view/{id}', 'view')
            ->name('question.view');

        Route::post('/question/store', 'store')
            ->name('question.store');

        Route::put('/question/edit/{id}', 'edit')
            ->name('question.edit');

        Route::get('/question/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('question.delete');
    });

Route::controller(QuestionAnswerController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('question-answer/filter', 'filter')
            ->name('question-answer.filter');

        Route::get('/question-answer/create', 'create')
            ->name('question-answer.create');

        Route::get('/question-answer/update/{id}', 'update')
            ->name('question-answer.update');

        Route::get('/question-answer/view/{id}', 'view')
            ->name('question-answer.view');

        Route::post('/question-answer/store', 'store')
            ->name('question-answer.store');

        Route::put('/question-answer/edit/{id}', 'edit')
            ->name('question-answer.edit');

        Route::get('/question-answer/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('question-answer.delete');
    });

Route::controller(ProductController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('product/filter', 'filter')
            ->name('product.filter');

        Route::get('product/delete/{id}', 'delete')
            ->name('product.delete');

        Route::get('product/view/{id}', 'view')
            ->name('product.view');
    });

Route::controller(MainBannerController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('main-banner/filter', 'filter')
            ->name('banner.filter');

        Route::get('/main-banner/create', 'create')
            ->name('banner.create');

        Route::get('/main-banner/update/{id}', 'update')
            ->name('banner.update');

        Route::get('/main-banner/view/{id}', 'view')
            ->name('banner.view');

        Route::post('/main-banner/store', 'store')
            ->name('banner.store');

        Route::put('/main-banner/edit/{id}', 'edit')
            ->name('banner.edit');

        Route::get('/main-banner/delete/{id}', 'destroy')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('banner.delete');
    });

Route::controller(ReportController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/report/user-register-search', 'userRegisterSearch')
            ->name('report.userRegisterSearch');

        Route::get('/report/product-order-search', 'productOrderSearch')
            ->name('report.productOrderSearch');

        Route::get('/report/top-buyers', 'topBuyers')
            ->name('report.topBuyers');

        Route::get('/report/top-products', 'topProducts')
            ->name('report.topProducts');
    });


Route::controller(ProverbController::class)
    ->middleware(['auth'])
    ->group(function () {

        Route::get('proverb/create', 'create')
            ->name('proverb.create');

        Route::post('proverb/store', 'store')
            ->name('proverb.store');

        Route::get('proverb/filter', 'filter')
            ->name('proverb.filter');

        Route::get('proverb/edit/{id}', 'edit')
            ->name('proverb.edit');

        Route::put('proverb/update/{id}', 'update')
            ->name('proverb.update');

        Route::get('proverb/delete/{id}', 'delete')
            ->name('proverb.delete');

        Route::get('proverb/view/{id}', 'view')
            ->name('proverb.view');
    });


Route::get('language/{locale?}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})
    ->middleware(['auth'])
    ->name('locale');

require __DIR__ . '/auth.php';


