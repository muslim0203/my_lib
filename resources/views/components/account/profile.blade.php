@php
    use App\Core\Enums\Auth\LoginTypeEnum;
    use App\Models\Users\User;
    use Illuminate\Support\Facades\Auth;

    /**
    * @var User $user
    */
    $user = Auth::user();
    $firstName = '';
    $birthDate = '';
    $img = '';

    if ($user->getLoginType() === LoginTypeEnum::_LOGIN_LOGIN_PASS->value) {
        $firstName = $user->employee?->getFirstName();
        $birthDate = $user->employee?->getBirthDate();
        $img = $user->employee?->file?->getSrc() ?? asset('assets/media/avatars/blank.png');
    }


@endphp

<div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">
            <!--begin::Avatar-->
            <div class="symbol symbol-50px me-5">
                <img alt="Logo" src="{{ $img }}"/>
            </div>
            <!--end::Avatar-->
            <!--begin::Username-->
            <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5">{{ $firstName }}
                    <span
                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">@lang('client.Online')</span>
                </div>
                <p class="fw-semibold text-muted text-hover-primary fs-7">{{ $birthDate }}</p>
            </div>
            <!--end::Username-->
        </div>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu separator-->
    <div class="separator my-2"></div>
    <!--end::Menu separator-->
    <!--begin::Menu item-->
    <div class="menu-item px-5">
        <a href="{{ route('employee.profile') }}" class="menu-link px-5">{{ __('client.My Profile') }}</a>
    </div>
    <!--end::Menu item-->

    {{--
        Logout endi POST: GET bo'lganida istalgan saytdagi <img src="/logout">
        foydalanuvchini tizimdan chiqarib yuborardi (Laravel GET so'rovlarda
        CSRF tokenni umuman tekshirmaydi). Marshrut routes/auth.php da
        Route::post('logout', ...) ga o'zgartirildi.
    --}}
    <div class="menu-item px-5">
        <form method="POST" action="{{ route('auth.logout') }}" class="m-0">
            @csrf
            <button type="submit"
                    class="menu-link px-5 btn btn-link text-start w-100 border-0 bg-transparent shadow-none">
                {{ __('client.Logout') }}
            </button>
        </form>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
         data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
        <a href="#" class="menu-link px-5">
												<span class="menu-title position-relative">@lang('lang.Language')

                                                    @if(app()->currentLocale() === 'en')
                                                        <span
                                                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">@lang('lang.English')
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/united-states.svg') }}"
                                                             alt=""/></span>
                                                    @elseif(app()->currentLocale() === 'uz')
                                                        <span
                                                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">@lang('lang.Uzbek Krill')
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/uzbekistan.svg') }}"
                                                             alt=""/>
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/russia.svg') }}"
                                                             alt=""/></span>
                                                    @elseif(app()->currentLocale() === 'oz')
                                                        <span
                                                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">@lang('lang.Uzbek Latin')
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/uzbekistan.svg') }}"
                                                             alt=""/></span>
                                                    @elseif(app()->currentLocale() === 'ru')
                                                        <span
                                                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">@lang('lang.Russian')
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/russia.svg') }}"
                                                             alt=""/>
                                                        </span>
                                                    @endif
            </span>
        </a>
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-dropdown w-230px py-4">
            {{--
                `en` bandi olib tashlandi: App\Core\Enums\LanguageEnum faqat
                oz/uz/ru ni biladi va LanguageHelper SQL ustun nomini shu
                ro'yxatdan yasaydi (title_en / name_en ustunlari bazada yo'q).
                Til kodi endi qat'iy oq ro'yxat bo'yicha tekshirilgani uchun
                `en` havolasi hech nima qilmaydigan "o'lik" tugma bo'lib
                qolardi. Ingliz tilini qaytarish uchun avval bazaga *_en
                ustunlari va LanguageEnum ga _EN kerak.
            --}}
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="{{ route('locale', ['oz']) }}"
                   class="menu-link d-flex px-5 {{ app()->currentLocale() === 'oz' ? 'active' : '' }}">
                <span class="symbol symbol-20px me-4">
														<img class="rounded-1"
                                                             src="{{ asset('assets/media/flags/uzbekistan.svg') }}"
                                                             alt=""/>
													</span>@lang('lang.Uzbek Latin')</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="{{ route('locale', ['uz']) }}"
                   class="menu-link d-flex px-5 {{ app()->currentLocale() === 'uz' ? 'active' : '' }}">
                <span class="symbol symbol-20px me-4">
														<img class="rounded-1"
                                                             src="{{ asset('assets/media/flags/uzbekistan.svg') }}"
                                                             alt=""/>
                                                        <img class="rounded-1"
                                                             src="{{ asset('assets/media/flags/russia.svg') }}" alt=""/>
													</span>@lang('lang.Uzbek Krill')</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="{{ route('locale', ['ru']) }}"
                   class="menu-link d-flex px-5 {{ app()->currentLocale() === 'ru' ? 'active' : '' }}">
                <span class="symbol symbol-20px me-4">
														<img class="rounded-1"
                                                             src="{{ asset('assets/media/flags/russia.svg') }}" alt=""/>
													</span>@lang('lang.Russian')</a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
</div>
