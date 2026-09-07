@php use App\Core\Helpers\Lang\LanguageHelper;
     use App\Models\Company\CompanyFile;
 @endphp
@php
    /**
    * @var CompanyFile|null $model
    */
    $name = LanguageHelper::getName();
    $file = !empty($companyFile->file_id) ? $companyFile->path : '';

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
                    @lang('client.Company File')
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
                        <a href="{{ route('company-file.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Company File')
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
                        @if(!empty($model))
                            <strong class="text-primary">@lang('breadcrumb.Company File Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Company File Create')</strong>
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
                          action="{{ !empty($model->getId()) ? route('company-file.edit', ['id' => $model->getId()]) : route('company-file.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="required form-label" for="company_file_file_id">
                                        @lang('model.file')
                                    </label>
                                    <div class="image-input image-input-outline"
                                         data-kt-image-input="true"
                                         style="background-image:url('{{$file}}')">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-125px h-125px"
                                             style="background-image:url('{{$file}}')"></div>
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
                                            <input
                                                   type="file"
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
                                </div>
                                <div class="col-md-4">
                                        <div class="mb-10">
                                            <label class="required form-label" for="company_file_file_name">
                                                @lang('model.file_name')
                                            </label>
                                            <input
                                                id="company_file_file_name"
                                                type="text"
                                                name="file_name"
                                                class="form-control"
                                                aria-label="Sizing example input"
                                                aria-describedby="inputGroup-sizing-sm"
                                                value="{{ !empty($model->getId()) ? $model->getTitleOz() : old('file_name') }}"
                                                placeholder="@lang('model.Enter file_name')"
                                            />
                                            @if($errors->has('file_name'))
                                                <span class="text-danger">{{ $errors->first('file_name') }}</span>
                                            @endif
                                        </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="md-10">
                                        <div class="form-group">
                                            <label for="company_file_company_id" class="required form-label">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_file_title_oz">
                                            @lang('model.title_oz')
                                        </label>
                                        <input
                                            id="company_file_title_oz"
                                            type="text"
                                            name="title_oz"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->getTitleOz() : old('title_oz') }}"
                                            placeholder="@lang('model.Enter title_oz')"
                                        />
                                        @if($errors->has('title_oz'))
                                            <span class="text-danger">{{ $errors->first('title_oz') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_file_title_uz">
                                            @lang('model.title_uz')
                                        </label>
                                        <input
                                            id="company_file_title_oz"
                                            type="text"
                                            name="title_uz"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->getTitleUz() : old('title_uz')  }}"
                                            placeholder="@lang('model.Enter title_uz')"
                                        />
                                        @if($errors->has('title_uz'))
                                            <span class="text-danger">{{ $errors->first('title_uz') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_file_title_ru">
                                            @lang('model.title_ru')
                                        </label>
                                        <input
                                            id="company_file_title_ru"
                                            type="text"
                                            name="title_ru"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->getTitleRu() : old('title_ru') }}"
                                            placeholder="@lang('model.Enter title_ru')"
                                        />
                                        @if($errors->has('title_ru'))
                                            <span class="text-danger">{{ $errors->first('title_ru') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="mb-10">
                            <label for="company_file_enabled" class="required form-label">
                                @lang('model.enabled')
                            </label>
                            <select class="form-select" name="enabled" id="company_file_enabled"
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
