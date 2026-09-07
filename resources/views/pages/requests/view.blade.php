@php
    use App\Core\Helpers\Lang\LanguageHelper;
    use App\Core\Repository\Enum\AcademicDegreeRepository;
    use App\Core\Repository\Enum\AcademicPositionRepository;
    use App\Core\Repository\Enum\CategoriesRepository;
    use App\Core\Repository\Enum\EducationTypeRepository;
    use App\Core\Repository\Enum\ProductGenresRepository;
    use App\Core\Repository\Enum\ProductTagRepository;
    use App\Core\Repository\FileManager\FileManagerRepository;
    use App\Core\Repository\LInks\LinkAuthorFilesRepository;
    use App\Core\Repository\Product\ProductPriceTypeRepository;
    use App\Core\Repository\Product\ProductTypeRepository;
    use App\Models\Enums\EnumAcademicDegree;
    use App\Models\Enums\EnumAcademicPosition;
    use App\Models\Enums\EnumEducationType;
    use App\Core\Enums\Requests\RequestTypeEnum;
    use App\Core\Enums\Requests\RequestStatusEnum;
    use App\Models\Request\Request;
    use Illuminate\Database\Eloquent\Collection;
    use App\Core\Enums\Genders\GenderEnum;

    /**
     * @var Request $request
     * @var EnumAcademicDegree[]|Collection $academicDegrees
     * @var EnumAcademicPosition[]|Collection $academicPositions
     * @var EnumEducationType[]|Collection $educationTypes
     * @var LinkAuthorFilesRepository $linkAuthorFilesRepository
     */

    $linkAuthorFilesRepository = app(LinkAuthorFilesRepository::class);
    $tagRepository = app(ProductTagRepository::class);
    $genreRepository = app(ProductGenresRepository::class);
    $fileRepository = app(FileManagerRepository::class);
    $priceTypeRepository = app(ProductPriceTypeRepository::class);
    $categoriesRepository = app(CategoriesRepository::class);
    $educationTypeRepository = app(EducationTypeRepository::class);
    $academicDegreeRepository = app(AcademicDegreeRepository::class);
    $academicPositionRepository = app(AcademicPositionRepository::class);
    $productTypeRepository = app(ProductTypeRepository::class);

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
        confirmClicked('request_confirm_button', 'request_confirm_form');
        cancelClicked('request_cancel_button', 'request_cancel_form');
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
                    @if($request->request_type_id == RequestTypeEnum::_PRODUCT->value)
                        @lang('client.Product View')
                    @elseif($request->request_type_id == RequestTypeEnum::_AUTHOR->value)
                        @lang('client.Author View')
                    @elseif($request->request_type_id == RequestTypeEnum::_AUTHORITY->value)
                        @lang('client.Authority View')
                    @endif
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
                        <a href="{{ route('request.filter', ['type' => $request->getRequestTypeId()]) }}">
                            @lang('breadcrumb.Request list')
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">{{ $request->requestType->{LanguageHelper::getName()} }}</li>
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
            @if($type == RequestTypeEnum::_PRODUCT->value)
                <div id="kt_ecommerce_add_product_form"
                     class="form d-flex flex-column flex-lg-row"
                >
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-150px symbol-lg-150px symbol-fixed position-relative">
                                        <img src="{{$fileRepository->getFileSource($data['wrapper_file_id'])}}"
                                             alt="image"/>
                                    </div>
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--begin::Details content-->
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Author')
                                        </div>
                                        <div class="col-md-6">
                                            {{$request->author->socialUser->getFullName()}}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Source File')
                                        </div>
                                        <div class="col-md-6">
                                            @php if(!empty($data['source_file_id'])): @endphp
                                            <a href="{{$fileRepository->getFileSource($data['source_file_id'])}}"
                                               class="fs-6 mb-3 btn-sm btn btn-primary">@lang('Download')
                                                <span class="fa fa-download"></span></a>
                                            @php endif; @endphp
                                        </div>
                                    </div>

                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Size')
                                        </div>
                                        <div class="col-md-6">
                                            {{$data['size']}}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold col-md-6">
                                            @lang('client.Status')
                                        </div>
                                        <div class="col-md-6">
                                            @if($request->status == RequestStatusEnum::_CHECKING->value)
                                                <span class="fs-4 badge badge-light-warning">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_APPROVED->value)
                                                <span class="fs-4 badge badge-light-success">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_REJECTED->value)
                                                <span class="fs-4 badge badge-light-danger">{{$request->status}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <div class="card card-flush py-2">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h3>@lang('client.Product Details')</h3>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="fw-bold col-md-6">
                                        @lang('client.Categories')
                                    </div>
                                    <div>
                                        <select class="form-select form-select-solid"
                                                multiple="multiple"
                                                data-kt-select2="true"
                                                data-close-on-select="false"
                                                data-dropdown-parent="#kt_menu_64b776149e588"
                                                data-allow-clear="false"
                                        >
                                            @foreach($categoriesRepository->getCategoryList($data['categories']) as $category)
                                                <option selected disabled="disabled"
                                                        value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="fw-bold col-md-6">
                                        @lang('client.Tags')
                                    </div>
                                    <div>
                                        <select class="form-select form-select-solid"
                                                multiple="multiple"
                                                data-kt-select2="true"
                                                data-close-on-select="false"
                                                data-dropdown-parent="#kt_menu_64b776149e588"
                                                data-allow-clear="false"
                                        >
                                            @foreach($tagRepository->getTagList($data['tags']) as $tag)
                                                <option selected readonly value="{{$tag->id}}">{{$tag->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="fw-bold col-md-6">
                                        @lang('client.Genres')
                                    </div>
                                    <div>
                                        <select class="form-select form-select-solid"
                                                multiple="multiple"
                                                data-kt-select2="true"
                                                data-close-on-select="false"
                                                data-dropdown-parent="#kt_menu_64b776149e588"
                                                data-allow-clear="false"
                                        >
                                            @foreach($genreRepository->getGenreList($data['genres']) as $genre)
                                                <option selected readonly
                                                        value="{{$genre->id}}">{{$genre->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="fw-bold col-md-6">
                                        @lang('client.Product Types')
                                    </div>
                                    <div>
                                        <select class="form-select form-select-solid"
                                                multiple="multiple"
                                                data-kt-select2="true"
                                                data-close-on-select="false"
                                                data-dropdown-parent="#kt_menu_64b776149e588"
                                                data-allow-clear="false"
                                        >
                                            @foreach($productTypeRepository->getTypeList($data['types']) as $type)
                                                <option selected readonly
                                                        value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end::Card-->
                    </div>
                    <!--begin::Main column-->
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-950px mb-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body pt-0 pb-5">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>@lang('client.Model Data')</h2>
                                    </div>
                                </div>
                                <di class="row">
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.title_oz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_oz" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.title_oz') value="{{$data['title_oz'] ?? ''}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.title_uz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_uz" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.title_uz') value="{{$data['title_uz'] ?? ''}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.title_ru')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="title_ru" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.title_ru') value="{{$data['title_ru'] ?? ''}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </di>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.description_oz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea name="title_ru" readonly class="form-control mb-2"
                                                      placeholder=@lang('model.title_ru')>{{$data['description_oz']}}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.description_uz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea name="title_ru" readonly class="form-control mb-2"
                                                      placeholder=@lang('model.title_uz')>{{$data['description_uz']}}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.description_ru')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea name="title_ru" readonly class="form-control mb-2"
                                                      placeholder=@lang('model.description_ru')>{{$data['description_ru']}}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.price')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="price" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.price') value="{{$data['price_value']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="mt-15"><?php echo $priceTypeRepository->getById($data['price_type_id'])->{LanguageHelper::getName()} ?></h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.create_date')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="date" name="create_date" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.create_date') value="{{$data['create_date']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    @php if(!empty($data['audio_files'])): @endphp
                                    <h4>@lang('client.Audio Files')</h4>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead class="table-bordered">
                                            <tr>
                                                <th class="text-bold">№</th>
                                                <th class="text-bold">@lang('client.File Name')</th>
                                                <th class="text-bold">@lang('client.File Listen')</th>
                                                <th class="text-bold">@lang('client.File Action')</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-bordered">
                                            @php $count = 1; foreach ($data['audio_files'] as $file):@endphp
                                            <tr>
                                                <td class="text-center">{{$count}}</td>
                                                <td class="text-center">{{$fileRepository->getById($file)?->original_name}}</td>
                                                <td class="text-center">
                                                    <audio controls>
                                                        <source src="{{$fileRepository->getFileSource($file)}}"
                                                                type="audio/mpeg">
                                                    </audio>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{$fileRepository->getFileSource($file)}}"
                                                       class="fs-3 mb-1 btn-sm btn-outline-primary">
                                                        <span class="fa fa-download"></span></a>
                                                </td>

                                            </tr>
                                            @php $count++; endforeach;@endphp
                                            </tbody>
                                        </table>
                                    </div>
                                    @php else:; @endphp
                                    <h4 class="alert-info">@lang('client.Audio files is not available!')</h4>
                                    @php endif; @endphp
                                </div>
                                <br>
                                <div class="row">
                                    @php if(!empty($data['licence_file_id'])): @endphp
                                    <h4>@lang('client.Licence File')</h4>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead class="table-bordered">
                                            <tr>
                                                <th class="text-bold">№</th>
                                                <th class="text-bold">@lang('client.Licence File Name')</th>
                                                <th class="text-bold">@lang('client.Licence Date')</th>
                                                <th class="text-bold">@lang('client.Licence Code')</th>
                                                <th class="text-bold">@lang('client.File Action')</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-bordered">
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center"><?= !empty($data['licence_file_id']) ? $fileRepository->getById($data['licence_file_id'][0])?->original_name : '' ?></td>
                                                <td class="text-center">
                                                    {{$data['licence_date']}}
                                                </td>
                                                <td class="text-center">
                                                        <?= !empty($data['licence_code']) ? $data['licence_code'] : '' ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{$fileRepository->getFileSource($data['licence_file_id'][0])}}"
                                                       class="fs-3 mb-1 btn-sm btn-outline-primary">
                                                        <span class="fa fa-download"></span></a>
                                                </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @php else:; @endphp
                                    <h4 class="alert-info">@lang('client.Licence files is not available!')</h4>
                                    @php endif; @endphp
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            @if($request->getStatus() == \App\Core\Enums\Requests\RequestStatusEnum::_CHECKING->value)
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_1">
                                    @lang('button.Reject')
                                </button>
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_2">
                                    @lang('button.Confirm')
                                </button>
                            @endif
                            <!--end::Button-->
                        </div>

                    </div>
                    <!--end::Main column-->
                </div>
            @elseif($type == RequestTypeEnum::_AUTHOR->value)
                <div id="kt_ecommerce_add_author_form"
                     class="form d-flex flex-column flex-lg-row"
                >
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Avatar-->
                                    @php if(!empty($data['file_id'])): @endphp
                                    <div class="symbol symbol-150px symbol-lg-150px symbol-fixed position-relative">
                                        <img src="{{$fileRepository->getFileSource($data['file_id'])}}"
                                             alt="image"/>
                                    </div>
                                    @php endif; @endphp
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--begin::Details content-->
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Author')
                                        </div>
                                        <div class="col-md-6">
                                            {{$request->author->socialUser->getFullName()}}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Diploma File')
                                        </div>
                                        <div class="col-md-6">
                                            @php if(!empty($data['diploma_file_id'])): @endphp
                                            <a href="{{$fileRepository->getFileSource($data['diploma_file_id'])}}"
                                               class="fs-6 mb-3 btn-sm btn btn-primary">@lang('Download') <span
                                                    class="fa fa-download"></span></a>
                                            @php endif; @endphp
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Licence File')
                                        </div>
                                        <div class="col-md-6">
                                            @php if(!empty($data['licence_file_id'])): @endphp
                                            <a href="{{$fileRepository->getFileSource($data['licence_file_id'])}}"
                                               class="fs-6 mb-3 btn-sm btn btn-primary">@lang('Download')
                                                <span class="fa fa-download"></span></a>
                                            @php endif; @endphp
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Status')
                                        </div>
                                        <div class="col-md-6">
                                            @if($request->status == RequestStatusEnum::_CHECKING->value)
                                                <span class="fs-4 badge badge-light-warning">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_APPROVED->value)
                                                <span class="fs-4 badge badge-light-success">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_REJECTED->value)
                                                <span class="fs-4 badge badge-light-danger">{{$request->status}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>@lang('client.Details')</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="fw-bold col-md-12">
                                        <label class="required form-label">@lang('client.Enum Education Type')</label>
                                    </div>
                                    <div class="fw-bold col-md-12">
                                        <input type="text" name="education_type_id" readonly class="form-control mb-2"
                                               placeholder=@lang('model.education_type_id')
                                               value="@php if(!empty($data['education_type_id'])): @endphp {{$educationTypeRepository->get($data['education_type_id'])->{LanguageHelper::getName()} }} @php endif; @endphp "/>
                                    </div>
                                </div>
                                <br>
                                <?php if (!empty($data['academic_degree_id'])): ?>
                                <div class="row">
                                    <div class="fw-bold col-md-12">
                                        <label class="required form-label">@lang('client.Enum Academic Degree')</label>
                                    </div>
                                    <div class="fw-bold col-md-12">
                                        <input type="text" name="academic_degree_id" readonly class="form-control mb-2"
                                               placeholder=@lang('model.academic_degree_id')
                                               value="{{$academicDegreeRepository->get($data['academic_degree_id'])->{LanguageHelper::getName()} }}"/>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <br>
                                <?php if (!empty($data['academic_position_id'])): ?>
                                <div class="row">
                                    <div class="fw-bold col-md-12">
                                        <label
                                            class="required form-label">@lang('client.Enum Academic Position')</label>
                                    </div>
                                    <div class="fw-bold col-md-12">
                                        <input type="text" name="first_name" readonly class="form-control mb-2"
                                               placeholder=@lang('model.first_name')
                                               value="{{$academicPositionRepository->get($data['academic_position_id'])->{LanguageHelper::getName()} }}"/>
                                        <!--end::Input-->
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-body pt-0 pb-5">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>@lang('client.Model Data')</h2>
                                    </div>
                                </div>
                                <di class="row">
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.first_name')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="first_name" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.first_name') value="{{$data['first_name']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.last_name')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="last_name" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.last_name') value="{{$data['last_name']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.middle_name')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="middle_name" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.middle_name') value="{{$data['middle_name']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </di>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.passport')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="passport" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.passport') value="{{$data['passport']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.pin_fl')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="pin_fl" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.pin_fl') value="{{$data['pin_fl']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.email')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="email" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.email') value="{{$data['email']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <?php if (!empty($data['gender'])): ?>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.gender')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="gender" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.gender') value="{{$data['gender'] == GenderEnum::_MALE->value ? __('model.Male') : __('model.Female') }}"/>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.phone')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="phone" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.phone') value="{{$data['phone']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.account_number')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="phone" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.account_number') value="{{$data['account_number']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.description')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <textarea name="description" readonly class="form-control mb-2"
                                                      placeholder=@lang('model.description')>{{$data['description']}}</textarea>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            @if($request->getStatus() == RequestStatusEnum::_CHECKING->value)
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_1">
                                    @lang('button.Reject')
                                </button>
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_2">
                                    @lang('button.Confirm')
                                </button>
                            @endif
                            <!--end::Button-->
                        </div>

                    </div>
                    <!--end::Main column-->
                </div>
            @elseif($type == RequestTypeEnum::_AUTHORITY->value)
                <div id="kt_ecommerce_add_authority_form"
                     class="form d-flex flex-column flex-lg-row"
                >
                    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                        <!--begin::Card-->
                        <div class="card mb-5 mb-xl-8">
                            <!--begin::Card body-->
                            <div class="card-body pt-15">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-150px symbol-lg-150px symbol-fixed position-relative">
                                        <img src="{{$fileRepository->getFileSource($data['file_id'])}}"
                                             alt="image"/>
                                    </div>
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--begin::Details content-->
                                <div class="pb-5 fs-6">
                                    <!--begin::Details item-->
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.User')
                                        </div>
                                        <div class="col-md-6">
                                            {{$request->author?->socialUser?->getFullName()}}
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Certificate File')
                                        </div>
                                        <div class="col-md-6">
                                            @php if(!empty($data['certificate_file_id'])): @endphp
                                            <a href="{{$fileRepository->getFileSource($data['certificate_file_id'])}}"
                                               class="fs-6 mb-3 btn-sm btn btn-primary">@lang('Download')
                                                <span class="fa fa-download"></span></a>
                                            @php endif; @endphp
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="fw-bold  col-md-6">
                                            @lang('client.Status')
                                        </div>
                                        <div class="col-md-6">
                                            @if($request->status == RequestStatusEnum::_CHECKING->value)
                                                <span class="fs-4 badge badge-light-warning">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_APPROVED->value)
                                                <span class="fs-4 badge badge-light-success">{{$request->status}}</span>
                                            @elseif($request->status == RequestStatusEnum::_REJECTED->value)
                                                <span class="fs-4 badge badge-light-danger">{{$request->status}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                    <!--begin::Main column-->
                    <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                        <div class="card mb-1 mb-xl-8">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>@lang('client.Model Data')</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_oz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name_oz" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.name_oz') value="{{$data['name_oz']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_uz')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name_uz" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.name_uz') value="{{$data['name_uz']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.name_ru')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name_ru" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.name_ru') value="{{$data['name_ru']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.inn')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="inn" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.inn') value="{{$data['inn']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.email')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="inn" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.email') value="{{$data['email']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.phone')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="phone" readonly class="form-control mb-2"
                                                   placeholder=@lang('model.phone') value="{{$data['phone']}}"/>
                                            <!--end::Input-->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.address')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <!--end::Input-->
                                            <textarea name="address" readonly class="form-control mb-2"
                                                      placeholder=@lang('model.address')>{{$data['address']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-10 fv-row mt-3">
                                            <!--begin::Label-->
                                            <label class="required form-label">@lang('model.description')</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <!--end::Input-->
                                            <textarea name="description" readonly class="form-control mb-2" rows="3"
                                                      placeholder=@lang('model.description')>{{$data['description']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            @if($request->getStatus() == RequestStatusEnum::_CHECKING->value)
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_1">
                                    @lang('button.Reject')
                                </button>
                                <button type="button" style="margin: 5px" class="btn btn-sm fw-bold btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_stacked_2">
                                    @lang('button.Confirm')
                                </button>
                            @endif
                            <!--end::Button-->
                        </div>

                    </div>
                    <!--end::Main column-->
                </div>
            @endif

            <div class="modal fade" tabindex="-1" id="kt_modal_stacked_1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">@lang('client.Reject')</h3>
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                 aria-label="Close">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <form id="request_cancel_form" method="post"
                                  action="{{ route('request.reject', ['id' => $request->getId()]) }}">
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm fw-bold btn-light"
                                            data-bs-dismiss="modal">@lang('button.Close')</button>
                                    <button type="button" id="request_cancel_button"
                                            class="btn btn-sm fw-bold btn-primary">@lang('button.Save')</button>
                                </div>
                            </form>
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
                            <form id="request_confirm_form" method="post"
                                  action="{{ route('request.confirm', ['id' => $request->getId()]) }}">
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
                            <button type="button" id="request_confirm_button"
                                    class="btn btn-sm fw-bold btn-primary">@lang('button.Save')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@stop

