@php use App\Core\Helpers\Lang\LanguageHelper;
     use App\Models\MainBanner\MainBanner;
 @endphp
@php
    /**
    * @var MainBanner $model
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
    <style>
        button, input, optgroup, select, textarea {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            width: 18px;
        }
    </style>
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
                    @lang('client.Main Banner')
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
                        <a href="{{ route('banner.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Main Banner')
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
                            <strong class="text-primary">@lang('breadcrumb.Main Banner Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Main Banner Create')</strong>
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
                          action="{{ !empty($model->getId()) ? route('banner.edit', ['id' => $model->getId()]) : route('banner.store') }}"
                          method="post" enctype="multipart/form-data">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="name_oz">
                                            @lang('model.name_oz')
                                        </label>
                                        <input
                                            id="banner_name_oz"
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
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="name_uz">
                                            @lang('model.name_uz')
                                        </label>
                                        <input
                                            id="banner_name_uz"
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
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="banner_name_ru">
                                            @lang('model.name_ru')
                                        </label>
                                        <input
                                            id="banner_name_ru"
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="banner_content_oz">
                                            @lang('model.content_oz')
                                        </label>
                                        <textarea
                                            id="banner_content_oz"
                                            name="content_oz"
                                            autofocus
                                            class="form-control ckeditor_content"
                                            aria-label="Sizing example input"
                                            placeholder="@lang('model.Enter content_oz')"
                                            rows="5"
                                        >
                                            {{ !empty($model->getId()) ? $model->getContentOz() : old('content_oz') }}</textarea>
                                        @if($errors->has('content_oz'))
                                            <span class="text-danger">{{ $errors->first('content_oz') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="banner_content_uz">
                                            @lang('model.content_uz')
                                        </label>
                                        <textarea
                                            id="banner_content_uz"
                                            type="text"
                                            name="content_uz"
                                            autofocus
                                            class="form-control ckeditor_content"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="@lang('model.Enter content_uz')"
                                            rows="5"
                                        >
                                            {{ !empty($model->getId()) ? $model->getContentUz() : old('content_uz')  }}
                                        </textarea>
                                        @if($errors->has('content_uz'))
                                            <span class="text-danger">{{ $errors->first('content_uz') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-10">
                                        <label class="required form-label" for="banner_content_ru">
                                            @lang('model.content_ru')
                                        </label>
                                        <textarea
                                            id="banner_content_ru"
                                            type="text"
                                            name="content_ru"
                                            autofocus
                                            class="form-control ckeditor_content"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            placeholder="@lang('model.Enter content_ru')"
                                            rows="5"
                                        >
                                        {{ !empty($model->getId()) ? $model->getContentRu() : old('content_ru') }}
                                        </textarea>
                                        @if($errors->has('content_ru'))
                                            <span class="text-danger">{{ $errors->first('content_ru') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="banner_is_view_content" class="required form-label">
                                        @lang('model.is_view_content')
                                    </label>
                                    <select class="form-select" name="is_view_content" id="is_view_content"
                                            aria-label="Select example">
                                        <option selected>@lang('model.Open this select')</option>
                                        <option
                                            value="1" {{ !empty($model->getId())
                                        ? ($model->getIsViewContent() ? 'selected' : '')
                                        : (old('is_view_content') === '1' ? 'selected' : '') }}>@lang('model.Yes')</option>
                                        <option
                                            value="0" {{ !empty($model->getId())
                                        ? (!$model->getIsViewContent() ? 'selected' : '')
                                        : (old('is_view_content') === '0' ? 'selected' : '') }}>@lang('model.No')</option>
                                    </select>
                                    @if($errors->has('is_view_content'))
                                        <span class="text-danger">{{ $errors->first('is_view_content') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-10">
                                        <label class="required form-label" for="banner_link">
                                            @lang('model.link')
                                        </label>
                                        <input
                                            id="banner_link"
                                            type="text"
                                            name="link"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->getLink() : old('link') }}"
                                            placeholder="@lang('model.Enter link')"
                                        />
                                        @if($errors->has('link'))
                                            <span class="text-danger">{{ $errors->first('link') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="form-label" for="banner_files">
                                            @lang('model.Main Banner Files')
                                        </label>
                                        <input
                                            id="banner_files"
                                            type="file"
                                            name="banner_files[]"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value=""
                                            multiple
                                        />
                                        @if($errors->has('banner_files'))
                                            <span class="text-danger">{{ $errors->first('banner_files') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="mb-10">
                            <label for="banner_enabled" class="required form-label">
                                @lang('model.enabled')
                            </label>
                            <select class="form-select" name="enabled" id="banner_enabled"
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
