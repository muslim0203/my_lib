@php use App\Core\Helpers\Lang\LanguageHelper;
     use App\Models\Company\CompanySocialNetwork;
@endphp
@php
    /**
    * @var CompanySocialNetwork|null $model
    */
    $name = LanguageHelper::getName();
@endphp

@extends('layouts.index')

@push('script')
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
    <script>
        validationError('kt_docs_repeater_form', 'kt_docs_repeater_button');
    </script>
@endpush

@section('content')

    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @lang('client.Company Social Network')
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('dashboard') }}"
                           class="text-muted text-hover-primary">@lang('breadcrumb.Home')</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('company-social-network.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Company Social Network')
                        </a>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->

                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        @if(!empty($model->getId()))
                            <strong class="text-primary">@lang('breadcrumb.Company Social Network Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Company Social Network Create')</strong>
                        @endif
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <div id="kt_app_content" class="app-content flex-column-fluid" data-select2-id="select2-data-kt_app_content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card" style="min-height: 700px!important;">
                <!--begin::Card body-->
                <div class="card-body py-4 table-responsive">
                    <!--begin::Form-->
                    <form id="kt_docs_repeater_form"
                          action="{{ !empty($model->getId()) ? route('company-social-network.edit', ['id' => $model->getId()]) : route('company-social-network.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="md-10">
                            <div class="form-group">
                                <label for="company_partner_company_id" class="required form-label">
                                    @lang('model.company_id')
                                </label>
                                <select name="company_id" id="company_id"
                                        class="form-select">
                                    <option selected value="">@lang('model.Open this select')</option>
                                    @foreach($companies as $company)
                                        <option
                                            {{old('company_id') ?? $company->id == $model->company_id ? "selected" : ""}} value="{{ $company->id }}">{{ $company->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('company_id'))
                                    <span class="text-danger">{{ $errors->first('company_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="required form-label" for="company_social_network_name_oz">
                                @lang('model.name_oz')
                            </label>
                            <input
                                id="company_social_network_name_oz"
                                type="text"
                                name="name_oz"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                value="{{ !empty($model->getId()) ? $model->getNameOz() : old('name_oz') }}"
                                placeholder="@lang('model.Enter name_oz')"
                            />
                            @if($errors->has('name_oz'))
                                <span class="text-danger">{{ $errors->first('name_oz') }}</span>
                            @endif
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="required form-label" for="company_social_network_name_uz">
                                @lang('model.name_uz')
                            </label>
                            <input
                                id="company_social_network_name_oz"
                                type="text"
                                name="name_uz"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                value="{{ !empty($model->getId()) ? $model->getNameUz() : old('name_uz')  }}"
                                placeholder="@lang('model.Enter name_uz')"
                            />
                            @if($errors->has('name_uz'))
                                <span class="text-danger">{{ $errors->first('name_uz') }}</span>
                            @endif
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="required form-label" for="company_social_network_name_ru">
                                @lang('model.name_ru')
                            </label>
                            <input
                                id="company_social_network_name_ru"
                                type="text"
                                name="name_ru"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                value="{{ !empty($model->getId()) ? $model->getNameRu() : old('name_ru') }}"
                                placeholder="@lang('model.Enter name_ru')"
                            />
                            @if($errors->has('name_ru'))
                                <span class="text-danger">{{ $errors->first('name_ru') }}</span>
                            @endif
                        </div>
                        <div class="mb-10">
                            <label class="required form-label" for="company_social_network_link">
                                @lang('model.link')
                            </label>
                            <input
                                id="company_social_network_link"
                                type="text"
                                name="link"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                value="{{ !empty($model->getId()) ? $model->getNameRu() : old('link') }}"
                                placeholder="@lang('model.Enter link')"
                            />
                            @if($errors->has('link'))
                                <span class="text-danger">{{ $errors->first('link') }}</span>
                            @endif
                        </div>
                        <div class="mb-10">
                            <label class="required form-label" for="company_social_network_file">
                                @lang('model.file')
                            </label>
                            <input
                                id="company_social_network_file"
                                type="file"
                                name="file"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-sm"
                                placeholder="@lang('model.Enter file')"
                            />
                            @if($errors->has('file'))
                                <span class="text-danger">{{ $errors->first('file') }}</span>
                            @endif
                        </div>
                        <div class="mb-10">
                            <label for="company_social_network_enabled" class="required form-label">
                                @lang('model.enabled')
                            </label>
                            <select class="form-select" name="enabled" id="company_social_network_enabled"
                                    aria-label="Select example">
                                <option selected>@lang('model.Open this select')</option>
                                <option
                                    value="1" {{ !empty($model->getId())
                                        ? ($model->isEnabled() ? 'selected' : '')
                                        : (old('enabled') === '1' ? 'selected' : '') }}>@lang('model.Active')</option>
                                <option
                                    value="0" {{ !empty($model->getId())
                                        ? (!$model->isEnabled() ? 'selected' : '')
                                        : (old('enabled') === '0' ? 'selected' : '') }}>@lang('model.No Active')</option>
                            </select>
                            @if($errors->has('enabled'))
                                <span class="text-danger">{{ $errors->first('enabled') }}</span>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="submit"
                                    class="btn btn-primary"
                                    id="kt_docs_repeater_button">
                                @if(!empty($model))
                                    @lang('button.Update')
                                @else
                                    @lang('button.Create')
                                @endif
                            </button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>

@stop
