@php use App\Core\Helpers\Lang\LanguageHelper;
     use App\Models\Company\Company;
 @endphp
@php
    /**
    * @var Company|null $model
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
                    @lang('client.Company')
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
                        <a href="{{ route('company.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Company')
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
                            <strong class="text-primary">@lang('breadcrumb.Company Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Company Create')</strong>
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
                          action="{{ !empty($model->getId()) ? route('company.edit', ['id' => $model->getId()]) : route('company.store') }}"
                          method="post">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_title_oz">
                                            @lang('model.title_oz')
                                        </label>
                                        <input
                                            id="company_title_oz"
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
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_title_uz">
                                            @lang('model.title_uz')
                                        </label>
                                        <input
                                            id="company_title_oz"
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
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_title_ru">
                                            @lang('model.title_ru')
                                        </label>
                                        <input
                                            id="company_title_ru"
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_content_oz">
                                            @lang('model.content_oz')
                                        </label>
                                        <textarea
                                            id="company_content_oz"
                                            name="content_oz"
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
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_content_uz">
                                            @lang('model.content_uz')
                                        </label>
                                        <textarea
                                            id="company_content_uz"
                                            type="text"
                                            name="content_uz"
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
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="required form-label" for="company_content_ru">
                                            @lang('model.content_ru')
                                        </label>
                                        <textarea
                                            id="company_content_ru"
                                            type="text"
                                            name="content_ru"
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
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="form-label" for="company_email">
                                            @lang('model.email')
                                        </label>
                                        <input
                                            id="company_email"
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->email : old('email') }}"
                                            placeholder="@lang('model.Enter email')"
                                        />
                                        @if($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-10">
                                        <label class="form-label" for="company_phone">
                                            @lang('model.phone')
                                        </label>
                                        <input
                                            id="company_phone"
                                            type="text"
                                            name="phone"
                                            class="form-control"
                                            aria-label="Sizing example input"
                                            aria-describedby="inputGroup-sizing-sm"
                                            value="{{ !empty($model->getId()) ? $model->phone : old('phone') }}"
                                            placeholder="@lang('model.Enter phone')"
                                        />
                                        @if($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        <div class="mb-10">
                            <label for="enum_categories_enabled" class="required form-label">
                                @lang('model.enabled')
                            </label>
                            <select class="form-select" name="enabled" id="enum_categories_enabled"
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
