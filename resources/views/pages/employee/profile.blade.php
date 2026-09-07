@php use Illuminate\Support\Facades\Session;
 /**
    *@var \App\Models\Users\Employee $employee;
 **/
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
                                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">@lang('client.Account')</h1>
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
                                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                                    <img src="{{$image}}" alt="image" />
                                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                                </div>
                                            </div>
                                            <!--end::Pic-->
                                            <!--begin::Info-->
                                            <div class="flex-grow-1">
                                                <!--begin::Title-->
                                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                    <!--begin::User-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Name-->
                                                        <div class="d-flex align-items-center mb-2">
                                                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$employee->first_name.' '.$employee->last_name.' '.$employee->middle_name}}</a>
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
                                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="{{route('employee.profile')}}">@lang('client.Overview')</a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item mt-2">
                                                <a class="nav-link text-active-primary ms-0 me-10 py-5" href="{{route('employee.updateProfile')}}">@lang('client.Settings')</a>
                                            </li>
                                            <!--end::Nav item-->
                                            <li class="nav-item mt-2">
                                                <a class="nav-link text-active-primary ms-0 me-10 py-5"
                                                   href="{{route('employee.updateUser')}}">@lang('client.Username And Password')</a>
                                            </li>
                                        </ul>
                                        <!--begin::Navs-->
                                    </div>
                                </div>
                                <!--end::Navbar-->
                                <!--begin::details View-->
                                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                                    <!--begin::Card header-->
                                    <div class="card-header cursor-pointer">
                                        <!--begin::Card title-->
                                        <div class="card-title m-0">
                                            <h3 class="fw-bold m-0">@lang('client.Profile Details')</h3>
                                        </div>
                                        <!--end::Card title-->
                                        <!--begin::Action-->
                                        <a href="{{route('employee.updateProfile')}}" class="btn btn-sm btn-primary align-self-center">@lang('button.Edit Profile')</a>
                                        <!--end::Action-->
                                    </div>
                                    <!--begin::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body p-9">
                                        <!--begin::Row-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.Full Name')</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8">
                                                <span class="fw-bold fs-6 text-gray-800">{{$employee->first_name.' '.$employee->last_name.' '.$employee->middle_name}}</span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.birth_date')</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8 fv-row">
                                                <span class="fw-semibold text-gray-800 fs-6">{{$employee->birth_date}}</span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.Passport')
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active"></span></label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$employee->passport}}</span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.pin_fl')
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active"></span></label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$employee->pin_fl}}</span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.username')
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Phone number must be active"></span></label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8 d-flex align-items-center">
                                                <span class="fw-bold fs-6 text-gray-800 me-2">{{$employee->username}}</span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="row mb-7">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 fw-semibold text-muted">@lang('model.current_address')</label>
                                            <!--end::Label-->
                                            <!--begin::Col-->
                                            <div class="col-lg-8">
                                                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{$employee->current_address}}</a>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--end::Notice-->
                                    </div>
                                    <!--end::Card body-->
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
