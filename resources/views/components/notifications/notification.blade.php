@php use App\Core\Repository\Notification\NotificationRepository; @endphp
@php
    /**
    * @var NotificationRepository $notificationRepository
    */
    $notificationRepository = app(NotificationRepository::class);

    $all = $notificationRepository->findAllByActive();

@endphp

@if($all->count() > 0)
    <!--begin::Notifications-->
    <div class="app-navbar-item ms-1 ms-md-4">
        <!--begin::Menu- wrapper-->
        <div
                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
            <i class="ki-duotone ki-notification-status fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </div>
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true"
             id="kt_menu_notifications">
            <!--begin::Heading-->
            <div class="d-flex flex-column bgi-no-repeat rounded-top"
                 style="background-image:url('assets/media/misc/menu-header-bg.jpg')">
                <!--begin::Title-->
                <h3 class="text-white fw-semibold px-9 mt-10 mb-6">@lang('button.Notifications')
                    <span class="badge badge-danger">{{ $all->count() }}</span></h3>
                <!--end::Title-->
                <!--begin::Tabs-->
                <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4"
                           data-bs-toggle="tab"
                           href="#kt_topbar_notifications_1">@lang('button.Authors')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4"
                           data-bs-toggle="tab"
                           href="#kt_topbar_notifications_2">@lang('button.Products')</a>
                    </li>
                </ul>
                <!--end::Tabs-->
            </div>
            <!--end::Heading-->
            <!--begin::Tab content-->
            <div class="tab-content">
                <!--begin::Tab panel-->
                <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
                    <!--begin::Items-->
                    <div class="scroll-y mh-325px my-5 px-8">
                        @foreach($all->where('notification_type_id', 1)->all() as $key => $item)
                            <!--begin::Item-->
                            <div class="d-flex flex-stack py-4">
                                <!--begin::Section-->
                                <div class="d-flex align-items-center me-2">
                                    <!--begin::Code-->
                                    <span class="alert alert-primary">
                                        <a href="">
                                            {{ $item['message_oz'] }}
                                        </a>
                                    </span>
                                    <!--end::Code-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Item-->
                        @endforeach
                    </div>
                    <!--end::Items-->
                    <!--begin::View more-->
                    <div class="py-3 text-center border-top">
                        <a href=""
                           class="btn btn-color-gray-600 btn-active-color-primary">
                            @lang('button.View All')
                            <i class="ki-duotone ki-arrow-right fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i></a>
                    </div>
                    <!--end::View more-->
                </div>
                <!--end::Tab panel-->
                <!--begin::Tab panel-->
                <div class="tab-pane fade" id="kt_topbar_notifications_2" role="tabpanel">

                </div>
                <!--end::Tab panel-->

            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::Notifications-->
@endif
