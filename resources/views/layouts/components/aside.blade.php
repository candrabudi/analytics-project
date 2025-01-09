<aside class="app-sidebar sticky" id="sidebar">

    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="{{ asset('assets/images/brand-logos/tensan_logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/images/brand-logos/tensan_logo.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/images/brand-logos/tensan_logo.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/images/brand-logos/tensan_logo.png') }}" alt="logo" class="toggle-logo">
        </a>
    </div>

    <div class="main-sidebar" id="sidebar-scroll">

        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <li class="slide__category"><span class="category-name">Main</span></li>
                <li class="slide">
                    <a href="{{ route('dashboard') }}" class="side-menu__item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="32" height="32"
                            viewBox="0 0 256 256">
                            <path
                                d="M216,115.54V208a8,8,0,0,1-8,8H160a8,8,0,0,1-8-8V160a8,8,0,0,0-8-8H112a8,8,0,0,0-8,8v48a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V115.54a8,8,0,0,1,2.62-5.92l80-75.54a8,8,0,0,1,10.77,0l80,75.54A8,8,0,0,1,216,115.54Z"
                                opacity="0.2"></path>
                            <path
                                d="M218.83,103.77l-80-75.48a1.14,1.14,0,0,1-.11-.11,16,16,0,0,0-21.53,0l-.11.11L37.17,103.77A16,16,0,0,0,32,115.55V208a16,16,0,0,0,16,16H96a16,16,0,0,0,16-16V160h32v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V115.55A16,16,0,0,0,218.83,103.77ZM208,208H160V160a16,16,0,0,0-16-16H112a16,16,0,0,0-16,16v48H48V115.55l.11-.1L128,40l79.9,75.43.11.1Z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Dashboards</span>
                    </a>
                </li>

                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'kol')
                    <li class="slide__category"><span class="category-name">KOL</span></li>
                    <li class="slide">
                        <a href="/scrap-engagement" class="side-menu__item">
                            <i class="side-menu__icon ri-bubble-chart-line"></i>
                            <span class="side-menu__label">Scrape Engagement</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="/scrap-engagement" class="side-menu__item">
                            <i class="side-menu__icon ri-article-line"></i>
                            <span class="side-menu__label">KOL Management</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="/scrap-engagement" class="side-menu__item">
                            <i class="side-menu__icon ri-send-plane-line"></i>
                            <span class="side-menu__label">KOL Post</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="/scrap-engagement" class="side-menu__item">
                            <i class="side-menu__icon ri-file-chart-line"></i>
                            <span class="side-menu__label">Invoice Tiktok</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="/scrap-engagement" class="side-menu__item">
                            <i class="side-menu__icon ri-pages-line"></i>
                            <span class="side-menu__label">Tipe Influencer</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="{{ route('kol.master') }}" class="side-menu__item">
                            <i class="side-menu__icon ri-mastercard-line"></i>
                            <span class="side-menu__label">KOL Master</span>
                        </a>
                    </li>
                @endif
                
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'affiliate')
                    <li class="slide__category"><span class="category-name">Affiliate</span></li>
                    <li class="slide has-sub open">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-id-card-line"></i>
                            <span class="side-menu__label">Scrape Username</span>
                            <i class="ri-arrow-down-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1"
                            style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(10px, 1093px); display: block; box-sizing: border-box;"
                            data-popper-placement="bottom" data-popper-escaped="">
                            <li class="slide">
                                <a href="/scrape-username/search" class="side-menu__item">Cari Data</a>
                            </li>
                            <li class="slide">
                                <a href="/scrape-username/history" class="side-menu__item">History Scrap</a>
                            </li>
                        </ul>
                    </li>
                @endif


                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'advertiser')
                    <li class="slide__category"><span class="category-name">Advertiser</span></li>
                    <li class="slide">
                        <a href="{{ route('advertiser.dashboard') }}" class="side-menu__item">
                            <i class="side-menu__icon ri-meta-line"></i>
                            <span class="side-menu__label">Meta Report</span>
                        </a>
                    </li>

                    <li class="slide has-sub open">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-speed-up-line"></i>
                            <span class="side-menu__label">Landingpage</span>
                            <i class="ri-arrow-down-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1"
                            style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(10px, 1093px); display: block; box-sizing: border-box;"
                            data-popper-placement="bottom" data-popper-escaped="">
                            <li class="slide">
                                <a href="{{ route('landingpages.list') }}" class="side-menu__item">Rank</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('landingpages.performance') }}"
                                    class="side-menu__item">Performance</a>
                            </li>
                        </ul>
                    </li>

                    <li class="slide has-sub open">
                        <a href="javascript:void(0);" class="side-menu__item">
                            <i class="side-menu__icon ri-database-2-line"></i>
                            <span class="side-menu__label">Database</span>
                            <i class="ri-arrow-down-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1"
                            style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(10px, 1093px); display: block; box-sizing: border-box;"
                            data-popper-placement="bottom" data-popper-escaped="">
                            <li class="slide">
                                <a href="{{ route('databae_raw.upload') }}" class="side-menu__item">Import Data</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('databae_raw.list') }}" class="side-menu__item">Data Raw</a>
                            </li>
                        </ul>
                    </li>
                @endif


                @if (Auth::user()->role == 'admin')
                    <li class="slide__category">
                        <span class="category-name">Pengaturan</span>
                    </li>
                    <li class="slide">
                        <a href="/users" class="side-menu__item">
                            <i class="side-menu__icon ri-group-3-line"></i>
                            <span class="side-menu__label">Management User</span>
                        </a>
                    </li>
                @endif

            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
    </div>
</aside>
