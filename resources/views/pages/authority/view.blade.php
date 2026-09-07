@php
    use App\Core\Enums\Authors\AuthorStatusEnum;
    use App\Core\Helpers\Lang\LanguageHelper;
    use App\Models\Files\File;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * @var \App\Models\Authority\Authority $data
     */
@endphp
@extends('layouts.index')

@push('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
          type="text/css"/>
@endpush

@push('scripts')
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/authorRequest.js') }}"></script>
    <!--end::Vendors Javascript-->

    <script>
        confirmClicked('authority_request_confirm_button', 'authority_request_confirm_form');
        cancelClicked('authority_request_cancel_button', 'authority_request_cancel_form');
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
                    @lang('client.Authority View')
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}"
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
                        <a href="{{ route('authority.filter') }}">
                            @lang('breadcrumb.Authority list')
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">@lang('breadcrumb.Authority view')</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Form-->
            <div id="kt_ecommerce_add_product_form"
                 class="form d-flex flex-column flex-lg-row"
            >
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>@lang('model.Personal photo')</h3>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body text-center pt-0">
                            <!--begin::Image input-->
                            @if(!empty($data->profileFile))
                                <!--begin::Image input placeholder-->
                                <style>.image-input-placeholder {
                                        background-image: url({{ $data->profileFile->file->getSrc() }});
                                    }

                                    [data-bs-theme="dark"] .image-input-placeholder {
                                        background-image: url({{ $data->profileFile->file->getSrc()}});
                                    }</style>
                                <!--end::Image input placeholder-->
                            @else
                                <!--begin::Image input placeholder-->
                                <style>.image-input-placeholder {
                                        background-image: url({{ asset('assets/media/svg/files/blank-image.svg') }});
                                    }

                                    [data-bs-theme="dark"] .image-input-placeholder {
                                        background-image: url({{ asset('assets/media/svg/files/blank-image-dark.svg') }});
                                    }</style>
                                <!--end::Image input placeholder-->
                            @endif
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                                 data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-35px h-35px bg-body shadow"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="Download image">
                                    <a href="{{ $data->profileFile->file->getSrc() }}" download>
                                        <i class="ki-duotone ki-cloud-download">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </a>
                                </label>
                                <!--end::Label-->
                            </div>
                            <!--end::Image input-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->
                    <!--begin::Status-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h3>@lang('model.Status')</h3>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <div
                                        class="rounded-circle {{ $data->getStatus() === AuthorStatusEnum::_ACTIVE->value ? 'bg-success' : ($data->getStatus() === AuthorStatusEnum::_UN_CONFIRMED->value ? 'bg-warning' : 'bg-danger') }} w-15px h-15px"
                                        id="kt_ecommerce_add_product_status"></div>
                            </div>
                            <!--begin::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Select2-->
                            <select name="status" disabled class="form-select mb-2" data-control="select2"
                                    data-hide-search="true"
                                    data-placeholder="@lang('client.Select an option')" id="author_status">
                                @foreach(AuthorStatusEnum::getListLabel() as $key => $value)
                                    @if($key === $data->getStatus())
                                        <option value="{{ $key }}"
                                                selected="selected">{{ AuthorStatusEnum::getListLabel()[$key] }}</option>
                                        @continue
                                    @endif
                                    <option value="{{ $key }}">{{ AuthorStatusEnum::getListLabel()[$key] }}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">@lang('model.inn')</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input disabled name="inn" type="text" class="form-control mb-2"
                                       placeholder="@lang('client.Authority inn')"
                                       value="{{ $data->getInn() }}"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">@lang('model.email')</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input disabled name="email" type="text" class="form-control mb-2"
                                       placeholder="@lang('client.Authority email')"
                                       value="{{ $data->getEmail() }}"/>
                                <!--end::Input-->
                            </div>
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">@lang('model.phone')</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input disabled name="phone" type="text" class="form-control mb-2"
                                       placeholder="@lang('client.Authority phone')"
                                       value="{{ $data->getPhone() }}"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row">
                                <!--begin::Label-->
                                <label class="required form-label">@lang('model.account_number')</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input disabled name="account_number" type="text" class="form-control mb-2"
                                       placeholder="@lang('client.Authority account number')"
                                       value="{{ $data->getAccountNumber() }}"/>
                                <!--end::Input-->
                            </div>
                        </div>

                        <!--end::Card body-->
                    </div>
                    <!--end::Status-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                               href="#kt_ecommerce_add_product_general">@lang('client.General')</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                               href="#kt_ecommerce_add_product_advanced">@lang('client.Files')</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <!--begin::General options-->
                                <div class="card card-flush py-4">
                                    <!--begin::Card header-->
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>@lang('client.General')</h2>
                                        </div>
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_oz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input disabled name="name_oz" type="text" class="form-control mb-2"
                                                   placeholder="@lang('client.Authority name oz')"
                                                   value="{{ $data->getNameOz() }}"/>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_uz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input disabled name="name_uz" type="text" class="form-control mb-2"
                                                   placeholder="@lang('client.Authority name uz')"
                                                   value="{{ $data->getNameUz() }}"/>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_ru')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input disabled name="name_ru" type="text" class="form-control mb-2"
                                                   placeholder="@lang('client.Authority name ru')"
                                                   value="{{ $data->getNameRu() }}"/>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div>
                                            <!--begin::Label-->
                                            <label class="form-label">@lang('client.address')</label>
                                            <!--end::Label-->
                                            <div id="author_address"
                                                 name="author_address">
                                                <p class="alert alert-info">
                                                    {{ $data->getAddress() }}
                                                </p>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div>
                                            <!--begin::Label-->
                                            <label class="form-label">@lang('client.description')</label>
                                            <!--end::Label-->
                                            <div id="description"
                                                 name="description">
                                                <p class="alert alert-info">
                                                    {{ $data->getDescription() }}
                                                </p>
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div>
                                            <!--begin::Label-->
                                            <label class="form-label">@lang('client.Process Step')</label>
                                            <!--end::Label-->
                                            <div id="step_id"
                                                 name="step">
                                                <p class="alert alert-info">
                                                    {{ $data->step->{LanguageHelper::getName()} }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mb-10 fv-row">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.activity_type_id')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input disabled name="activity_type_id" type="text" class="form-control mb-2"
                                                   placeholder="@lang('client.Authority activity type')"
                                                   value="{{ $data->activityType?->{LanguageHelper::getName()} }}"/>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::General options-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                        <!--begin::Tab pane-->
                        <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-0">
                                        <!--begin::Input group-->
                                        <label class="form-label">@lang('client.Certificate File')</label>
                                        <?php if($data->certificateFile):?>
                                        <div class="fv-row mb-2">
                                            <!--begin::Dropzone-->
                                            <div class="dropzone">
                                                <!--begin::Message-->
                                                <div class="dz-message needsclick">
                                                    <a href="{{ url($data->certificateFile?->getSrc()) }}" download>
                                                        {{ $data->getCertificateFileName() }}
                                                    </a>
                                                </div>
                                            </div>
                                            <!--end::Dropzone-->
                                        </div>
                                        <?php endif;?>
                                        <label class="form-label">@lang('client.Patent File')</label>
                                        <?php if($data->patentFile):?>
                                        <div class="fv-row mb-2">
                                            <!--begin::Dropzone-->
                                            <div class="dropzone">
                                                <!--begin::Message-->
                                                <div class="dz-message needsclick">
                                                    <a href="{{ url($data->patentFile?->getSrc()) }}" download>
                                                        {{ $data?->getPatentFileName() }}
                                                    </a>
                                                </div>
                                            </div>
                                            <!--end::Dropzone-->
                                        </div>
                                        <?php endif;?>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Card header-->
                                </div>
                                <!--end::Media-->
                            </div>
                        </div>
                        <!--end::Tab pane-->
                    </div>
                    @if($data->getStepId() === 1)
                        <!--end::Tab content-->
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_stacked_1">
                                @lang('button.Cancel')
                            </button>
                            <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_stacked_2">
                                @lang('button.Confirm')
                            </button>
                            <!--end::Button-->
                        </div>
                    @endif
                </div>
                <!--end::Main column-->
            </div>
            <div class="modal fade" tabindex="-1" id="kt_modal_stacked_1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">@lang('client.Cancel')</h3>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                 aria-label="Close">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <form id="authority_request_cancel_form" method="post"
                                  action="{{ route('authority.cancel', ['id' => $data->getId()]) }}">
                                @csrf
                                <div class="input-group">
                                    <span class="required input-group-text">@lang('client.Description')</span>
                                    <textarea required name="description" class="form-control"
                                              aria-label="With textarea"></textarea>
                                </div>
                                <div class="form-check form-check-custom form-check-solid me-10">
                                    <input checked
                                           class="form-check-input h-40px w-40px"
                                           type="checkbox"
                                           name="is_editable"
                                           id="flexCheckbox40"
                                           value="true"
                                    />
                                    <label class="form-check-label" for="flexCheckbox40">
                                        <?= __('client.Is editable') ?>
                                    </label>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm fw-bold btn-light"
                                    data-bs-dismiss="modal">@lang('button.Close')</button>
                            <button type="button" id="authority_request_cancel_button"
                                    class="btn btn-sm fw-bold btn-primary">@lang('button.Cancel')</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" id="kt_modal_stacked_2">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">@lang('client.Confirm')</h3>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                 aria-label="Close">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <form id="authority_request_confirm_form" method="post"
                                  action="{{ route('authority.confirm', ['id' => $data->getId()]) }}">
                                @csrf
                                <div class="input-group">
                                    <span class="required input-group-text">@lang('client.Description')</span>
                                    <textarea name="description" class="form-control"
                                              aria-label="With textarea"></textarea>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm fw-bold btn-light"
                                    data-bs-dismiss="modal">@lang('button.Close')</button>
                            <button type="button" id="authority_request_confirm_button"
                                    class="btn btn-sm fw-bold btn-primary">@lang('button.Confirm')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@stop

