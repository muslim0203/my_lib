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
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5 active"
                                               href="{{route('employee.updateProfile')}}">@lang('client.Settings')</a>
                                        </li>
                                        <!--end::Nav item-->
                                        <!--begin::Nav item-->
                                        <li class="nav-item mt-2">
                                            <a class="nav-link text-active-primary ms-0 me-10 py-5"
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
                                <!--begin::Card header-->
                                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                                     data-bs-target="#kt_account_profile_details" aria-expanded="true"
                                     aria-controls="kt_account_profile_details">
                                    <!--begin::Card title-->
                                    <div class="card-title m-0">
                                        <h3 class="fw-bold m-0">@lang('client.Profile Details')</h3>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <!--begin::Card header-->
                                <!--begin::Content-->
                                <div id="kt_account_settings_profile_details" class="collapse show">
                                    <!--begin::Form-->
                                    <form id="kt_docs_repeater_form"
                                          action="{{ !empty($employee->employee_id) ? route('employee.editProfile', ['id' => $employee->employee_id]) : route('employee.store') }}"
                                          method="POST" class="form" enctype="multipart/form-data">
                                        @if(!empty($employee->employee_id))
                                            @method('PUT')
                                        @endif
                                        @csrf
                                        <!--begin::Card body-->
                                        <div class="card-body border-top p-9">
                                            <!--begin::Input group-->
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 col-form-label fw-semibold fs-6">@lang('model.Image')</label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-outline"
                                                         data-kt-image-input="true"
                                                         style="background-image:url('{{$image}}')">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-125px h-125px"
                                                             style="background-image:url('{{$image}}')"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            title="Change avatar">
                                                            <i class="ki-duotone ki-pencil fs-7">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!--begin::Inputs-->
                                                            <input type="file"
                                                                   id="file"
                                                                   name="file"/>
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                            title="Cancel avatar">
																	<i class="ki-duotone ki-cross fs-2">
																		<span class="path1"></span>
																		<span class="path2"></span>
																	</i>
																</span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            title="Remove avatar">
																	<i class="ki-duotone ki-cross fs-2">
																		<span class="path1"></span>
																		<span class="path2"></span>
																	</i>
																</span>
                                                        <!--end::Remove-->
                                                    </div>
                                                    <!--end::Image input-->
                                                    <!--begin::Hint-->
                                                    <div class="form-text">@lang('model.Allowed file types: png, jpg, jpeg')</div>
                                                    <!--end::Hint-->
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">@lang('model.Full Name')</label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <!--begin::Row-->
                                                    <div class="row">
                                                        <!--begin::Col-->
                                                        <div class="col-lg-4 fv-row">
                                                            <label class="required form-label" for="first_name">
                                                                @lang('model.first_name')
                                                            </label>
                                                            <input type="text" name="first_name"
                                                                   id="first_name"
                                                                   class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                                   placeholder="@lang('model.Enter first_name')"
                                                                   value="{{ !empty($employee->getId()) ? $employee->getFirstName() : old('first_name')  }}"/>
                                                            @if($errors->has('first_name'))
                                                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                                            @endif
                                                        </div>
                                                        <!--end::Col-->
                                                        <!--begin::Col-->
                                                        <div class="col-lg-4 fv-row">
                                                            <label class="required form-label" for="first_name">
                                                                @lang('model.last_name')
                                                            </label>
                                                            <input type="text"
                                                                   id="last_name"
                                                                   name="last_name"
                                                                   class="form-control form-control-lg form-control-solid"
                                                                   placeholder="@lang('model.Enter last_name')"
                                                                   value="{{ !empty($employee->getId()) ? $employee->getLastName() : old('last_name')  }}"/>
                                                            @if($errors->has('last_name'))
                                                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-4 fv-row">
                                                            <label class="required form-label" for="middle_name">
                                                                @lang('model.middle_name')
                                                            </label>
                                                            <input type="text"
                                                                   id="middle_name"
                                                                   name="middle_name"
                                                                   class="form-control form-control-lg form-control-solid"
                                                                   placeholder="@lang('model.Enter middle_name')"
                                                                   value="{{ !empty($employee->getId()) ? $employee->getMiddleName() : old('middle_name')  }}"/>
                                                            @if($errors->has('middle_name'))
                                                                <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                                            @endif
                                                        </div>
                                                        <!--end::Col-->
                                                    </div>
                                                    <!--end::Row-->
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->

                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 col-form-label fw-semibold fs-6 required form-label" for="birth_date">
                                                    @lang('model.birth_date')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="date"
                                                           id="birth_date"
                                                           name="birth_date"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.birth_date")"
                                                           value="{{ !empty($employee->getId()) ? $employee->getBirthDate() : old('birth_date')  }}"/>
                                                    @if($errors->has('birth_date'))
                                                        <span class="text-danger">{{ $errors->first('birth_date') }}</span>
                                                    @endif
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 required form-label" for="pin_fl">
                                                    @lang('model.pin_fl')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="pin_fl"
                                                           name="pin_fl"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.pin_fl")"
                                                           value="{{ !empty($employee->getId()) ? $employee->getPinfl() : old('pin_fl')  }}"/>
                                                    @if($errors->has('pin_fl'))
                                                        <span class="text-danger">{{ $errors->first('pin_fl') }}</span>
                                                    @endif
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 required form-label" for="passport">
                                                    @lang('model.passport')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="passport"
                                                           name="passport"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.passport")"
                                                           value="{{ !empty($employee->getId()) ? $employee->getPassport() : old('passport')  }}"/>
                                                    @if($errors->has('passport'))
                                                        <span class="text-danger">{{ $errors->first('passport') }}</span>
                                                    @endif
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <div class="row mb-6">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 required form-label" for="current_address">
                                                    @lang('model.current_address')
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <input type="text"
                                                           id="current_address"
                                                           name="current_address"
                                                           class="form-control form-control-lg form-control-solid"
                                                           placeholder="@lang("model.current_address")"
                                                           value="{{ !empty($employee->getId()) ? $employee->getCurrentAddress() : old('current_address') }}"/>
                                                    @if($errors->has('current_address'))
                                                        <span class="text-danger">{{ $errors->first('current_address') }}</span>
                                                    @endif
                                                </div>
                                                <!--end::Col-->
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
