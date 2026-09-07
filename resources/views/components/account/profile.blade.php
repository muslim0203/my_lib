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

    <div class="menu-item px-5">
        <a href="{{ route('auth.logout')  }}"
           class="menu-link px-5">{{ __('client.Logout') }}</a>
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
                                                                class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">@lang('lang.Russia')
                                                        <img class="w-15px h-15px rounded-1 ms-2"
                                                             src="{{ asset('assets/media/flags/russia.svg') }}"
                                                             alt=""/>
                                                        </span>
                                                    @endif
            </span>
        </a>
        <!--begin::Menu sub-->
        <div class="menu-sub menu-sub-dropdown w-230px py-4">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <a href="{{ route('locale', ['en']) }}"
                   class="menu-link d-flex px-5 {{ app()->currentLocale() === 'en' ? 'active' : '' }}">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1"
                                                             src="{{ asset('assets/media/flags/united-states.svg') }}"
                                                             alt=""/>
													</span>@lang('lang.English')</a>
            </div>
            <!--end::Menu item-->
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
													</span>@lang('lang.Russia')</a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
</div>
