@php use App\Core\Enums\Requests\RequestTypeEnum; @endphp
<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
    <!--begin::Scroll wrapper-->
    <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true"
         data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
         data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link">
												<span class="menu-icon">
													<i class="ki-duotone ki-element-11 fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
													</i>
												</span>
												<span class="menu-title">@lang('menu.Enums')</span>
												<span class="menu-arrow"></span>
											</span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-academic-degree.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Academic Degree')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-academic-position.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Academic Position')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-categories.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Categories')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-education-type.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Education Types')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-language.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Language')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-product-genre.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Product Genre')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-product-status.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Product Status')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-product-tag.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Product Tags')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('enum-product-type.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Enum Product Types')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('banner.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Main Banner')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('proverb.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Proverb Banner')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                             data-kt-menu="true" data-kt-menu-expand="false">
                            <!--begin:Menu item-->
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-people fs-1">
                                                 <span class="path1"></span>
                                                 <span class="path2"></span>
                                                 <span class="path3"></span>
                                                 <span class="path4"></span>
                                                 <span class="path5"></span>
                                            </i>
                                        </span>
                                        <span class="menu-title">{{ __('menu.Company Info') }}</span>
                                        <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('company.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Company')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('company-file.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Company File')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('company-partner.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Company Partner')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('company-social-network.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Company Social Network')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <!--end:Menu item-->

                                </div>
                                <!--end:Menu sub-->
                            </div>
                        </div>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                             data-kt-menu="true" data-kt-menu-expand="false">
                            <!--begin:Menu item-->
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                <!--begin:Menu link-->
                                <span class="menu-link">
                                        <span class="menu-icon">
                                            <i class="ki-duotone ki-people fs-1">
                                                 <span class="path1"></span>
                                                 <span class="path2"></span>
                                                 <span class="path3"></span>
                                                 <span class="path4"></span>
                                                 <span class="path5"></span>
                                            </i>
                                        </span>
                                        <span class="menu-title">{{ __('menu.Questions') }}</span>
                                        <span class="menu-arrow"></span>
                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-accordion">
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('question.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Questions')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link"
                                           href="{{ route('question-answer.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                            <span class="menu-title">@lang('menu.Question Answers')</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                </div>
                                <!--end:Menu sub-->
                            </div>
                        </div>
                        <!--end:Menu link-->
                    </div>
                </div>
                <!--end:Menu sub-->
            </div>
        </div>
        <!--end::Menu-->
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-people fs-1">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                             <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">{{ __('menu.Requests') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('request.filter', ['type' => RequestTypeEnum::_AUTHOR->value]) }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Author Request')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('request.filter', ['type' => RequestTypeEnum::_AUTHORITY->value]) }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Authority Request')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->

                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('request.filter', ['type' => RequestTypeEnum::_PRODUCT->value]) }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Product Request')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
        </div>
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-people fs-1">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                             <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">{{ __('menu.Products') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('product.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Product List')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
        </div>
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-people fs-1">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                             <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">{{ __('menu.Author and authority') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('author.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Authors')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('authority.filter') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Authority')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end:Menu sub-->
            </div>
        </div>
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
             data-kt-menu="true" data-kt-menu-expand="false">
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-people fs-1">
                             <span class="path1"></span>
                             <span class="path2"></span>
                             <span class="path3"></span>
                             <span class="path4"></span>
                             <span class="path5"></span>
                        </i>
                    </span>
                    <span class="menu-title">{{ __('menu.Reports') }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('report.userRegisterSearch') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.User Register')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('report.productOrderSearch') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Product Order')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('report.topBuyers') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Top Buyers List')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <div class="menu-sub menu-sub-accordion">
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link"
                           href="{{ route('report.topProducts') }}">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                            <span class="menu-title">@lang('menu.Top Products List')</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                </div>
                <!--end:Menu sub-->
            </div>
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Scroll wrapper-->
</div>
