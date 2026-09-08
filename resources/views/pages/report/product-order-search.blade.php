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
                @lang('client.Product Order')
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
            <div class="row card-body">
                    <button type="button" class="collapsible">@lang('client.Search')</button>
                    <div class="content">
                        <br>
                        <form action="{{ route('report.productOrderSearch') }}" method="get">
                            <div class="row">
                                @csrf
                                <!--begin::Content-->
                                <!--begin::Input group-->
                                <div class="col-md-3">
                                    <label class="form-label fs-6 fw-semibold">@lang('model.Title'):</label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control form-control-solid mb-3 mb-lg-0"
                                        placeholder="@lang('model.title')"
                                        value="{{ request('title') }}"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-6 fw-semibold">@lang('model.payment_type'):</label>
                                    <select class="form-select form-select-solid fw-bold"
                                            data-kt-select2="true"
                                            data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                            data-kt-user-table-filter="role" data-hide-search="true"
                                            name="payment_type">
                                        <option {{ request('payment_type') ?? 'selected' }}></option>
                                        <option
                                            value="free" {{ request('payment_type') == 'free' ? 'selected' : '' }} >@lang('model.Free')</option>
                                        <option
                                            value="payme" {{ request('payment_type') === 'payme' ? 'selected' : '' }}>@lang('model.Payme')</option>
                                        <option
                                            value="click" {{ request('payment_type') === 'click' ? 'selected' : '' }}>@lang('model.Click')</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-6 fw-semibold">@lang('model.Categories'):</label>
                                    <select class="form-select form-select-solid fw-bold"
                                            data-kt-select2="true"
                                            data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                            name="category_id">
                                        <option selected value="">@lang('model.Open this select')</option>
                                        @foreach($categories as $category)
                                            <option {{old('category_id')}} value="{{$category['id']}}">{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-6 fw-semibold">@lang('model.Genres'):</label>
                                    <select class="form-select form-select-solid fw-bold"
                                            data-kt-select2="true"
                                            data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                            name="genre_id">
                                        <option selected value="">@lang('model.Open this select')</option>
                                        @foreach($genres as $genre)
                                            <option {{old('genre_id')}} value="{{$genre['id']}}">{{$genre['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fs-6 fw-semibold">@lang('model.Tags'):</label>
                                    <select class="form-select form-select-solid fw-bold"
                                            data-kt-select2="true"
                                            data-placeholder="@lang('client.Select an option')" data-allow-clear="true"
                                            name="tag_id">
                                        <option selected value="">@lang('model.Open this select')</option>
                                        @foreach($tags as $tag)
                                            <option {{old('tag_id')}} value="{{$tag['id']}}">{{$tag['name']}}</option>
                                        @endforeach
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
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-9">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('report.productOrderSearch') }}"
                                           class="btn btn-light-danger btn-active-light-danger fw-semibold me-2 px-6"
                                        >@lang('button.Reset')</a>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true"
                                                data-kt-user-table-filter="filter">@lang('button.Apply')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            <!--begin::Card title-->

        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4 table-responsive">
            <!--begin::Table-->
            <table class="table table-striped table-row-bordered fs-6 gy-5" id="kt_datatable_horizontal_scroll">
                <thead>
                <tr class="text-start text-muted fw-bold fs-6 text-uppercase gs-0">
                    <th class="text-center">@lang('№')</th>
                    <th class="text-center">@lang('model.WrapperFile')</th>
                    <th class="min-w-125px">@lang('model.Title')</th>
                    <th class="text-center">@lang('model.Author')</th>
                    <th class="min-w-125px">@lang('model.Categories')</th>
                    <th class="min-w-125px">@lang('model.Genres')</th>
                    <th class="min-w-125px">@lang('model.Tags')</th>
                    <th class="min-w-125px">@lang('model.PaymentType')</th>
                    <th class="min-w-125px">@lang('model.OrderPrice')</th>
                    <th class="min-w-125px">@lang('model.MerchantMoneyAmount')</th>
                    <th class="min-w-125px">@lang('model.WebService')</th>
                    <th class="min-w-125px">@lang('model.Created_at')</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="enum_categories_body">
                @foreach($data as $key => $item)
                    <tr>
                        <td class="text-center">{{$key + 1}}</td>
                        <td class="text-center">
                            <a href="{{route('product.view', ['id' => $item['id']])}}">
                            <div class="symbol symbol-60px symbol-lg-90px symbol-fixed">
                                <img src="{{$item['wrapper_file']}}" alt="image"/>
                                <div class="position-absolute translate-middle border-body h-80px w-80px"></div>
                            </div>
                            </a>
                        </td>
                        <td class="align-items-center">
                            {{$item['title']}}
                        </td>
                        <td class="align-items-center">
                            {{$item['author']}}
                        </td>
                        <td class="align-items-center">
                            @php if(!empty($item['productCategory'])): @endphp
                            @foreach($item['productCategory'] as $category)
                                <span class="alert-success">{{$category->{\App\Core\Helpers\Lang\LanguageHelper::getName()} }}</span><br>
                            @endforeach
                            @php else: @endphp
                            <p class="text-center"> - </p>
                            @php endif; @endphp
                        </td>
                        <td class="align-items-center">
                            @php if(!empty($item['productGenre'])): @endphp
                            @foreach($item['productGenre'] as $genre)
                                <span class="alert-primary">{{$genre->{\App\Core\Helpers\Lang\LanguageHelper::getName()} }}</span><br>
                            @endforeach
                            @php else: @endphp
                            <p class="text-center"> - </p>
                            @php endif; @endphp
                        </td>
                        <td class="align-items-center">
                            @php if(!empty($item['productTag'])): @endphp
                            @foreach($item['productTag'] as $tag)
                                <span class="alert-info">{{$tag->{\App\Core\Helpers\Lang\LanguageHelper::getName()}  }}</span><br>
                            @endforeach
                            @php else: @endphp
                            <p class="text-center"> - </p>
                            @php endif; @endphp
                        </td>
                        <td class="align-items-center">
                                <?php if ($item['payment_type'] == 'free'): ?>
                            <h5 class="badge p-3 badge-secondary text-center">@lang('model.Free')</h5>
                            <?php elseif($item['payment_type'] == 'payme') : ?>
                            <h5 class="badge p-3 badge-success text-center">@lang('model.Payme')</h5>
                            <?php elseif ($item['payment_type'] == 'click'): ?>
                            <h5 class="badge p-3 badge-info text-center">@lang('model.Click')</h5>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            {{$item['order_price']}}
                        </td>
                        <td class="text-center">
                            {{$item['merchant_money_amount']}}
                        </td>
                        <td class="text-center">
                            {{$item['web_service_price_percentage'] ? $item['web_service_price_percentage']. ' %' : ''}}
                            <span>{{$item['web_service_money_amount'] ?? ''}}</span>
                        </td>
                        <td class="text-center">
                            {{$item['created_at']}}
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
