@php
use App\Core\Helpers\Lang\LanguageHelper;
$name = LanguageHelper::getName();
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

<style>
    .collapsible {
        background-color: #eee;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
    }

    /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
    .active, .collapsible:hover {
        background-color: #ccc;
    }

    /* Style the collapsible content. Note: hidden by default */
    .content {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-out;
    }
</style>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
</script>
@endpush

@section("content")
<!--begin::Toolbar-->
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack m-lg-2">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                @lang('client.User Register')
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <a href="{{ url('dashboard') }}"
                       class="text-muted text-hover-primary">@lang('breadcrumb.Home')</a>
                </li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
    </div>
    <!--end::Toolbar container-->
<!--end::Toolbar-->
<!--begin::Content-->
        <!--begin::Card-->
        <div class="card m-10">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <div class="row card-body">
                        <button type="button" class="collapsible">@lang('client.Search')</button>
                        <div class="content">
                            <br>
                            <form action="{{ route('report.userRegisterSearch') }}" method="get">
                                <div class="row">
                                    @csrf
                                    <!--begin::Content-->
                                    <!--begin::Input group-->
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.Full Name')
                                            :</label>
                                        <input
                                            type="text"
                                            name="full_name"
                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                            placeholder="@lang('model.full_name')"
                                            value="{{ request('full_name') }}"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.Username')
                                            :</label>
                                        <input
                                            type="text"
                                            name="username"
                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                            placeholder="@lang('model.username')"
                                            value="{{ request('username') }}"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.email')
                                            :</label>
                                        <input
                                            type="text"
                                            name="email"
                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                            placeholder="@lang('model.email')"
                                            value="{{ request('email') }}"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.Status')
                                            :</label>
                                        <select class="form-select form-select-solid fw-bold"
                                                data-kt-select2="true"
                                                data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true"
                                                name="status">
                                            <option {{ request('status') ?? 'selected' }}></option>
                                            <option
                                                value="1" {{ request('status') == 1 ? 'selected' : '' }} >@lang('model.Active')</option>
                                            <option
                                                value="0" {{ request('status') === 0 ? 'selected' : '' }}>@lang('model.No Active')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.From Date'):</label>
                                        <input
                                            type="date"
                                            name="from_date"
                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                            placeholder="@lang('model.from_date')"
                                            value="{{ request('from_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', request('from_date'))->format('Y-m-d') : '' }}"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-6 fw-semibold">@lang('model.To Date'):</label>
                                        <input
                                            type="date"
                                            name="to_date"
                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                            placeholder="@lang('model.to_date')"
                                            value="{{ request('to_date') ? \Carbon\Carbon::createFromFormat('Y-m-d', request('to_date'))->format('Y-m-d') : '' }}"
                                        />
                                    </div>
                                    <div class="col-md-3">
                                        <br>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('report.productOrderSearch') }}"
                                               class="btn btn-light-danger btn-active-light-danger fw-semibold me-2 px-6"
                                            >@lang('button.Reset')</a>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                    data-kt-menu-dismiss="true"
                                                    data-kt-user-table-filter="filter">@lang('button.Search')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
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
                        <th class="text-center">@lang('model.picture')</th>
                        <th class="min-w-125px">@lang('model.full_name')</th>
                        <th class="min-w-125px">@lang('model.username')</th>
                        <th class="min-w-125px">@lang('model.birth_date')</th>
                        <th class="min-w-125px">@lang('model.email')</th>
                        <th class="min-w-125px">@lang('model.phone')</th>
                        <th class="min-w-125px">@lang('model.address')</th>
                        <th class="min-w-125px">@lang('model.Created At')</th>
                        <th class="min-w-125px">@lang('model.status')</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold" id="enum_categories_body">
                    @foreach($data as $item)
                    <tr>
                        <td class="text-center">
                            <div  class="symbol symbol-60px symbol-lg-60px symbol-fixed">
                                <img src="{{$item['picture']}}" class="rounded-circle" alt="image"/>
                                <div class="position-absolute translate-middle border-body h-20px w-20px"></div>
                            </div>
                        </td>
                        <td class="align-items-center">
                           {{$item['full_name']}}
                        </td>
                        <td class="align-items-center">
                            {{$item['username']}}
                        </td>
                        <td class="align-items-center">
                            {{ $item['birth_date'] ? date('d.m.Y',strtotime($item['birth_date'])) : ''}}
                        </td>
                        <td class="align-items-center">
                            {{$item['email']}}
                        </td>
                        <td class="align-items-center">
                            {{$item['phone']}}
                        </td>
                        <td class="align-items-center">
                            {{$item['current_address']}}
                        </td>
                        <td class="align-items-center">
                            {{date('d.m.Y H:i:s',strtotime($item['created_at']))}}
                        </td>
                        <td class="align-items-center">
                            <?php if($item['status'] == 1):?>
                                <h4 class="badge p-5 badge-success text-center">@lang('model.Active')</h4>
                            <?php else :?>
                                <h5 class="alert alert-secondary text-center">@lang('model.No active')</h5>
                            <?php endif;?>
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

@stop
