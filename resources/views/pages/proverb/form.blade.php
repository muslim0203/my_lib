@php use App\Core\Helpers\Lang\LanguageHelper;
 use App\Models\Proverbs\Proverb;
@endphp
@php
    /**
    * @var Proverb|null $model
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
                    @lang('client.Proverb')
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
                        <a href="{{ route('proverb.filter') }}" class="text-muted text-hover-primary">
                            @lang('breadcrumb.Proverb')
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
                            <strong class="text-primary">@lang('breadcrumb.Proverb Update')</strong>
                        @else
                            <strong class="text-primary">@lang('breadcrumb.Proverb Create')</strong>
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
                    <form
                        action="{{!empty($model->getId()) ? route('proverb.update',['id' => $model->getId()]) : route('proverb.store')}}"
                        method="POST">
                        @if(!empty($model->getId()))
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">@lang('model.author_oz')</label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="@lang('author_oz')"
                                       name="author_oz"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"
                                       value="{{ !empty($model->getId()) ? $model->getAuthorOz() : old('author_oz')}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">@lang('model.author_uz')</label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="@lang('author_uz')"
                                       name="author_uz"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"
                                       value="{{ !empty($model->getId()) ? $model->getAuthorUz() : old('author_uz')}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">@lang('model.author_ru')</label>
                                <input type="text"
                                       class="form-control"
                                       placeholder="@lang('author_ru')"
                                       name="author_ru"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-sm"
                                       value="{{ !empty($model->getId()) ? $model->getAuthorRu() : old('author_ru')}}">
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label" for="proverb_content_oz">
                                    @lang('model.content_oz')
                                </label>
                                <textarea
                                    id="proverb_content_oz"
                                    name="content_oz"
                                    class="form-control ckeditor_content"
                                    aria-label="Sizing example input"
                                    placeholder="@lang('model.Enter content_oz')"
                                    rows="5"
                                >{{ !empty($model->getId()) ? $model->getContentOz() : old('content_oz') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label" for="proverb_content_uz">
                                    @lang('model.content_uz')
                                </label>
                                <textarea
                                    id="proverb_content_uz"
                                    name="content_uz"
                                    class="form-control ckeditor_content"
                                    aria-label="Sizing example input"
                                    placeholder="@lang('model.Enter content_uz')"
                                    rows="5"
                                >{{ !empty($model->getId()) ? $model->getContentUz() : old('content_uz') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label" for="proverb_content_ru">
                                    @lang('model.content_ru')
                                </label>
                                <textarea
                                    id="proverb_content_ru"
                                    name="content_ru"
                                    class="form-control ckeditor_content"
                                    aria-label="Sizing example input"
                                    placeholder="@lang('model.Enter content_ru')"
                                    rows="5"
                                >{{ !empty($model->getId()) ? $model->getContentRu() : old('content_ru') }}</textarea>
                            </div>
                            <div class="mb-10">
                                <label for="proverb_enabled" class="required form-label">
                                    @lang('model.enabled')
                                </label>
                                <select class="form-select" name="enabled" id="proverb_enabled"
                                        aria-label="Select example">
                                    <option selected>@lang('model.Open this select')</option>
                                    <option
                                        value="1" {{ !empty($model->getId())
                                        ? ($model->getEnabled() ? 'selected' : '')
                                        : (old('enabled') === '1' ? 'selected' : '') }}>@lang('model.Active')</option>
                                    <option
                                        value="0" {{ !empty($model->getId())
                                        ? (!$model->getEnabled() ? 'selected' : '')
                                        : (old('enabled') === '0' ? 'selected' : '') }}>@lang('model.No Active')</option>
                                </select>
                            </div>
                        </div>
                            <div class="modal-footer">
                                <button type="submit"
                                        class="btn btn-primary"
                                        id="kt_docs_repeater_button">
                                    @if(!empty($model->getId()))
                                        @lang('button.Update')
                                    @else
                                        @lang('button.Create')
                                    @endif
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
