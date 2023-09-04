<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar__brand-wrapper navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($restaurant_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value)
                <a class="navbar-brand d-block p-0" href="{{ route('admin.dashboard') }}" aria-label="Front">
                    <img class="navbar-brand-logo sidebar--logo-design"
                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                    <img class="navbar-brand-logo-mini sidebar--logo-design-2"
                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                        src="{{ asset('storage/app/public/business/' . $restaurant_logo) }}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                    class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="navbar-nav-wrap-content-left d-none d-xl-block">
                    <!-- Navbar Vertical Toggle -->
                    <button type="button" class="js-navbar-vertical-aside-toggle-invoker close">
                        <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                            data-placement="right" title="Collapse"></i>
                        <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                            data-template='<div class="tooltip d-none" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                            data-toggle="tooltip" data-placement="right" title="Expand"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

            </div>

            <!-- Content -->
            <div class="navbar-vertical-content bg--title" id="navbar-vertical-content">
                <!-- Search Form -->
                <form class="sidebar--search-form">
                    <div class="search--form-group">
                        <button type="button" class="btn"><i class="tio-search"></i></button>
                        <input type="text" id="search" class="form-control form--control"
                            placeholder="{{ translate('Search Menu...') }}">
                    </div>
                </form>
                <!-- Search Form -->
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.dashboard') }}"
                            title="{{ translate('messages.dashboard') }}">
                            <i class="tio-dashboard-vs nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('pos'))
                        {{-- <li class="nav-item">
                            <small class="nav-subtitle">{{ translate('POS') }}
                                {{ translate('System') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li> --}}
                        <!-- POS -->
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/pos') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.pos.index') }}" title="{{ translate('messages.pos') }}">
                                <i class="tio-receipt nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.pos') }}</span>
                            </a>
                        </li>
                        <!-- End POS -->
                    @endif

                    <!-- Marketing section -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.employee_handle') }}">{{ translate('Promotions') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- Campaign -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('campaign'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/campaign*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.campaigns') }}">
                                <i class="tio-notice nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.campaigns') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/campaign*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/campaign/basic/*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.campaign.list', 'basic') }}"
                                        title="{{ translate('messages.basic_campaign') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.basic_campaign') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/campaign/item/*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.campaign.list', 'item') }}"
                                        title="{{ translate('messages.food_campaign') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.food_campaign') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Campaign -->
                    <!-- Banner -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('banner'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/banner*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.banner.add-new') }}"
                                title="{{ translate('messages.banners') }}">
                                <i class="tio-bookmark nav-icon side-nav-icon--design"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.banners') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Banner -->
                    <!-- Coupon -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('coupon'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/coupon*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.coupon.add-new') }}"
                                title="{{ translate('messages.coupons') }}">
                                <i class="tio-ticket nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.coupons') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Coupon -->
                    <!-- Notification -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('notification'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/notification*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.notification.add-new') }}"
                                title="{{ translate('messages.push') }} {{ translate('messages.notification') }}">
                                <i class="tio-notifications-on nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.push') }} {{ translate('messages.notification') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    <!-- End Notification -->
                    <!-- Orders -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('order'))
                        <li class="nav-item">
                            <small class="nav-subtitle">{{ translate('messages.order') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/order*')  ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.orders') }}">
                                <i class="tio-file-text-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.orders') }}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/order*') && (Request::is('admin/order/subscription*') == false &&  Request::is('admin/order-cancel-reasons') ==false) ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/order/list/all') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.order.list', ['all']) }}"
                                        title="{{ translate('messages.all') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.all') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Notpos()->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/scheduled') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.order.list', ['scheduled']) }}"
                                        title="{{ translate('messages.scheduled') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.scheduled') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Scheduled()->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/pending') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['pending']) }}"
                                        title="{{ translate('messages.pending') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.pending') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::Pending()->HasSubscriptionToday()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/order/list/accepted') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['accepted']) }}"
                                        title="{{ translate('messages.accepted') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.accepted') }}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{ \App\Models\Order::AccepteByDeliveryman()->HasSubscriptionToday()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/processing') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['processing']) }}"
                                        title="{{ translate('messages.processing') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.processing') }}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{ \App\Models\Order::whereIn('order_status', ['confirmed','processing','handover'])->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/order/list/food_on_the_way') ? 'active' : '' }}">
                                    <a class="nav-link text-capitalize"
                                        href="{{ route('admin.order.list', ['food_on_the_way']) }}"
                                        title="{{ translate('messages.foodOnTheWay') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.foodOnTheWay') }}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                                {{ \App\Models\Order::FoodOnTheWay()->HasSubscriptionToday()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/delivered') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['delivered']) }}"
                                        title="{{ translate('messages.delivered') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.delivered') }}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                                {{ \App\Models\Order::Delivered()->HasSubscriptionToday()->Notpos()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/canceled') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['canceled']) }}"
                                        title="{{ translate('messages.canceled') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.canceled') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::Canceled()->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/failed') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['failed']) }}"
                                        title="{{ translate('messages.payment') }} {{ translate('messages.failed') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container text-capitalize">
                                            {{ translate('messages.payment') }} {{ translate('messages.failed') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::failed()->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/order/list/refunded') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.order.list', ['refunded']) }}"
                                        title="{{ translate('messages.refunded') }} {{ translate('messages.orders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.refunded') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::Refunded()->HasSubscriptionToday()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Order refund -->
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/refund/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('Order Refunds') }}">
                                <i class="tio-receipt nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('Order Refunds') }}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/refund*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/refund/requested') || Request::is('admin/refund/refunded') ||
                                Request::is('admin/refund/rejected') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.refund.refund_attr', ['requested']) }}"
                                        title="{{ translate('Refund Requests') }} ">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.Refund Requests') }}
                                            <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{ \App\Models\Order::Refund_requested()->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/refund/settings') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.refund.refund_settings') }}"
                                        title="{{ translate('messages.refund_settings') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.refund_settings') }}

                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order refund End-->


                        <!-- Order dispachment -->
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/dispatch/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.dispatchManagement') }}">
                                <i class="tio-clock nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.dispatchManagement') }}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/dispatch*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/dispatch/list/searching_for_deliverymen') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.dispatch.list', ['searching_for_deliverymen']) }}"
                                        title="{{ translate('messages.searchingDM') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">
                                            {{ translate('messages.searchingDM') }}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{ \App\Models\Order::SearchingForDeliveryman()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/dispatch/list/on_going') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.dispatch.list', ['on_going']) }}"
                                        title="{{ translate('messages.ongoingOrders') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate sidebar--badge-container">
                                            {{ translate('messages.ongoingOrders') }}
                                            <span class="badge badge-soft-dark bg-light badge-pill ml-1">
                                                {{ \App\Models\Order::Ongoing()->OrderScheduledIn(30)->count() }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Order dispachment End-->



                    <!-- Order Subscription -->
                    {{-- <li class="nav-item">
                        <small class="nav-subtitle" title="{{ translate('messages.order') }} {{ translate('subscription_management') }}">
                            {{ translate('messages.subscription_management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li> --}}

                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/order/subscription*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.order.subscription.index') }}" title="{{ translate('messages.order') }} {{ translate('messages.subscriptiona') }}">
                            <i class="tio-appointment nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.order') }}  {{ translate('messages.subscription') }} </span>
                        </a>
                    </li>


                        <li class="navbar-vertical-aside-has-menu  {{ Request::is('admin/order-cancel-reasons') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.order-cancel-reasons.index') }}"
                            title="{{translate('messages.order_cancellation_reasons')}}">
                            <i class="tio-settings nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('messages.order_cancellation_reasons')}}
                            </span>
                            </a>
                        </li>
                    @endif
                    <!-- End Orders -->

                    <!-- Restaurant -->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.restaurant') }} {{ translate('messages.section') }}">{{ translate('messages.restaurant') }}
                            {{ translate('messages.management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if (\App\CentralLogics\Helpers::module_permission_check('zone'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/zone*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.zone.home') }}"
                                title="{{ translate('messages.zone') }} {{ translate('messages.setup') }}">
                                <i class="tio-poi-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.zone') }} {{ translate('messages.setup') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('restaurant'))
                    <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/cuisine/add') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.cuisine.add') }}"
                            title="{{ translate('messages.cuisine') }}">
                            <i class="tio-link nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{ translate('messages.cuisine') }}
                        </a>
                    </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/restaurant*') && !Request::is('admin/restaurant/withdraw_list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.restaurants') }}">
                                <i class="tio-restaurant nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurants') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/restaurant*') && !Request::is('admin/restaurant/withdraw_list') ? 'block' : 'none' }}">
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/restaurant/add') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.restaurant.add') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.restaurant') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('messages.add') }} {{ translate('messages.restaurant') }}
                                        </span>
                                    </a>
                                </li>

                                <li class="navbar-item {{ Request::is('admin/restaurant/list') ||  Request::is('admin/restaurant/transcation/*') ? 'active' : '' }} {{ Request::is('admin/restaurant/view*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.restaurant.list') }}"
                                        title="{{ translate('messages.restaurants') }} {{ translate('list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurants') }}
                                            {{ translate('list') }}</span>
                                    </a>
                                </li>

                                <li class="navbar-item {{ Request::is('admin/restaurant/pending/list*') ||  Request::is('admin/restaurant/denied/list*') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.restaurant.pending') }}"
                                        title="{{ translate('messages.New_joining_request') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span  class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.New_joining_request') }}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/restaurant/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.restaurant.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item {{ Request::is('admin/restaurant/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.restaurant.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    {{-- @endif --}}
                    <!-- End Restaurant -->






                    @if (\App\CentralLogics\Helpers::subscription_check() == true  )
                    <!-- Subscription-->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.employee_handle') }}">{{ translate('Subscription') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    {{-- @if (\App\CentralLogics\Helpers::module_permission_check('subscription')) --}}
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/subscription*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.subscription') }}">
                                <i class="tio-crown  nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.subscription') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/subscription*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/subscription/package/*') || Request::is('admin/subscription/search')  ||  Request::is('admin/subscription/transcation/list/*')? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.subscription.package_list') }}"
                                        title="{{ translate('messages.Package') }}  {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.Package') }}  {{ translate('messages.list') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/subscription/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.subscription.subscription_list') }}"
                                        title="{{ translate('messages.subscription') }}  {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('Subscribers') }}  {{ translate('messages.list') }}</span>
                                    </a>
                                </li>
                                </li>
                                <li class="nav-item {{ Request::is('admin/subscription/settings') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.subscription.settings') }}"
                                        title="{{ translate('messages.settings') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.settings') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Subscription -->
                    @endif

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.food') }} {{ translate('messages.section') }}">{{ translate('messages.food') }}
                            {{ translate('messages.management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <!-- Category -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('category'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/category*')  ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.categories') }}">
                                <i class="tio-category nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.categories') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/category*') ? 'block' : 'none' }}">


                                <li class="nav-item {{ Request::is('admin/category/add') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.add') }}"
                                        title="{{ translate('messages.category') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.category') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/category/add-sub-category') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.add-sub-category') }}"
                                        title="{{ translate('messages.sub_category') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.sub_category') }}</span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item {{Request::is('admin/category/add-sub-sub-category')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.category.add-sub-sub-category')}}"
                                        title="add new sub sub category">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">Sub-Sub-Category</span>
                                    </a>
                                </li> --}}
                                <li class="nav-item {{ Request::is('admin/category/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/category/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.category.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Category -->

                    <!-- Attributes -->
                    {{-- @if (\App\CentralLogics\Helpers::module_permission_check('attribute'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/attribute*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.attribute.add-new') }}"
                                title="{{ translate('messages.attributes') }}">
                                <i class="tio-apps nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.attributes') }}
                                </span>
                            </a>
                        </li>
                    @endif --}}
                    <!-- End Attributes -->

                    <!-- AddOn -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('addon'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/addon*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.addons') }}">
                                <i class="tio-add-circle-outlined nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.addons') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/addon*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/addon/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.add-new') }}"
                                        title="{{ translate('messages.addon') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{ Request::is('admin/addon/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/addon/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.addon.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End AddOn -->
                    <!-- Food -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('food'))
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/food*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.foods') }}">
                                <i class="tio-fastfood nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.foods') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/food*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/food/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.add-new') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.new') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add') }}
                                            {{ translate('messages.new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.list') }}"
                                        title="{{ translate('messages.food') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/reviews') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.reviews') }}"
                                        title="{{ translate('messages.review') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.review') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/bulk-import') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.bulk-import') }}"
                                        title="{{ translate('Bulk Import') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_import') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/food/bulk-export') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.food.bulk-export-index') }}"
                                        title="{{ translate('Bulk Export') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bulk_export') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <!-- End Food -->
                    <!-- DeliveryMan -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('deliveryman'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.deliveryman') }} {{ translate('messages.section') }}">{{ translate('messages.deliveryman') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/vehicle/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.vehicle.list') }}"
                                title="{{ translate('messages.vehicles_category') }}">
                                <i class="tio-car nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.vehicles_category') }}
                                </span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/shift*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.shift.list') }}"
                                title="{{ translate('messages.Shift_setup') }}">
                                <i class="tio-calendar nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.Shift_setup') }}
                                </span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.deliveryman') }}">
                                <i class="tio-running nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.deliveryman') }}</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/delivery-man*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/delivery-man/add') ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.delivery-man.add') }}"
                                        title="{{ translate('messages.add_delivery_man') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('messages.add_delivery_man') }}
                                        </span>
                                    </a>
                                </li>

                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/list') || Request::is('admin/delivery-man/preview/*')? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                    href="{{ route('admin.delivery-man.list') }}"
                                    title="{{ translate('messages.deliveryman') }} {{ translate('messages.list') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{ translate('messages.deliveryman') }} {{ translate('messages.list') }}
                                    </span>
                                </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/pending/list') || Request::is('admin/delivery-man/denied/list') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{ route('admin.delivery-man.pending') }}"
                                        title="{{ translate('messages.New_joining_request') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('messages.New_joining_request') }}
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/incentive') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                                        href="{{  route('admin.delivery-man.incentive')}}"
                                        title="{{ translate('messages.deliveryman') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ translate('messages.incentives') }}
                                        </span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/incentive-history') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.delivery-man.incentive-history') }}"
                                        title="{{ translate('messages.incentives_history') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.incentives_history') }}</span>
                                    </a>
                                </li>
                                <li
                                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/bonus') ? 'active' : '' }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('admin.delivery-man.bonus') }}"
                                        title="{{ translate('messages.bonus') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.bonus') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/delivery-man/reviews/list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.delivery-man.reviews.list') }}"
                                title="{{ translate('messages.reviews') }}">
                                <i class="tio-star-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.reviews') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    <!-- End DeliveryMan -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('customerList'))
                        <!-- Custommer -->
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.customer') }} {{ translate('messages.section') }}">{{ translate('messages.customer') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>


                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/list') || Request::is('admin/customer/view*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.list') }}"
                                title="{{ translate('Customer List') }}">
                                <i class="tio-poi-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.customer') }} {{ translate('messages.list') }}
                                </span>
                            </a>
                        </li>
                        {{-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/logs') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.visitor_logs') }}"
                                title="{{ translate('Customer_Logs') }}">
                                <i class="tio-poi-user nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.customer') }} {{ translate('messages.Logs') }}
                                </span>
                            </a>
                        </li> --}}

                        @if (\App\CentralLogics\Helpers::module_permission_check('customer_wallet'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/wallet*') ? 'active' : '' }}">

                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('Customer Wallet') }}">
                                <i class="tio-wallet nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate  text-capitalize">
                                    {{ translate('messages.customer') }} {{ translate('messages.wallet') }}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/customer/wallet*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/customer/wallet/add-fund') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.customer.wallet.add-fund') }}"
                                        title="{{ translate('messages.add_fund') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.add_fund') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/customer/wallet/report*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.customer.wallet.report') }}"
                                        title="{{ translate('messages.report') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.report') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/loyalty-point*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link  nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.customer_loyalty_point') }}">
                                <i class="tio-medal nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate  text-capitalize">
                                    {{ translate('messages.customer_loyalty_point') }}
                                </span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/customer/loyalty-point*') ? 'block' : 'none' }}">
                                <li
                                    class="nav-item {{ Request::is('admin/customer/loyalty-point/report*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.customer.loyalty-point.report') }}"
                                        title="{{ translate('messages.report') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate text-capitalize">{{ translate('messages.report') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/subscribed') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.subscribed') }}"
                                title="{{ translate('Subscribed Emails') }}">
                                <i class="tio-email-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.subscribed_mail_list') }}
                                </span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/customer/settings') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.customer.settings') }}"
                                title="{{ translate('messages.Customer') }} {{ translate('messages.settings') }}">
                                <i class="tio-settings nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.Customer') }} {{ translate('messages.settings') }}
                                </span>
                            </a>
                        </li>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/message/list') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.message.list') }}"
                                title="{{ translate('messages.Customer') }} {{ translate('messages.chat') }}">
                                <i class="tio-chat nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('messages.Customer') }} {{ translate('messages.chat') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    @if (\App\CentralLogics\Helpers::module_permission_check('contact_message'))
                    <li
                    class="navbar-vertical-aside-has-menu {{ Request::is('admin/contact/*') ? 'active' : '' }}">
                    <a class="js-navbar-vertical-aside-menu-link nav-link"
                        href="{{ route('admin.contact.list') }}"
                        title="{{ translate('Contact_messages') }}">
                        <i class="tio-messages nav-icon"></i>
                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                            {{ translate('messages.Contact_messages') }}
                        </span>
                    </a>
                    </li>
                    @endif

                    <!-- End Custommer -->


                    <!-- Employee-->

                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.employee_handle') }}">{{ translate('Employee Management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    @if (\App\CentralLogics\Helpers::module_permission_check('custom_role'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/custom-role*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.custom-role.create') }}"
                                title="{{ translate('messages.employee') }} {{ translate('messages.Role') }}">
                                <i class="tio-incognito nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employee') }}
                                    {{ translate('messages.Role') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::module_permission_check('employee'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/employee*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('Employees') }}">
                                <i class="tio-user nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.employees') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/employee*') ? 'block' : 'none' }}">
                                <li class="nav-item {{ Request::is('admin/employee/add-new') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.employee.add-new') }}"
                                        title="{{ translate('messages.add') }} {{ translate('messages.new') }} {{ translate('messages.Employee') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.add') }}
                                            {{ translate('messages.new') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('admin/employee/list') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.employee.list') }}"
                                        title="{{ translate('messages.Employee') }} {{ translate('messages.list') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.list') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    <!-- End Employee -->




                    <!-- Report -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('report'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.report_and_analytics') }}">{{ translate('messages.report') }}
                                {{ translate('messages.management') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/transaction-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.day-wise-report') }}"
                                title="{{ translate('messages.transaction_report') }}">
                                <span class="tio-chart-pie-1 nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.transaction_report') }}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/food-wise-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.food-wise-report') }}"
                                title="{{ translate('messages.food_report') }}">
                                <span class="tio-fastfood nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.food_report') }}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/order-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.order-report') }}" title="{{ translate('messages.order_report') }}">
                                <span class="tio-money nav-icon"></span>
                                <span class="text-truncate text-capitalize">{{ translate('messages.order_report') }}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/campaign-order-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.campaign_order-report') }}" title="{{ translate('messages.Campaign_Order_Report') }}">
                                <span class="tio-voice nav-icon"></span>
                                <span class="text-truncate text-capitalize">{{ translate('messages.Campaign_Order_Report') }}</span>
                            </a>
                        </li>

                        @if (\App\CentralLogics\Helpers::subscription_check() == true  )
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/subscription-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.subscription-report') }}"
                                title="{{ translate('messages.Subscription_report') }}">
                                <span class="tio-subscribe nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.Subscription_report') }}</span>
                            </a>
                        </li>
                        @endif

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/restaurant-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.restaurant-report') }}"
                                title="{{ translate('messages.restaurant_report') }}">
                                <span class="tio-files nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.restaurant_report') }}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/report/expense-report') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.report.expense-report') }}" title="{{ translate('messages.expense_report') }}">
                                <span class="tio-image  nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.expense_report') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- Report -->
                    <!-- Business Settings -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.business') }} {{ translate('messages.settings') }}">{{ translate('messages.business') }}
                                {{ translate('messages.settings') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/business-setup') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.business-setup') }}"
                                title="{{ translate('messages.business') }} {{ translate('messages.setup') }}">
                                <span class="tio-settings nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.business') }}
                                    {{ translate('messages.setup') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/language*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{route('admin.language.index')}}"
                                title="{{ translate('messages.Language') }}">
                                <i class="tio-globe nav-icon"></i>
                                <span class="text-truncate">{{ translate('messages.Language') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/social-media') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.social-media.index') }}"
                                title="{{ translate('messages.Social Media') }}">
                                <span class="tio-slack nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.Social Media') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/payment-method') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.payment-method') }}"
                                title="{{ translate('messages.payment') }} {{ translate('messages.methods') }}">
                                <span class="tio-atm nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.payment') }}
                                    {{ translate('messages.methods') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/mail-config') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.mail-config') }}"
                                title="{{ translate('messages.mail') }} {{ translate('messages.config') }}">
                                <span class="tio-email nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.mail') }}
                                    {{ translate('messages.config') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/sms-module') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.sms-module') }}"
                                title="{{ translate('messages.sms') }} {{ translate('messages.module') }}">
                                <span class="tio-sms-active nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.sms') }}
                                    {{ translate('messages.module') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/fcm-index') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.fcm-index') }}"
                                title="{{ translate('messages.notification') }} {{ translate('messages.settings') }}">
                                <span class="tio-notifications-on nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.notification') }}
                                    {{ translate('messages.settings') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End Business Settings -->




                    <!-- web & adpp Settings -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/pages*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                                title="{{ translate('messages.pages') }} {{ translate('messages.setup') }}">
                                <i class="tio-pages nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.pages') }}
                                    {{ translate('messages.setup') }}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/business-settings/pages*') ? 'block' : 'none' }}">

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/terms-and-conditions') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.business-settings.terms-and-conditions') }}"
                                        title="{{ translate('messages.terms_and_condition') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">{{ translate('messages.terms_and_condition') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/privacy-policy') ? 'active' : '' }}">
                                    <a class="nav-link "
                                        href="{{ route('admin.business-settings.privacy-policy') }}"
                                        title="{{ translate('messages.privacy_policy') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.privacy_policy') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/business-settings/pages/about-us') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.business-settings.about-us') }}"
                                        title="{{ translate('messages.about_us') }}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{ translate('messages.about_us') }}</span>
                                    </a>
                                </li>


                            <li class="nav-item {{ Request::is('admin/business-settings/pages/refund-policy') ? 'active' : '' }}">
                                <a class="nav-link "
                                    href="{{ route('admin.business-settings.refund-policy') }}"
                                    title="{{ translate('messages.refund_policy') }}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{ translate('messages.refund_policy') }}</span>
                                </a>
                            </li>


                        <li class="nav-item {{ Request::is('admin/business-settings/pages/shipping-policy') ? 'active' : '' }}">
                            <a class="nav-link "
                                href="{{ route('admin.business-settings.shipping-policy') }}"
                                title="{{ translate('messages.shipping_policy') }}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{ translate('messages.shipping_policy') }}</span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('admin/business-settings/pages/cancellation-policy') ? 'active' : '' }}">
                            <a class="nav-link "
                                href="{{ route('admin.business-settings.cancellation-policy') }}"
                                title="{{ translate('messages.cancellation_policy') }}">
                                <span class="tio-circle nav-indicator-icon"></span>
                                <span class="text-truncate">{{ translate('messages.cancellation_policy') }}</span>
                            </a>
                        </li>


                            </ul>
                        </li>
                        <li class="nav-item">
                            <small class="nav-subtitle"
                                title="{{ translate('messages.business') }} {{ translate('messages.settings') }}">{{ translate('messages.system') }}
                                {{ translate('messages.settings') }}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/theme-settings*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.theme-settings') }}"
                                title="{{ translate('theme_settings') }}">
                                <span class="tio-brush nav-icon"></span>
                                <span class="text-truncate">{{ translate('theme_settings') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/app-settings*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.app-settings') }}"
                                title="{{ translate('messages.app_settings') }}">
                                <span class="tio-android nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.app_settings') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/landing-page-settings*') ? 'active' : '' }}">
                            <a class="nav-link "
                                href="{{ route('admin.business-settings.landing-page-settings', 'index') }}"
                                title="{{ translate('messages.landing_page_settings') }}">
                                <span class="tio-website nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.landing_page_settings') }}</span>
                            </a>
                        </li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/config*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.config-setup') }}"
                                title="{{ translate('messages.third_party_apis') }}">
                                <span class="tio-key nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.third_party_apis') }}</span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/react*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.react-setup') }}"
                                title="{{ translate('messages.react_site') }}">
                                <span class="tio-rear-window-defrost nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.react_site') }}</span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/file-manager*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.file-manager.index') }}"
                                title="{{ translate('messages.gallery') }}">
                                <span class="tio-album nav-icon"></span>
                                <span
                                    class="text-truncate text-capitalize">{{ translate('messages.gallery') }}</span>
                            </a>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/social-login/view')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('admin.social-login.view')}}">
                                <i class="tio-twitter nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('messages.social_login')}}
                                </span>
                            </a>
                        </li>

                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/recaptcha*') ? 'active' : '' }}">
                            <a class="nav-link " href="{{ route('admin.business-settings.recaptcha_index') }}"
                                title="{{ translate('messages.reCaptcha') }}">
                                <span class="tio-top-security-outlined nav-icon"></span>
                                <span class="text-truncate">{{ translate('messages.reCaptcha') }}</span>
                            </a>
                        </li>
                        {{-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/environment-setup') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business-settings.environment-setup') }}">
                                <i class="tio-labels nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('environment_setup') }}
                                </span>
                            </a>
                        </li> --}}
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/db-index') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.business-settings.db-index') }}"
                                title="{{ translate('clean_database') }}">
                                <i class="tio-cloud nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ translate('clean_database') }}
                                </span>
                            </a>
                        </li>
                    @endif
                    <!-- End web & adpp Settings -->






                    <!-- Business Section-->
                    <li class="nav-item">
                        <small class="nav-subtitle"
                            title="{{ translate('messages.business') }} {{ translate('messages.section') }}">{{ translate('messages.transaction') }}
                            {{ translate('messages.management') }}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <!-- account -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('account'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/account-transaction*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.account-transaction.index') }}"
                                title="{{ translate('messages.collect') }} {{ translate('messages.cash') }}">
                                <i class="tio-money nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.collect') }}
                                    {{ translate('messages.cash') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End account -->
                    <!-- withdraw -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('withdraw_list'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/restaurant/withdraw*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.restaurant.withdraw_list') }}"
                                title="{{ translate('messages.restaurant') }} {{ translate('messages.withdraws') }}">
                                <i class="tio-table nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.withdraws') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (\App\CentralLogics\Helpers::module_permission_check('settings'))
                        <li
                        class="navbar-vertical-aside-has-menu {{ Request::is('admin/business-settings/withdraw-method*') ? 'active' : '' }}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                            href="{{ route('admin.business-settings.withdraw-method.list') }}"
                            title="{{ translate('messages.restaurant') }} {{ translate('messages.withdraw_method') }}">
                            <i class="tio-savings nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('messages.restaurant') }}
                                {{ translate('messages.withdraw_method') }}</span>
                        </a>
                        </li>
                    @endif
                    <!-- End withdraw -->

                    <!-- provide_dm_earning -->
                    @if (\App\CentralLogics\Helpers::module_permission_check('provide_dm_earning'))
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/provide-deliveryman-earnings*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.provide-deliveryman-earnings.index') }}"
                                title="{{ translate('messages.deliveryman') }} Payments">
                                <i class="tio-send nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ translate('Delivery Man Payments') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- End provide_dm_earning -->


                    <li class="nav-item pt-100px">

                    </li>
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


@push('script_2')
    <script>
        $(window).on('load', function() {
            if ($(".navbar-vertical-content li.active").length) {
                $('.navbar-vertical-content').animate({
                    scrollTop: $(".navbar-vertical-content li.active").offset().top - 150
                }, 10);
            }
        });
    </script>
    <script>
        var $rows = $('#navbar-vertical-content li');
        $('#search').keyup(function() {
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

            $rows.show().filter(function() {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    </script>
@endpush
