@php use App\Models\Enums\EnumLanguage; @endphp
@php
    /**
    * @var EnumLanguage|null $model
    */
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
                    @lang('client.Enum Language')
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
                        <a href="{{ route('enum-language.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Enum Language')
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
                            <strong class="text-primary">@lang('breadcrumb.Enum Language Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Enum Language Create')</strong>
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
                          action="{{ !empty($model->getId()) ? route('enum-language.edit', ['id' => $model->getId()]) : route('enum-language.store') }}"
                          method="post">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <label class="required form-label" for="language_name_uz">
                                @lang('model.name_uz')
                            </label>
                            <input
                                id="enum_languages_name_uz"
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
                            <label class="required form-label" for="languages_name_oz">
                                @lang('model.name_oz')
                            </label>
                            <input
                                id="enum_languages_name_oz"
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
                            <label class="required form-label" for="languages_name_ru">
                                @lang('model.name_ru')
                            </label>
                            <input
                                id="enum_languages_name_ru"
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
                        <!--end::Input group-->
                        <div class="mb-10">
                            <label for="enum_language_enabled" class="required form-label">
                                @lang('model.enabled')
                            </label>
                            <select class="form-select" name="enabled" id="enum_language_enabled"
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
