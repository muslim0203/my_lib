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
@endpush

@section('content')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
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
                    <strong class="text-primary">@lang('breadcrumb.Proverb View')</strong>
                </li>
                <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
        </div>
    </div>
    <br>
    <br>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <table class="table table-bordered">
                <thead class="table-bordered">
                <tr>
                    <th>@lang('model.Proverb')</th>
                    <th>@lang('model.Proverb View')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>@lang('model.author_oz')</td>
                    <td>
                        {{$data->author_oz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.author_uz')</td>
                    <td>
                        {{$data->author_uz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.author_ru')</td>
                    <td>
                        {{$data->author_ru}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.content_oz')</td>
                    <td>
                        {{$data->content_oz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.content_uz')</td>
                    <td>
                        {{$data->content_uz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.content_ru')</td>
                    <td>
                        {{$data->content_ru}}
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
@stop

