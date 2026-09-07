@php
    use App\Models\Users\Employee;
    use Illuminate\Support\Facades\Session;
 /**
    * @var Employee|null $employee
    */
 $image = $employee->file_id ? $employee->image : asset('assets/media/avatars/blank.png');
@endphp
@extends('layouts.index')

@push('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
          type="text/css"/>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/user-management/users/list/table.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/user-management/users/list/add.js') }}"></script>
    <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
    <script src="{{ asset('assets/js/custom/authentication/sign-up/general.js')}}"></script>
    <script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
    <script>
        validationError('kt_docs_repeater_form', 'kt_docs_repeater_button');
    </script>
@endpush

@section("content")

    <div id="kt_app_content" class="app-content flex-column-fluid" data-select2-id="select2-data-kt_app_content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                                        Account</h1>
                                    <!--end::Title-->
                                    <!--begin::Breadcrumb-->
                                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                        <!--begin::Item-->
                                        <li class="breadcrumb-item text-muted">
                                            <a href="/" class="text-muted text-hover-primary">@lang('client.Home')</a>
                                        </li>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <li class="breadcrumb-item">
                                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                                        </li>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <li class="breadcrumb-item text-muted">@lang('client.Account')</li>
                                        <!--end::Item-->
                                    </ul>
                                    <!--end::Breadcrumb-->
                                </div>
                                <!--end::Page title-->
                                <!--begin::Actions-->
                                <!--end::Actions-->
                            </div>
                            <!--end::Toolbar container-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <!--begin::Navbar-->
                                <div class="card mb-5 mb-xl-10">
                                    <div class="card-body pt-9 pb-0">
                                        <!--begin::Details-->
                                        <div class="d-flex flex-wrap flex-sm-nowrap">
                                            <!--begin: Pic-->
                                            <div class="me-7 mb-4">
                                                <div
                                                    class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                    <img src="{{$image}}" alt="image"/>
                                                    <div
                                                        class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                                </div>
                                            </div>
                                            <!--end::Pic-->
                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <!--begin::Title-->
                                                <div
                                                    class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                    <!--begin::User-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Name-->
                                                        <div class="d-flex align-items-center mb-2">
                                                            <a href="#"
                                                               class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$employee->first_name.' '.$employee->last_name.' '.$employee->middle_name}}</a>
                                                            <a href="#">
                                                                <i class="ki-duotone ki-verify fs-1 text-primary">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </a>
                                                        </div>
                                                        <!--end::Name-->
                                                    </div>
                                                    <!--end::User-->
                                                </div>
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Navs-->
                                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                        <!--begin::Nav item-->
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                               href="{{route('employee.profile')}}">@lang('client.Overview')</a>
                                        </li>
                                        <!--end::Nav item-->
                                        <!--begin::Nav item-->
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                               href="{{route('employee.updateProfile')}}">@lang('client.Settings')</a>
                                        </li>
                                        <!--end::Nav item-->
                                        <!--begin::Nav item-->
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5 active"
                                               href="{{route('employee.updateUser')}}">@lang('client.Username And Password')</a>
                                        </li>
                                        <!--end::Nav item-->
                                    </ul>
                                    <!--begin::Navs-->
                                </div>
                            </div>
                            <!--end::Navbar-->
                            <!--begin::details View-->
                            <div class="card mb-5 mb-xl-10">
                                <div id="kt_account_settings_profile_details" class="collapse show">
                                    <!--begin::Form-->
                                    <form id="kt_docs_repeater_form"
                                          action="{{ !empty($employee->employee_id) ? route('employee.editUser', ['id' => $employee->employee_id]) : route('employee.store') }}"
                                          method="POST" class="form" enctype="multipart/form-data">
                                        @if(!empty($employee->employee_id))
                                            @method('PUT')
                                        @endif
                                        @csrf
                                        <!--begin::Card body-->
                                        <div class="card-body border-top p-9">
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 required form-label" for="email">
                                                    @lang('model.email')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="email"
                                                           name="email"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.email")"
                                                           value="{{ !empty($employee->getId()) ? $employee->email : old('email')  }}"/>
                                                    @if($errors->has('phone'))
                                                        <span class="text-danger">{{ $errors->first('email')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 form-label" for="phone">
                                                    @lang('model.phone')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="phone"
                                                           name="phone"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.phone")"
                                                           value="{{ !empty($employee->getId()) ? $employee->phone : old('phone')  }}"/>
                                                    @if($errors->has('phone'))
                                                        <span class="text-danger">{{ $errors->first('phone')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 required form-label" for="username">
                                                    @lang('model.username')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="username"
                                                           name="username"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.username")"
                                                           value="{{ !empty($employee->getId()) ? $employee->username : old('username')  }}"/>
                                                    @if($errors->has('username'))
                                                        <span class="text-danger">{{ $errors->first('username')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-6">
                                                <label class="col-lg-4 required form-label" for="password">
                                                    @lang('model.password')
                                                </label>
                                                <div class="col-lg-8 fv-row">
                                                    <input class="form-control form-control-lg form-control-solid"
                                                           type="password"
                                                           placeholder="@lang('model.password')"
                                                           id="password"
                                                           name="password"
                                                           autocomplete="off"
                                                    />
                                                    @if($errors->has('password'))
                                                        <span class="text-danger">{{ $errors->first('password')}}</span>
                                                    @endif
                                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                                <i class="ki-duotone ki-eye-slash fs-2"></i>
                                                                <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                                            </span>
                                                    <div class="text-muted">@lang('client.Use 6 or more characters with a mix of letters, numbers & symbols')</div>
                                                </div>
                                            </div>
                                            <div class="row mb-6">
                                                <label class="required col-lg-4 form-label" for="confirm_password">
                                                    @lang('model.reset password')
                                                </label>
                                                <div class="col-lg-8 fv-row">
                                                    <input class="form-control form-control-lg form-control-solid"
                                                           id="confirm_password"
                                                           name="confirm_password"
                                                           type="password" autocomplete="off"
                                                           placeholder="@lang('model.reset password')"
                                                    />
                                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                                <i class="ki-duotone ki-eye-slash fs-2"></i>
                                                                <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                                            </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!--end::Card body-->
                                        <!--begin::Actions-->
                                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                                            <a class="btn btn-light btn-active-light-primary me-2" href="{{route('employee.profile')}}">@lang('button.Discard')</a>
                                            <button type="submit" class="btn btn-primary"
                                                    id="kt_docs_repeater_button">@lang('button.Save')
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                        <!--end::Content container-->
                    </div>
                    <!--end::Content-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Content container-->
    </div>

@stop
