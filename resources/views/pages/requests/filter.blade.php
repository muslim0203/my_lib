@php
    use App\Core\Enums\Requests\RequestStatusEnum;
    use App\Core\Helpers\Lang\LanguageHelper;
    use App\Models\Request\Request;
    use App\Core\Enums\Requests\RequestTypeEnum;
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
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($type == RequestTypeEnum::_PRODUCT->value)
                        @lang('client.Products list')
                    @elseif($type == RequestTypeEnum::_AUTHOR->value)
                        @lang('client.Authors list')
                    @else
                        @lang('client.Authority list')
                    @endif
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
                    <li class="breadcrumb-item text-muted"><strong class="text-primary">
                            @if ($type == RequestTypeEnum::_PRODUCT->value)
                                @lang('client.Products list')
                            @elseif($type == RequestTypeEnum::_AUTHORITY->value)
                                @lang('breadcrumb.Authority list')
                            @else
                                @lang('breadcrumb.Authors list')
                            @endif
                        </strong></li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid" data-select2-id="select2-data-kt_app_content">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3"
                                    data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                <i class="ki-duotone ki-filter fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>@lang('button.Filter')
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">@lang('button.Filter Options')</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <form action="{{ route('request.filter') }}" method="get">
                                    @csrf
                                    <!--begin::Content-->
                                    <div class="px-7 py-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">@lang('model.full_name')
                                                :</label>
                                            <input
                                                    type="text"
                                                    name="full_name"
                                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                                    placeholder="@lang('model.full_name')"
                                                    value="{{ request('full_name') }}"
                                            />
                                        </div>
                                        <!--end::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">@lang('model.Status')
                                                :</label>
                                            <select class="form-select form-select-solid fw-bold"
                                                    data-kt-select2="true"
                                                    data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                                    data-kt-user-table-filter="role" data-hide-search="true"
                                                    name="enabled">
                                                <option {{ request('enabled') ?? 'selected' }}></option>
                                                <option
                                                        value="1" {{ request('enabled') == 1 ? 'selected' : '' }} >@lang('model.Active')</option>
                                                <option
                                                        value="0" {{ request('enabled') === '0' ? 'selected' : '' }}>@lang('model.No Active')</option>
                                            </select>
                                        </div>

                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('author.filter') }}"
                                               class="btn btn-light-danger btn-active-light-danger fw-semibold me-2 px-6"
                                            >@lang('button.Reset')</a>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                    data-kt-menu-dismiss="true"
                                                    data-kt-user-table-filter="filter">@lang('button.Apply')
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Content-->
                                </form>
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                             data-kt-user-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger"
                                    data-kt-user-table-select="delete_selected">
                                Delete Selected
                            </button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4 table-responsive">
                    <!--begin::Table-->
                    <table class="table table-striped table-row-bordered fs-6 gy-5" id="kt_datatable_horizontal_scroll">
                        <thead>
                        <tr class="text-start text-muted fw-bold fs-6 text-uppercase gs-0">
                            <th class="min-w-125px">@lang('model.Request Type')</th>
                            <th class="min-w-125px">@lang('model.Process Step')</th>
                            <th class="min-w-125px">@lang('model.Comment')</th>
                            <th class="min-w-125px">@lang('model.Status')</th>
                            <th class="text-end min-w-100px">@lang('button.Actions')</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold" id="education_type_body">
                        @foreach($data as $item)
                            @php
                                /**
                                * @var Request $item
                                */
                            @endphp
                            <tr>
                                <td class="align-items-center">
                                    {{ $item->requestType->{LanguageHelper::getName()} }}
                                </td>
                                <td class="align-items-center">
                                    {{ $item->step->getNameUz() }}
                                </td>
                                <td class="align-items-center">
                                    {{ $item->getComment() }}
                                </td>
                                <td class="align-items-center">
                                    @if($item->getStatus() === RequestStatusEnum::_APPROVED->value)
                                        <div class="fs-4 badge badge-light-success fw-bold">@lang('client.Active')</div>
                                    @elseif($item->getStatus() === RequestStatusEnum::_CHECKING->value)
                                        <div class="fs-4 badge badge-light-warning fw-bold">@lang('client.UnConfirmed')</div>
                                    @elseif($item->getStatus() === RequestStatusEnum::_REJECTED->value)
                                        <div class="fs-4 badge badge-light-danger fw-bold">@lang('client.Deleted')</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                       class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                       data-kt-menu-trigger="click"
                                       data-kt-menu-placement="bottom-end">@lang('button.Actions')
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div
                                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('request.view', ['id' => $item->getId(),'type' => $type]) }}"
                                               class="menu-link px-3 author-view"
                                            >
                                                @lang('button.View')
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">{{ $data->links() }}</td>
                        </tr>
                        </tfoot>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>

@stop
