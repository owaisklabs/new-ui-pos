@extends('ui.layouts.simple.master')

@section('title', 'Default')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
    <style>
        body{
            background:#111827;
            font-family: 'Segoe UI', sans-serif;
            overflow-x:hidden;
        }

        .dashboard-wrapper{
            padding:20px;
        }

        .header-card{
            background:#0f172a;
            border-radius:16px;
            padding:20px;
            margin-bottom:20px;
            border:1px solid rgba(255,255,255,.08);
        }

        .brand-box{
            display:flex;
            align-items:center;
            gap:20px;
        }

        .brand-icon{
            width:90px;
            height:90px;
            background:#22c55e;
            border-radius:20px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-size:40px;
        }

        .brand-title{
            color:#fff;
            font-size:32px;
            font-weight:700;
        }

        .brand-subtitle{
            color:#cbd5e1;
            font-size:15px;
        }

        .top-menu{
            display:grid;
            grid-template-columns: repeat(5,1fr);
            gap:15px;
        }

        .menu-card{
            height:120px;
            border-radius:16px;
            color:#fff;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            text-decoration:none;
            transition:.3s;
            font-weight:600;
            box-shadow:0 10px 20px rgba(0,0,0,.2);
        }

        .menu-card:hover{
            transform:translateY(-5px);
            color:#fff;
        }

        .menu-card i{
            font-size:32px;
            margin-bottom:12px;
        }

        .section-title{
            color:#fff;
            margin:25px 0 15px;
            font-size:20px;
            font-weight:600;
        }

        .main-grid{
            display:grid;
            grid-template-columns: repeat(4,1fr);
            gap:18px;
        }

        .action-card{
            min-height:140px;
            border-radius:18px;
            padding:20px;
            position:relative;
            overflow:hidden;
            color:#fff;
            text-decoration:none;
            transition:.3s;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
            box-shadow:0 10px 25px rgba(0,0,0,.3);
        }

        .action-card:hover{
            transform:scale(1.03);
            color:#fff;
        }

        .action-card i{
            font-size:42px;
            margin-bottom:15px;
        }

        .action-title{
            font-size:20px;
            font-weight:700;
        }

        .green{ background:#22c55e; }
        .blue{ background:#06b6d4; }
        .red{ background:#ef4444; }
        .orange{ background:#f97316; }
        .purple{ background:#8b5cf6; }
        .teal{ background:#14b8a6; }
        .pink{ background:#ec4899; }

        .footer-bar{
            margin-top:25px;
            background:#0f172a;
            border-radius:14px;
            padding:12px 20px;
            color:#cbd5e1;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        @media(max-width:1200px){
            .main-grid{
                grid-template-columns: repeat(3,1fr);
            }

            .top-menu{
                grid-template-columns: repeat(3,1fr);
            }
        }

        @media(max-width:768px){
            .main-grid{
                grid-template-columns: repeat(2,1fr);
            }

            .top-menu{
                grid-template-columns: repeat(2,1fr);
            }

            .brand-box{
                flex-direction:column;
                text-align:center;
            }
        }

        @media(max-width:576px){
            .main-grid,
            .top-menu{
                grid-template-columns:1fr;
            }
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">index</li>
@endsection

@section('content')
    <div class="container-fluid dashboard-wrapper">

        <!-- Header -->
        <div class="header-card">

            <div class="row align-items-center">

                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="brand-box">

                        <div class="brand-icon">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>

                        <div>
                            <div class="brand-title">POS System</div>
                            <div class="brand-subtitle">
                                Smart Business Management Dashboard
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="top-menu">

                        <a href="{{route('book.index')}}" class="menu-card green">
                            <i class="fa-solid fa-box"></i>
                            Items
                        </a>

                        <a href="#" class="menu-card blue">
                            <i class="fa-solid fa-users"></i>
                            Accounts
                        </a>

                        <a href="#" class="menu-card orange">
                            <i class="fa-solid fa-wallet"></i>
                            Cashbox
                        </a>

                        <a href="#" class="menu-card purple">
                            <i class="fa-solid fa-file-invoice"></i>
                            Invoices
                        </a>

                        <a href="#" class="menu-card teal">
                            <i data-feather="shopping-cart"></i>
                            Reports
                        </a>

                    </div>
                </div>

            </div>

        </div>

        <!-- Main Actions -->
        <div class="section-title">
            Quick Actions
        </div>

        <div class="main-grid">

            <a href="{{route('sales.index')}}" class="action-card green">
                <i data-feather="shopping-bag"></i>
                <div class="action-title">Sale</div>
            </a>

            <a href="{{route('purchase.index')}}" class="action-card red">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Purchase</div>
            </a>

            <a href="{{route('supplier.index')}}" class="action-card orange">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Supplier</div>
            </a>

            <a href="{{ route('inventory.index') }}" class="action-card teal">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Inventory</div>
            </a>

            <a href="/sales-return" class="action-card blue">
                <i data-feather="corner-up-left"></i>
                <div class="action-title">Sale Return</div>
            </a>

{{--            <a href="/quotation" class="action-card purple">--}}
{{--                <i data-feather="shopping-cart"></i>--}}
{{--                <div class="action-title">Quotation</div>--}}
{{--            </a>--}}

            <a href="/purchase-return" class="action-card red">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Purchase Return</div>
            </a>

            <a href="/receipt" class="action-card green">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Receipt</div>
            </a>

{{--            <a href="/transfer" class="action-card teal">--}}
{{--                <i data-feather="shopping-cart"></i>--}}
{{--                <div class="action-title">Items Transfer</div>--}}
{{--            </a>--}}

            <a href="/adjustment" class="action-card pink">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Stock Adjustment</div>
            </a>

            <a href="/daily-report" class="action-card blue">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Daily Report</div>
            </a>

            <a href="{{route('sales.index')}}" class="action-card purple">
                <i data-feather="shopping-cart"></i>
                <div class="action-title">Sales Analysis</div>
            </a>

        </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
