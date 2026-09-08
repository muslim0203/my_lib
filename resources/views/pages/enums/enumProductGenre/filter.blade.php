@php use Illuminate\Support\Facades\Session; @endphp
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

    <script>
        remove(
            'enum_product_genre_delete',
            @json(__("client.Are you sure?")),
            @json(__('client.You won\'t be able to revert this!')),
            @json(__('button.Yes, delete it')),
            @json(__('button.Cancel'))
        );
    </script>
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
                    @lang('client.Enum Product Genre')
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
                    <li class="breadcrumb-item text-muted"><strong
                            class="text-primary">@lang('breadcrumb.Enum Product Genre')</strong></li>
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
                                <form action="{{ route('enum-product-genre.filter') }}" method="get">
                                    @csrf
                                    <!--begin::Content-->
                                    <div class="px-7 py-5">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">@lang('model.name_uz')
                                                :</label>
                                            <input
                                                type="text"
                                                name="name_uz"
                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                placeholder="@lang('model.name_uz')"
                                                value="{{ request('name_uz') }}"
                                            />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">@lang('model.name_oz')
                                                :</label>
                                            <input
                                                type="text"
                                                name="name_oz"
                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                placeholder="@lang('model.name_oz')"
                                                value="{{ old('name_oz') }}"
                                            />
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">@lang('model.name_ru')
                                                :</label>
                                            <input
                                                type="text"
                                                name="name_ru"
                                                class="form-control form-control-solid mb-3 mb-lg-0"
                                                placeholder="@lang('model.name_ru')"
                                                value="{{ old('name_ru') }}"
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
                                            <a href="{{ route('enum-product-genre.filter') }}"
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
                            <!--begin::Add user-->
                            <a
                                href="{{ route('enum-product-genre.create') }}"
                                type="button"
                                class="btn btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>@lang('button.Add Enum Product Genre')
                            </a>
                            <!--end::Add user-->
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
                            <th class="min-w-125px">@lang('model.name_oz')</th>
                            <th class="min-w-125px">@lang('model.name_uz')</th>
                            <th class="min-w-125px">@lang('model.name_ru')</th>
                            <th class="min-w-125px">@lang('model.enabled')</th>
                            <th class="min-w-125px">@lang('model.created_at')</th>
                            <th class="text-end min-w-100px">@lang('button.Actions')</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold" id="enum_product_genres_body">
                        @foreach($data as $item)
                            @php
                                /**
                                * @var \App\Models\Enums\EnumProductGenre $item
                                */
                            @endphp
                            <tr>
                                <td class="align-items-center">
                                    {{ $item->getNameOz() }}
                                </td>
                                <td class="align-items-center">
                                    {{ $item->getNameUz() }}
                                </td>
                                <td class="align-items-center">
                                    {{ $item->getNameRu() }}
                                </td>
                                <td class="align-items-center">
                                    @if($item->isEnabled())
                                        <div class="badge badge-light-success fw-bold">@lang('client.On')</div>
                                    @else
                                        <div class="badge badge-light-danger fw-bold">@lang('client.Off')</div>
                                    @endif
                                </td>
                                <td class="align-items-center">
                                    {{ $item->getCreatedAt() }}
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
                                            <a href="{{ route('enum-product-genre.update', ['id' => $item->getId()]) }}"
                                               class="menu-link px-3 enum-product_genre_edit"
                                            >
                                                @lang('button.Edit')
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a
                                                href="#"
                                                class="menu-link px-3 enum_product_genre_delete"
                                                data-action="{{ route('enum-product-genre.delete', ['id' => $item->getId()]) }}" data-value="{{ csrf_token() }}">
                                                @lang('button.delete')
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
