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
                @lang('client.Product')
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
                    <a href="{{ route('product.filter') }}" class="text-muted text-hover-primary">
                        @lang('breadcrumb.Product')
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
                    <strong class="text-primary">@lang('breadcrumb.Product View')</strong>
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
                    <th>@lang('model.Model')</th>
                    <th>@lang('model.Model View')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>@lang('model.wrapper_file')</td>
                    <td>
                        <div
                            class="symbol symbol-60px symbol-lg-100px symbol-fixed position-relative">
                            <img src="{{$data->wrapper_file}}" alt="image"/>
                            <div
                                class="position-absolute translate-middle border-body h-30px w-30px"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.title_oz')</td>
                    <td>
                        {{$data->title_oz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.title_uz')</td>
                    <td>
                        {{$data->title_uz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.title_ru')</td>
                    <td>
                        {{$data->title_ru}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.description_oz')</td>
                    <td>
                        {{$data->description_oz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.description_uz')</td>
                    <td>
                        {{$data->description_uz}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.description_ru')</td>
                    <td>
                        {{$data->description_ru}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.process_step')</td>
                    <td>
                        {{$data->process_step}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.author')</td>
                    <td>
                        {{$data->author_name}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.size')</td>
                    <td>
                        {{$data->size}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.source_file')</td>
                    <td>
                        <a href="{{$data->source_file}}"
                           class="fs-3 fw-bold mb-1">{{$data->source_file_name}}
                            </a>
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.type_name')</td>
                    <td>
                        {{$data->type_name}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.status_name')</td>
                    <td>
                        {{$data->status_name}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.price')</td>
                    <td>
                        {{$data->price}}
                    </td>
                </tr>
                <tr>
                    <td>@lang('model.discount')</td>
                    <td>
                        {{$data->discount}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@stop

