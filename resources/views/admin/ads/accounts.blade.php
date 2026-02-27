@extends('layouts.admin')

@section('styles')
    <style>
        .ads-analytics {
            --bg: #ffffff;
            --panel: #ffffff;
            --panel-2: #f8f9fa;
            --stroke: rgba(0,0,0,0.08);
            --text: rgba(0,0,0,0.85);
            --muted: rgba(0,0,0,0.55);
            --muted2: rgba(0,0,0,0.40);
            --blue: #3b82f6;
            --cyan: #22c55e;
            --pink: #a855f7;
            --yellow: #f59e0b;
            color: var(--text);
        }

        .ads-analytics .crumbs {
            color: var(--muted2);
            font-size: 12px;
            margin-bottom: 14px;
        }

        .ads-analytics .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .ads-analytics .title {
            display: flex;
            align-items: baseline;
            gap: 10px;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .ads-analytics .title small {
            font-weight: 600;
            font-size: 12px;
            color: var(--muted);
            border: 1px solid var(--stroke);
            padding: 2px 8px;
            border-radius: 999px;
        }

        .ads-analytics .controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ads-analytics .segmented {
            display: inline-flex;
            background: #f0f0f0;
            border: 1px solid var(--stroke);
            border-radius: 10px;
            padding: 3px;
            gap: 3px;
        }

        .ads-analytics .segmented .seg {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .ads-analytics .segmented .seg.is-active {
            color: #fff;
            background: #3b82f6;
            border: 1px solid #3b82f6;
        }

        .ads-analytics .date-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border: 1px solid var(--stroke);
            border-radius: 10px;
            color: var(--muted);
            background: #ffffff;
            font-size: 12px;
            white-space: nowrap;
        }

        .ads-analytics .grid {
            display: grid;
            gap: 16px;
        }

        .ads-analytics .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        @media (max-width: 768px) {
            .ads-analytics .metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        .ads-analytics .cardx {
            background: var(--panel);
            border: 1px solid var(--stroke);
            border-radius: 14px;
            padding: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .ads-analytics .metric {
            display: grid;
            gap: 10px;
        }

        .ads-analytics .metric .label {
            font-size: 11px;
            color: var(--muted2);
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .ads-analytics .metric .value {
            font-size: 26px;
            font-weight: 800;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .ads-analytics .metric .delta {
            font-size: 12px;
            font-weight: 700;
            color: #22c55e;
        }

        .ads-analytics .metric .bar {
            height: 6px;
            border-radius: 999px;
            background: rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .ads-analytics .metric .bar > span {
            display: block;
            height: 100%;
            width: 70%;
            background: linear-gradient(90deg, rgba(59,130,246,0.9), rgba(59,130,246,0.2));
        }

        .ads-analytics .chart-head {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .ads-analytics .chart-head .h {
            font-size: 18px;
            font-weight: 800;
        }

        .ads-analytics .chart-head .sub {
            color: var(--muted);
            font-size: 12px;
            margin-top: 2px;
        }

        .ads-analytics .toggle {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            color: var(--muted);
            font-size: 12px;
        }

        .ads-analytics .toggle .pill {
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid var(--stroke);
            background: #f0f0f0;
            color: var(--muted);
        }

        .ads-analytics .toggle .pill.is-active {
            color: #fff;
            border-color: #3b82f6;
            background: #3b82f6;
        }

        .ads-analytics .chart {
            height: 270px;
            border-radius: 14px;
            background: radial-gradient(1200px 500px at 70% 30%, rgba(59,130,246,0.08), transparent 60%),
                        radial-gradient(900px 400px at 20% 80%, rgba(168,85,247,0.06), transparent 60%),
                        #ffffff;
            border: 1px solid var(--stroke);
            position: relative;
            overflow: hidden;
        }

        .ads-analytics .chart svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }
        .ads-analytics .chart-js-wrapper {
            position: relative;
            padding: 10px 16px 36px 48px;
            min-height: 270px;
        }
        .ads-analytics .chart-js-wrapper canvas {
            width: 100% !important;
            height: 224px !important;
            max-height: 224px;
        }

        .ads-analytics .chart .xlabels {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 10px;
            display: flex;
            justify-content: space-between;
            padding: 0 16px;
            color: var(--muted2);
            font-size: 11px;
            letter-spacing: .12em;
        }

        .ads-analytics .chart .ylabels {
            position: absolute;
            left: 8px;
            top: 30px;
            bottom: 36px;
            width: 36px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--muted2);
            font-size: 11px;
            font-weight: 600;
            pointer-events: none;
            z-index: 1;
        }

        .ads-analytics .chart .chart-point-dot {
            cursor: pointer;
            transition: fill 0.15s ease;
        }
        .ads-analytics .chart .chart-point-dot:hover {
            fill: rgba(59, 130, 246, 0.5);
        }
        .ads-analytics .chart .roas-chart-tooltip {
            position: fixed;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .ads-analytics .section-title {
            margin-top: 18px;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ads-analytics .chip {
            font-size: 12px;
            color: var(--muted);
            border: 1px solid var(--stroke);
            padding: 4px 10px;
            border-radius: 999px;
            background: #f0f0f0;
        }

        .ads-analytics .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin: 10px 0 12px;
        }

        .ads-analytics .search {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--stroke);
            background: #ffffff;
            padding: 8px 10px;
            border-radius: 12px;
            min-width: 260px;
            color: var(--muted);
        }

        .ads-analytics .search input {
            border: 0;
            outline: none;
            background: transparent;
            color: var(--text);
            width: 100%;
        }
        
        .ads-analytics .search input::placeholder {
            color: var(--muted);
        }

        .ads-analytics .btnx {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 12px;
            border: 1px solid var(--stroke);
            background: #ffffff;
            color: var(--text);
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        
        .ads-analytics .btnx:hover {
            background: #f0f0f0;
        }

        .ads-analytics .btnx.primary {
            border-color: #3b82f6;
            background: #3b82f6;
            color: #fff;
        }
        
        .ads-analytics .btnx.primary:hover {
            background: #2563eb;
            border-color: #2563eb;
        }

        .ads-analytics table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid var(--stroke);
            background: #ffffff;
        }

        .ads-analytics thead th {
            text-align: left;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted2);
            padding: 12px 14px;
            border-bottom: 1px solid var(--stroke);
            background: #f8f9fa;
        }

        .ads-analytics tbody td {
            padding: 14px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
            color: var(--text);
        }
        
        .ads-analytics tbody tr:hover {
            background: #f8f9fa;
        }

        /* Branch/tree: clickable campaign row to expand/collapse children */
        .ads-analytics .branch-expandable-cell {
            cursor: pointer;
        }
        .ads-analytics .branch-expandable-cell:hover {
            background: rgba(59, 130, 246, 0.06);
        }

        .ads-analytics tbody tr:last-child td { border-bottom: 0; }

        .ads-analytics .acct {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .ads-analytics .logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.06);
            display: grid;
            place-items: center;
            font-weight: 800;
            color: #fff;
        }

        .ads-analytics .acct .meta {
            display: grid;
            gap: 2px;
        }

        .ads-analytics .acct .meta .name { font-weight: 800; color: var(--text); }
        .ads-analytics .acct .meta .id { color: var(--muted2); font-size: 12px; }

        .ads-analytics .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            color: var(--text);
        }

        .ads-analytics .badge .up { color: #22c55e; font-weight: 900; }
        .ads-analytics .badge .down { color: #ef4444; font-weight: 900; }

        .ads-analytics .actions {
            display: inline-flex;
            gap: 8px;
            color: var(--muted);
        }

        .ads-analytics .actions span {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            border: 1px solid var(--stroke);
            display: grid;
            place-items: center;
            background: #f0f0f0;
            transition: all 0.2s ease;
        }
        
        .ads-analytics .actions span:hover {
            background: #e0e0e0;
        }

        .ads-analytics .actions a {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            border: 1px solid var(--stroke);
            display: grid;
            place-items: center;
            background: #f0f0f0;
            color: var(--text);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .ads-analytics .actions a:hover {
            background: #e0e0e0;
        }

        .ads-analytics .toggle-detail {
            cursor: pointer;
            user-select: none;
        }

        /* ROAS Breakdown Modal Styles */
        .roas-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .roas-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
        }

        .roas-modal-content {
            position: relative;
            width: 480px;
            max-width: 90vw;
            height: 100vh;
            background: #ffffff;
            border-left: 1px solid var(--stroke);
            box-shadow: -20px 0 60px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: transX(100%);
            }
            to {
                transform: transX(0);
            }
        }

        .roas-modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--stroke);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .roas-modal-header h2 {
            font-size: 24px;
            font-weight: 900;
            color: var(--text);
            margin: 0 0 4px 0;
        }

        .roas-modal-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
        }

        .roas-modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--stroke);
            background: #f0f0f0;
            color: var(--text);
            font-size: 20px;
            font-weight: 300;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .roas-modal-close:hover {
            background: #e0e0e0;
            color: var(--text);
        }

        .roas-modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .roas-metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        @media (max-width: 600px) {
            .roas-metrics-grid {
                grid-template-columns: 1fr;
            }
        }

        .roas-metric-card {
            background: #f8f9fa;
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 16px;
        }

        .roas-metric-label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }

        .roas-metric-value {
            font-size: 24px;
            font-weight: 900;
            color: var(--text);
        }

        .roas-metric-green {
            color: #22c55e;
        }

        .roas-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .roas-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .roas-section-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text);
            margin: 0;
        }

        .roas-view-all {
            font-size: 12px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .roas-view-all:hover {
            text-decoration: underline;
        }

        .roas-products-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .roas-products-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }

        .roas-products-modal-content {
            position: relative;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            background: #ffffff;
            border: 1px solid var(--stroke);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .roas-products-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--stroke);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .roas-products-modal-header h3 {
            font-size: 20px;
            font-weight: 900;
            color: var(--text);
            margin: 0;
        }

        .roas-products-modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--stroke);
            background: #f0f0f0;
            color: var(--text);
            font-size: 20px;
            font-weight: 300;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .roas-products-modal-close:hover {
            background: #e0e0e0;
            color: var(--text);
        }

        .roas-products-modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }

        .roas-products-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .roas-products-table thead th {
            text-align: left;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--muted2);
            padding: 12px;
            border-bottom: 1px solid var(--stroke);
            font-weight: 700;
            background: #f8f9fa;
        }

        .roas-products-table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            color: var(--text);
            font-size: 13px;
        }

        .roas-products-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .roas-products-table tbody tr:hover {
            background: #f8f9fa;
        }

        .roas-status-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .roas-status-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border: 1px solid var(--stroke);
            border-radius: 10px;
        }

        .roas-status-item-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .roas-status-item-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 4px;
            padding-top: 8px;
            border-top: 1px solid rgba(0,0,0,0.08);
        }

        @media (max-width: 600px) {
            .roas-status-item-details {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }

        .roas-status-detail {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .roas-status-detail-label {
            font-size: 10px;
            color: var(--muted2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .roas-status-detail-value {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        .roas-status-bar {
            flex: 1;
            height: 24px;
            border-radius: 999px;
            background: rgba(0,0,0,0.08);
            overflow: visible;
            position: relative;
            display: flex;
            align-items: center;
        }

        .roas-status-bar-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 8px;
            min-width: 0;
        }

        .roas-status-percentage {
            font-size: 11px;
            font-weight: 800;
            color: rgba(255,255,255,0.95);
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            white-space: nowrap;
            line-height: 1;
        }

        .roas-status-percentage-outside {
            font-size: 11px;
            font-weight: 800;
            color: var(--muted);
            white-space: nowrap;
            margin-left: 8px;
        }

        .roas-status-label {
            min-width: 100px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .roas-status-value {
            min-width: 70px;
            text-align: right;
            font-size: 13px;
            font-weight: 800;
            color: var(--text);
        }

        .roas-products-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .roas-product-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border: 1px solid var(--stroke);
            border-radius: 10px;
        }

        .roas-product-thumb {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f0f0f0;
            border: 1px solid var(--stroke);
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .roas-product-thumb .roas-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .roas-product-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f0f0f0;
            border: 1px solid var(--stroke);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 18px;
        }

        .roas-product-name {
            flex: 1;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .roas-product-roas {
            font-size: 13px;
            font-weight: 800;
        }

        .roas-product-roas.high {
            color: #22c55e;
        }

        .roas-product-roas.medium {
            color: #f59e0b;
        }

        .roas-product-roas.low {
            color: #ef4444;
        }

        .roas-download-btn {
            width: 100%;
            padding: 14px;
            background: #3b82f6;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .roas-download-btn:hover {
            background: #2563eb;
            transform: transY(-1px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }

        .roas-loading {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }
    </style>
@endsection

@section('content')
    <div class="ads-analytics">
        <div class="crumbs">
            {{ trans('Dashboard') }} <span style="opacity:.35;">›</span> {{ trans('Ad Accounts Overview') }}
        </div>

        <div class="topbar">
            <div class="title">
                <span>{{ trans('Dashboard Analytics') }}</span>
                </div>
            <div class="controls">
                {{-- <div class="segmented">
                    <a class="seg is-active" href="javascript:void(0)">{{ trans('All Platforms') }}</a>
                    <a class="seg" href="javascript:void(0)">{{ trans('Meta') }}</a>
                    <a class="seg" href="javascript:void(0)">{{ trans('Google') }}</a>
                    <a class="seg" href="javascript:void(0)">{{ trans('TikTok') }}</a>
                </div> --}}
                <form method="GET" action="{{ route('admin.ads.accounts.index') }}" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    @if(request('account_id'))
                        <input type="hidden" name="account_id" value="{{ request('account_id') }}">
                    @endif
                    <div class="date-pill" style="display:flex; align-items:center; gap:8px; padding:8px 12px;">
                        <span style="opacity:.7;">📅</span>
                        <input type="text" 
                               class="aiz-date-range" 
                               name="date_range" 
                               value="{{ request('date_range') }}" 
                               placeholder="{{ trans('Select date range') }}" 
                               data-format="DD-MM-Y" 
                               data-separator=" to " 
                               data-advanced-range="true" 
                               autocomplete="off"
                               style="border:none; background:transparent; color:var(--text); font-size:12px; width:180px; outline:none;">
                    </div>
                    @if(request('date_range'))
                        <a href="{{ route('admin.ads.accounts.index', request()->except(['date_range', 'page', 'page_details'])) }}" 
                           class="btnx" 
                           style="padding:6px 10px; font-size:11px;">
                            {{ trans('Clear') }}
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @php
            $maxValue = max($totalSpent ?? 0, $totalRevenue ?? 0, 1);
            $spentPercent = $maxValue > 0 ? min(100, (($totalSpent ?? 0) / $maxValue) * 100) : 0;
            $revenuePercent = $maxValue > 0 ? min(100, (($totalRevenue ?? 0) / $maxValue) * 100) : 0;
            $roasPercent = ($overallRoas ?? 0) > 0 ? min(100, (($overallRoas ?? 0) / max($overallRoas ?? 1, 1)) * 100) : 0;
            $roasChartDataJson = json_encode($chartData ?? []);
        @endphp

        <div class="grid">
            <div class="metrics-grid">
                <div class="cardx metric">
                    <div class="label">{{ trans('Total Spent') }}</div>
                    <div class="value">{{ format_price($totalSpent ?? 0) }}</div>
                    <div class="bar">
                        <span style="width: {{ $spentPercent }}%; background: linear-gradient(90deg, rgba(245,158,11,0.9), rgba(245,158,11,0.2));"></span>
                    </div>
                </div>

                <div class="cardx metric">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div class="label" style="margin: 0;">{{ trans('Total Revenue') }} ({{ trans('All') }})</div>
                        <div style="font-size: 16px; font-weight: 600; color: var(--text);">{{ format_price($totalRevenue ?? 0) }}</div>
                    </div>
                    @php
                        $breakdown = $revenueBreakdown ?? [
                            'pending' => 0,
                            'confirmed' => 0,
                            'delivered' => 0,
                            'returned' => 0,
                        ];
                        $totalBreakdown = ($breakdown['delivered'] ?? 0) + ($breakdown['pending'] ?? 0) + ($breakdown['confirmed'] ?? 0) + ($breakdown['returned'] ?? 0);
                    @endphp
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(34,197,94,0.06); border-radius: 12px; border: 1px solid rgba(34,197,94,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(34,197,94,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(34,197,94,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Delivered') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(34,197,94,0.95);">{{ format_price($breakdown['delivered'] ?? 0) }}</div>
                            @if($totalBreakdown > 0)
                                <div style="font-size: 11px; color: var(--muted); font-weight: 400;">{{ number_format((($breakdown['delivered'] ?? 0) / $totalBreakdown) * 100, 1) }}% {{ trans('of total') }}</div>
                            @endif
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(245,158,11,0.06); border-radius: 12px; border: 1px solid rgba(245,158,11,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(245,158,11,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(245,158,11,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Pending') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(245,158,11,0.95);">{{ format_price($breakdown['pending'] ?? 0) }}</div>
                            @if($totalBreakdown > 0)
                                <div style="font-size: 11px; color: var(--muted); font-weight: 400;">{{ number_format((($breakdown['pending'] ?? 0) / $totalBreakdown) * 100, 1) }}% {{ trans('of total') }}</div>
                            @endif
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(59,130,246,0.06); border-radius: 12px; border: 1px solid rgba(59,130,246,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(59,130,246,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(59,130,246,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Confirmed') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(59,130,246,0.95);">{{ format_price($breakdown['confirmed'] ?? 0) }}</div>
                            @if($totalBreakdown > 0)
                                <div style="font-size: 11px; color: var(--muted); font-weight: 400;">{{ number_format((($breakdown['confirmed'] ?? 0) / $totalBreakdown) * 100, 1) }}% {{ trans('of total') }}</div>
                            @endif
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(239,68,68,0.06); border-radius: 12px; border: 1px solid rgba(239,68,68,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(239,68,68,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(239,68,68,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Returned') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(239,68,68,0.95);">{{ format_price($breakdown['returned'] ?? 0) }}</div>
                            @if($totalBreakdown > 0)
                                <div style="font-size: 11px; color: var(--muted); font-weight: 400;">{{ number_format((($breakdown['returned'] ?? 0) / $totalBreakdown) * 100, 1) }}% {{ trans('of total') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="cardx metric">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <div class="label" style="margin: 0;">{{ trans('Overall ROAS') }} ({{ trans('All') }})</div>
                        <div style="font-size: 16px; font-weight: 600; color: var(--text);">{{ number_format($overallRoas ?? 0, 2) }}</div>
                    </div>
                    @php
                        $roasBreakdownData = $roasBreakdown ?? [
                            'pending' => 0,
                            'confirmed' => 0,
                            'delivered' => 0,
                            'returned' => 0,
                        ];
                    @endphp
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(34,197,94,0.06); border-radius: 12px; border: 1px solid rgba(34,197,94,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(34,197,94,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(34,197,94,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Delivered') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(34,197,94,0.95);">{{ number_format($roasBreakdownData['delivered'] ?? 0, 2) }}</div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(245,158,11,0.06); border-radius: 12px; border: 1px solid rgba(245,158,11,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(245,158,11,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(245,158,11,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Pending') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(245,158,11,0.95);">{{ number_format($roasBreakdownData['pending'] ?? 0, 2) }}</div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(59,130,246,0.06); border-radius: 12px; border: 1px solid rgba(59,130,246,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(59,130,246,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(59,130,246,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Confirmed') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(59,130,246,0.95);">{{ number_format($roasBreakdownData['confirmed'] ?? 0, 2) }}</div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px; padding: 14px; background: rgba(239,68,68,0.06); border-radius: 12px; border: 1px solid rgba(239,68,68,0.15);">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: rgba(239,68,68,0.4);"></div>
                                <span style="font-size: 11px; font-weight: 500; color: rgba(239,68,68,0.8); text-transform: uppercase; letter-spacing: 0.5px;">{{ trans('Returned') }}</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: rgba(239,68,68,0.95);">{{ number_format($roasBreakdownData['returned'] ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cardx" style="grid-column: 1 / -1;">
                <div class="chart-head">
                    <div>
                        <div class="h">{{ trans('ROAS Trends') }}</div>
                        <div class="sub">{{ trans('Efficiency across all accounts') }}</div>
                    </div>
                </div>

                <div class="chart chart-js-wrapper">
                    <canvas id="roasTrendChart" aria-label="{{ trans('ROAS Trends') }}" role="img"></canvas>
                    <script>window.roasChartData = {!! $roasChartDataJson !!};</script>
                </div>
            </div>
        </div>

        @php
            $isDetailsView = isset($selectedAccount) && $selectedAccount;
        @endphp

        <div class="section-title">
            <div style="display:flex; gap:10px; align-items:center;">
                @if($isDetailsView)
                    <span>{{ trans('Account Details') }}</span>
                    <span class="chip">{{ $selectedAccount->name }}</span>
                @else
                    <span>{{ trans('Ad Accounts') }}</span>
                    <span class="chip">{{ isset($accounts) ? $accounts->count() : 0 }} {{ trans('Active') }}</span>
                @endif
            </div>
        </div>

        <div class="toolbar">
            <form method="GET" action="{{ route('admin.ads.accounts.index') }}" style="display:flex; align-items:center; gap:10px; flex:1;">
                @if(request('account_id'))
                    <input type="hidden" name="account_id" value="{{ request('account_id') }}">
                @endif
                @if(request('date_range'))
                    <input type="hidden" name="date_range" value="{{ request('date_range') }}">
                @endif
                <div class="search" style="flex:1; max-width:400px;">
                    <span style="opacity:.7;">🔎</span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="{{ $isDetailsView ? trans('Search details...') : trans('Search accounts...') }}"
                           style="border:none; background:transparent; color:var(--text); font-size:12px; width:100%; outline:none;">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.ads.accounts.index', request()->except(['search', 'page', 'page_details'])) }}" 
                       class="btnx" 
                       style="padding:6px 10px; font-size:11px;">
                        {{ trans('Clear') }}
                    </a>
                @endif
            </form>
            <div style="display:flex; gap:10px; align-items:center;">
                @if($isDetailsView)
                    <a class="btnx" href="{{ route('admin.ads.accounts.index') }}">← {{ trans('Back to Accounts') }}</a>
                    <a class="btnx primary" href="{{ route('admin.ads.accounts.details.create', $selectedAccount->id) }}">+ {{ trans('New Detail') }}</a>
                @else
                    {{-- <a class="btnx" href="javascript:void(0)">{{ trans('Filters') }}</a> --}}
                    <a class="btnx primary" href="{{ route('admin.ads.accounts.create') }}">+ {{ trans('New') }}</a>
                    <a class="btnx" href="{{ route('admin.ads.accounts.assign-campaign') }}">{{ trans('Assign Campaign') }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    @if($isDetailsView)
                        <th style="width:18%;">{{ trans('Detail Name') }}</th>
                        <th style="width:10%;">{{ trans('UTM Key') }}</th>
                        <th style="width:8%;">{{ trans('Type') }}</th>
                        <th style="width:8%;">{{ trans('Orders') }}</th>
                        <th style="width:10%;">{{ trans('Spend') }}</th>
                        <th style="width:10%;">{{ trans('Revenue') }}</th>
                        <th style="width:8%;">{{ trans('ROAS') }}</th>
                        <th style="width:8%;">{{ trans('AOV') }}</th>
                        <th style="width:8%;">{{ trans('CPO') }}</th>
                        <th style="width:12%;">{{ trans('Actions') }}</th>
                    @else
                        <th style="width:24%;">{{ trans('Account Details') }}</th>
                        <th>{{ trans('Balance') }}</th>
                        <th>{{ trans('Orders') }}</th>
                        <th>{{ trans('Status Breakdown') }}</th>
                        <th>{{ trans('ROAS') }}</th>
                        <th>{{ trans('Spend') }}</th>
                        <th>{{ trans('Revenue') }}</th>
                        <th>{{ trans('AOV') }}</th>
                        <th>{{ trans('CPO') }}</th>
                        <th style="width:12%;">{{ trans('Actions') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($isDetailsView)
                    @forelse(($flatAccountDetails ?? collect()) as $row)
                        @php
                            /** @var \App\Models\AdsAccountDetail $detail */
                            $detail = $row['detail'];
                            $level = (int) ($row['level'] ?? 0);
                            $hasChildren = (bool) ($row['hasChildren'] ?? false);
                            $indent = $level * 22;
                            $isHidden = $level > 0;
                        @endphp

                        @php
                            $isAdType = strtolower($detail->type ?? '') === 'ad';
                            $historyUrl = $isAdType ? route('admin.ads.accounts.details.history', [$selectedAccount->id, $detail->id]) : null;
                        @endphp

                        <tr data-detail-id="{{ $detail->id }}"
                            data-parent-id="{{ $detail->parent_id ?? '' }}"
                            data-level="{{ $level }}"
                            data-has-children="{{ $hasChildren ? '1' : '0' }}"
                            class="{{ $isHidden ? 'branch-child-row' : '' }}"
                            style="{{ $isHidden ? 'display:none;' : '' }}">
                            <td class="{{ $hasChildren ? 'branch-expandable-cell' : '' }}" @if($hasChildren) title="{{ trans('Click to expand/collapse children') }}" @endif>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="logo" style="width:34px; height:34px; border-radius: 10px; background: #f0f0f0; border-color: var(--stroke);">
                                        {{ strtoupper(mb_substr((string) ($detail->name ?? 'D'), 0, 1)) }}
                                    </div>
                                    <div style="padding-left: {{ $indent }}px;">
                                        <div style="font-weight:800; color: var(--text);">{{ $detail->name }}</div>
                                        <div class="id" style="color: var(--muted2); font-size: 12px;">ID: {{ $detail->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--muted);">{{ $detail->utm_key }}</td>
                            <td>
                                @if($detail->type)
                                    <span class="chip" style="background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.3); color: #3b82f6;">
                                        {{ ucfirst(str_replace('_', ' ', $detail->type)) }}
                                    </span>
                                @else
                                    <span style="color: var(--muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ (int) ($detail->orders_count ?? 0) }}</span>
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ format_price($detail->total_spent ?? 0) }}</span>
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ format_price($detail->revenue ?? 0) }}</span>
                            </td>
                            <td>
                                <span class="chip" style="background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.3); color: #3b82f6;">
                                    {{ number_format($detail->roas ?? 0, 2) }}
                                </span>
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ format_price($detail->aov ?? 0) }}</span>
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ format_price($detail->cpo ?? 0) }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    @if($hasChildren)
                                        <a class="toggle-detail" data-toggle-detail="{{ $detail->id }}" data-expanded="0" href="javascript:void(0)">→</a>
                                    @else
                                        <span style="opacity:.25;">—</span>
                                    @endif
                                    <span onclick="showRoasBreakdown({{ $selectedAccount->id }}, '{{ addslashes(e($detail->name ?? '')) }}', {{ $detail->id }})" style="cursor: pointer;" title="{{ trans('ROAS Breakdown') }}{{ $hasChildren ? ' (' . trans('this and children') . ')' : '' }}">📊</span>
                                    @if($isAdType && $historyUrl)
                                        <a href="{{ $historyUrl }}" title="{{ trans('History') }}" style="text-decoration:none; color:inherit; cursor:pointer;">👁</a>
                                    @endif
                                    <a href="{{ route('admin.ads.accounts.details.edit', [$selectedAccount->id, $detail->id]) }}" title="{{ trans('Edit') }}">✎</a>
                                    @can('delete_ads_accounts')
                                    <a href="javascript:void(0)" title="{{ trans('Delete') }}" onclick="adsConfirmDelete('{{ route('admin.ads.accounts.details.destroy', [$selectedAccount->id, $detail->id]) }}'); return false;" style="text-decoration:none; color:inherit; cursor:pointer;">🗑️</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; color: var(--muted); padding: 24px;">
                                {{ trans('No account details found') }}
                            </td>
                        </tr>
                    @endforelse
                @else
                    @forelse(($accounts ?? collect()) as $account)
                        @php
                            $palettes = [
                                ['bg' => 'rgba(59,130,246,0.10)', 'border' => 'rgba(59,130,246,0.25)', 'text' => '#3b82f6'],
                                ['bg' => 'rgba(236,72,153,0.10)', 'border' => 'rgba(236,72,153,0.25)', 'text' => '#ec4899'],
                                ['bg' => 'rgba(34,197,94,0.10)', 'border' => 'rgba(34,197,94,0.25)', 'text' => '#22c55e'],
                            ];
                            $palette = $palettes[($account->id ?? 0) % count($palettes)];
                            $logoLetter = strtoupper(mb_substr((string) ($account->name ?? 'A'), 0, 1));

                            $spent = (float) ($account->total_spent_sum ?? 0);
                            $revenue = (float) ($account->revenue_sum ?? 0);
                            $ordersCount = (int) ($account->orders_count ?? 0);
                            $roas = $spent > 0 ? ($revenue / $spent) : 0;
                            $aov = $ordersCount > 0 ? ($revenue / $ordersCount) : 0;
                            $cpo = $ordersCount > 0 ? ($spent / $ordersCount) : 0;
                            $balance = (float) ($account->balance ?? 0);

                            $p = (int) ($account->status_pending ?? 0);
                            $conf = (int) ($account->status_confirmed ?? 0);
                            $d = (int) ($account->status_delivered ?? 0);
                            $r = (int) ($account->status_returned ?? 0);
                            $statusTotal = $p + $conf + $d + $r;
                            $statusSegments = [];
                            if ($statusTotal > 0) {
                                $statusSegments = [
                                    ['pct' => round(100 * $p / $statusTotal, 1), 'color' => '#f59e0b', 'label' => trans('Pending')],
                                    ['pct' => round(100 * $conf / $statusTotal, 1), 'color' => '#3b82f6', 'label' => trans('Confirmed')],
                                    ['pct' => round(100 * $d / $statusTotal, 1), 'color' => '#22c55e', 'label' => trans('Delivered')],
                                    ['pct' => round(100 * $r / $statusTotal, 1), 'color' => '#ef4444', 'label' => trans('Returned')],
                                ];
                                $statusSegments = array_filter($statusSegments, fn($seg) => $seg['pct'] > 0);
                            }

                            $detailsUrl = route('admin.ads.accounts.index', ['account_id' => $account->id]);
                        @endphp

                        <tr>
                            <td>
                                <div class="acct">
                                    <div class="logo" style="background: {{ $palette['bg'] }}; border-color: {{ $palette['border'] }}; color: {{ $palette['text'] ?? 'var(--text)' }};">
                                        {{ $logoLetter }}
                                    </div>
                                    <div class="meta">
                                        <div class="name">
                                            <a href="{{ $detailsUrl }}" style="color: var(--text); text-decoration:none;">
                                                {{ $account->name }}
                                            </a>
                                        </div>
                                        <div class="id">ID: {{ $account->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 800; font-size: 14px; color: #22c55e;">{{ format_price($balance) }}</span>
                            </td>
                            <td>
                                <span style="color: var(--text); font-weight: 600;">{{ $ordersCount > 0 ? $ordersCount : '—' }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <div style="flex:1; height:8px; border-radius:999px; background:rgba(0,0,0,0.08); overflow:hidden; display:flex;">
                                        @if(!empty($statusSegments))
                                            @foreach($statusSegments as $seg)
                                                <span style="display:block; width:{{ $seg['pct'] }}%; height:100%; background:{{ $seg['color'] }};" title="{{ $seg['label'] }}: {{ $seg['pct'] }}%"></span>
                                            @endforeach
                                        @else
                                            <span style="display:block; width:100%; height:100%; background:rgba(0,0,0,0.12);"></span>
                                        @endif
                                    </div>
                                    @if(!empty($statusSegments))
                                        <span style="color:var(--muted2); font-size:11px; white-space:nowrap;">{{ $p }} / {{ $conf }} / {{ $d }} / {{ $r }}</span>
                                    @else
                                        <span style="color:var(--muted2); font-size:12px;">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="badge">{{ number_format($roas, 2) }}</td>
                            <td>{{ format_price($spent) }}</td>
                            <td>{{ format_price($revenue) }}</td>
                            <td>{{ format_price($aov) }}</td>
                            <td>{{ format_price($cpo) }}</td>
                            <td>
                                <div class="actions">
                                    <span onclick="showRoasBreakdown({{ $account->id }}, '{{ addslashes(e($account->name ?? '')) }}')" style="cursor: pointer;" title="{{ trans('ROAS Breakdown') }}">📊</span>
                                    {{-- <span>🗂️</span> --}}
                                    <a href="{{ $detailsUrl }}" style="color: var(--muted); text-decoration:none;">
                                        →
                                    </a>
                                    @can('delete_ads_accounts')
                                    <a href="javascript:void(0)" title="{{ trans('Delete') }}" onclick="adsConfirmDelete('{{ route('admin.ads.accounts.destroy', $account->id) }}'); return false;" style="text-decoration:none; color:inherit; cursor:pointer;">🗑️</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; color: var(--muted); padding: 24px;">
                                {{ trans('No accounts found') }}
                            </td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
        </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap text-muted small">
            <div>
                @if($isDetailsView)
                    @php
                        $flatDetails = $flatAccountDetails ?? collect();
                        $from = $flatDetails->firstItem() ?? 0;
                        $to = $flatDetails->lastItem() ?? 0;
                        $total = $flatDetails->total() ?? 0;
                    @endphp
                    Showing {{ $from }}-{{ $to }} of {{ $total }} details
                    @if(request('search'))
                        <span style="color:var(--muted2);">({{ trans('filtered') }})</span>
                    @endif
                @else
                    @php
                        $accountsPaginated = $accounts ?? null;
                        $from = $accountsPaginated ? $accountsPaginated->firstItem() : 0;
                        $to = $accountsPaginated ? $accountsPaginated->lastItem() : 0;
                        $total = $accountsPaginated ? $accountsPaginated->total() : 0;
                    @endphp
                    Showing {{ $from }}-{{ $to }} of {{ $total }} accounts
                    @if(request('search'))
                        <span style="color:var(--muted2);">({{ trans('filtered') }})</span>
                    @endif
                @endif
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                @if($isDetailsView)
                    @if($flatAccountDetails && $flatAccountDetails->hasPages())
                        @if($flatAccountDetails->onFirstPage())
                            <span class="chip" style="opacity:0.3; cursor:not-allowed;">‹</span>
                        @else
                            <a href="{{ $flatAccountDetails->appends(request()->except('page_details'))->previousPageUrl() }}" class="chip" style="text-decoration:none; color:inherit;">‹</a>
                        @endif
                        
                        @php
                            $lastPage = $flatAccountDetails->lastPage();
                            $currentPage = $flatAccountDetails->currentPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp
                        
                        @if($startPage > 1)
                            <a href="{{ $flatAccountDetails->appends(request()->except('page_details'))->url(1) }}" class="chip" style="text-decoration:none; color:inherit;">1</a>
                            @if($startPage > 2)
                                <span class="chip" style="opacity:0.5;">...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $flatAccountDetails->currentPage())
                                <span class="chip" style="border-color: #3b82f6; background: #3b82f6; color:#fff;">{{ $page }}</span>
                            @else
                                <a href="{{ $flatAccountDetails->appends(request()->except('page_details'))->url($page) }}" class="chip" style="text-decoration:none; color:inherit;">{{ $page }}</a>
                            @endif
                        @endfor
                        
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="chip" style="opacity:0.5;">...</span>
                            @endif
                            <a href="{{ $flatAccountDetails->appends(request()->except('page_details'))->url($lastPage) }}" class="chip" style="text-decoration:none; color:inherit;">{{ $lastPage }}</a>
                        @endif
                        
                        @if($flatAccountDetails->hasMorePages())
                            <a href="{{ $flatAccountDetails->appends(request()->except('page_details'))->nextPageUrl() }}" class="chip" style="text-decoration:none; color:inherit;">›</a>
                        @else
                            <span class="chip" style="opacity:0.3; cursor:not-allowed;">›</span>
                        @endif
                    @endif
                @else
                    @if($accounts && $accounts->hasPages())
                        @if($accounts->onFirstPage())
                            <span class="chip" style="opacity:0.3; cursor:not-allowed;">‹</span>
                        @else
                            <a href="{{ $accounts->appends(request()->except('page'))->previousPageUrl() }}" class="chip" style="text-decoration:none; color:inherit;">‹</a>
                        @endif
                        
                        @php
                            $lastPage = $accounts->lastPage();
                            $currentPage = $accounts->currentPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp
                        
                        @if($startPage > 1)
                            <a href="{{ $accounts->appends(request()->except('page'))->url(1) }}" class="chip" style="text-decoration:none; color:inherit;">1</a>
                            @if($startPage > 2)
                                <span class="chip" style="opacity:0.5;">...</span>
                            @endif
                        @endif
                        
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $accounts->currentPage())
                                <span class="chip" style="border-color: #3b82f6; background: #3b82f6; color:#fff;">{{ $page }}</span>
                            @else
                                <a href="{{ $accounts->appends(request()->except('page'))->url($page) }}" class="chip" style="text-decoration:none; color:inherit;">{{ $page }}</a>
                            @endif
                        @endfor
                        
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <span class="chip" style="opacity:0.5;">...</span>
                            @endif
                            <a href="{{ $accounts->appends(request()->except('page'))->url($lastPage) }}" class="chip" style="text-decoration:none; color:inherit;">{{ $lastPage }}</a>
                        @endif
                        
                        @if($accounts->hasMorePages())
                            <a href="{{ $accounts->appends(request()->except('page'))->nextPageUrl() }}" class="chip" style="text-decoration:none; color:inherit;">›</a>
                        @else
                            <span class="chip" style="opacity:0.3; cursor:not-allowed;">›</span>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- ROAS Breakdown Side Modal -->
    <div id="roasBreakdownModal" class="roas-modal" style="display: none;">
        <div class="roas-modal-overlay" onclick="closeRoasBreakdown()"></div>
        <div class="roas-modal-content">
            <div class="roas-modal-header">
                <div>
                    <h2 id="roasModalTitle">{{ trans('ROAS Breakdown') }}</h2>
                    <p id="roasModalSubtitle" class="roas-modal-subtitle"></p>
                </div>
                <button class="roas-modal-close" onclick="closeRoasBreakdown()">×</button>
            </div>

            <div class="roas-modal-body">
                <div class="roas-metrics-grid">
                    <div class="roas-metric-card">
                        <div class="roas-metric-label">{{ trans('Total Revenue with shipping') }}</div>
                        <div class="roas-metric-value" id="attributedRevenue">—</div>
                    </div>
                    <div class="roas-metric-card">
                        <div class="roas-metric-label">{{ trans('Total Revenue Without Shipping') }}</div>
                        <div class="roas-metric-value" id="totalRevenueWithoutShipping">—</div>
                    </div>
                    <div class="roas-metric-card">
                        <div class="roas-metric-label">{{ trans('Total Combined Orders') }}</div>
                        <div class="roas-metric-value" id="totalCombinedOrders">—</div>
                    </div>
                    <div class="roas-metric-card">
                        <div class="roas-metric-label">{{ trans('True ROAS') }}</div>
                        <div class="roas-metric-value roas-metric-green" id="trueRoas">—</div>
                    </div>
                </div>

                <div class="roas-section">
                    <h3 class="roas-section-title">{{ trans('ROAS by Order Status') }}</h3>
                    <div id="roasByStatus" class="roas-status-list">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <div class="roas-section">
                    <div class="roas-section-header">
                        <h3 class="roas-section-title">{{ trans('Top Products') }}</h3>
                        <a href="javascript:void(0)" class="roas-view-all">{{ trans('View All') }}</a>
                    </div>
                    <div id="topProducts" class="roas-products-list">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <button class="roas-download-btn" onclick="downloadRoasReport()">
                    {{ trans('Download Full Report') }}
                </button>
            </div>
        </div>
    </div>

    <!-- All Products Modal -->
    <div id="roasProductsModal" class="roas-products-modal">
        <div class="roas-products-modal-overlay" onclick="closeProductsModal()"></div>
        <div class="roas-products-modal-content">
            <div class="roas-products-modal-header">
                <h3 id="roasProductsModalTitle">{{ trans('All Products') }}</h3>
                <button class="roas-products-modal-close" onclick="closeProductsModal()">×</button>
            </div>
            <div class="roas-products-modal-body">
                <table class="roas-products-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>{{ trans('Product') }}</th>
                            <th style="text-align:right;">{{ trans('Revenue') }}</th>
                            <th style="text-align:center;">{{ trans('Quantity') }}</th>
                            <th style="text-align:right;">{{ trans('ROAS') }}</th>
                        </tr>
                    </thead>
                    <tbody id="roasProductsTableBody">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <script>
        // Delete: called from inline onclick (works without jQuery delegation)
        function adsConfirmDelete(url) {
            if (url && confirm('{{ trans("Do you really want to delete?") }}')) {
                window.location.href = url;
            }
        }

        // ROAS Trends chart (Chart.js)
        (function () {
            var data = typeof window.roasChartData !== 'undefined' ? window.roasChartData : [];
            var labels = Array.isArray(data) ? data.map(function (d) { return d.date || ''; }) : [];
            var roasValues = Array.isArray(data) ? data.map(function (d) { return typeof d.roas === 'number' ? d.roas : parseFloat(d.roas) || 0; }) : [];
            var canvas = document.getElementById('roasTrendChart');
            if (!canvas || typeof Chart === 'undefined') return;
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ trans("ROAS") }}',
                        data: roasValues,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    var v = ctx.parsed.y;
                                    return (v != null ? v.toFixed(2) : '') + ' ROAS';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxRotation: 0, font: { size: 11 }, color: 'rgba(0,0,0,0.4)' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { font: { size: 11 }, color: 'rgba(0,0,0,0.4)' }
                        }
                    }
                }
            });
        })();

        (function () {
            function qsAll(sel) { return Array.prototype.slice.call(document.querySelectorAll(sel)); }

            function collapse(detailId) {
                const childRows = qsAll('tr[data-parent-id="' + detailId + '"]');
                childRows.forEach(function (row) {
                    row.style.display = 'none';
                    const childId = row.getAttribute('data-detail-id');
                    const toggle = row.querySelector('[data-toggle-detail]');
                    if (toggle) {
                        toggle.setAttribute('data-expanded', '0');
                        toggle.textContent = '→';
                    }
                    if (childId) {
                        collapse(childId);
                    }
                });
            }

            function expand(detailId) {
                const childRows = qsAll('tr[data-parent-id="' + detailId + '"]');
                childRows.forEach(function (row) {
                    row.style.display = '';
                });
            }

            function toggleRow(detailId) {
                const row = document.querySelector('tr[data-detail-id="' + detailId + '"]');
                if (!row) return;
                const btn = row.querySelector('[data-toggle-detail]');
                if (!btn) return;
                const expanded = btn.getAttribute('data-expanded') === '1';
                if (expanded) {
                    btn.setAttribute('data-expanded', '0');
                    btn.textContent = '→';
                    collapse(detailId);
                } else {
                    btn.setAttribute('data-expanded', '1');
                    btn.textContent = '↓';
                    expand(detailId);
                }
            }

            function initToggleHandlers() {
                qsAll('[data-toggle-detail]').forEach(function(btn) {
                    // Check if handler already attached
                    if (btn.dataset.handlerAttached === 'true') return;
                    btn.dataset.handlerAttached = 'true';
                    
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent row click if row has onclick

                        const detailId = btn.getAttribute('data-toggle-detail');
                        if (!detailId) return;
                        toggleRow(detailId);
                    });
                });

                // Branch system: click on campaign name (first cell) to expand/collapse children
                const tbody = document.querySelector('.ads-analytics table tbody');
                if (tbody && !tbody.dataset.branchClickAttached) {
                    tbody.dataset.branchClickAttached = 'true';
                    tbody.addEventListener('click', function(e) {
                        const cell = e.target.closest('td.branch-expandable-cell');
                        if (!cell) return;
                        // Ignore if user clicked a link, button, or action inside the cell (e.g. future icons)
                        if (e.target.closest('a, button, [onclick]')) return;
                        const row = cell.closest('tr');
                        if (!row || row.getAttribute('data-has-children') !== '1') return;
                        const detailId = row.getAttribute('data-detail-id');
                        if (detailId) toggleRow(detailId);
                    });
                }
            }

            // Initialize on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initToggleHandlers);
            } else {
                initToggleHandlers();
            }
        })();

        // Store current account ID and optional detail ID for View All functionality
        let currentAccountId = null;
        let currentAccountName = null;
        let currentDetailId = null;

        // ROAS Breakdown Modal Functions
        // accountId, subtitle (account name or detail name), optional detailId for campaign/adset scope
        function showRoasBreakdown(accountId, accountNameOrSubtitle, detailId) {
            currentAccountId = accountId;
            currentAccountName = accountNameOrSubtitle;
            currentDetailId = detailId || null;
            
            const modal = document.getElementById('roasBreakdownModal');
            const title = document.getElementById('roasModalTitle');
            const subtitle = document.getElementById('roasModalSubtitle');
            
            title.textContent = '{{ trans("ROAS Breakdown") }}';
            subtitle.textContent = accountNameOrSubtitle;
            
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Show loading state
            document.getElementById('attributedRevenue').textContent = '—';
            document.getElementById('totalRevenueWithoutShipping').textContent = '—';
            document.getElementById('totalCombinedOrders').textContent = '—';
            document.getElementById('trueRoas').textContent = '—';
            document.getElementById('roasByStatus').innerHTML = '<div class="roas-loading">{{ trans("Loading...") }}</div>';
            document.getElementById('topProducts').innerHTML = '<div class="roas-loading">{{ trans("Loading...") }}</div>';
            
            // Set up View All click handler (pass detailId so products are scoped to same campaign/children)
            const viewAllLink = document.querySelector('.roas-view-all');
            if (viewAllLink) {
                viewAllLink.onclick = function(e) {
                    e.preventDefault();
                    showAllProducts(accountId, accountNameOrSubtitle, currentDetailId);
                };
            }
            
            // Fetch data (with optional detail_id to scope to campaign/adset and its children)
            let url = '{{ url("/admin/ads/accounts") }}/' + accountId + '/roas-breakdown';
            if (currentDetailId) {
                url += '?detail_id=' + encodeURIComponent(currentDetailId);
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        closeRoasBreakdown();
                        return;
                    }
                    
                    // Update metrics
                    document.getElementById('attributedRevenue').textContent = formatCurrency(data.attributed_revenue || 0);
                    document.getElementById('totalRevenueWithoutShipping').textContent = formatCurrency(data.total_revenue_without_shipping || 0);
                    document.getElementById('totalCombinedOrders').textContent = (data.total_combined_orders != null ? data.total_combined_orders : 0);
                    document.getElementById('trueRoas').textContent = (data.true_roas || 0).toFixed(2);
                    
                    // Update ROAS by status (bar fill = revenue percentage)
                    const statusContainer = document.getElementById('roasByStatus');
                    if (data.roas_by_status && data.roas_by_status.length > 0) {
                        statusContainer.innerHTML = data.roas_by_status.map(status => {
                            const percentage = parseFloat(status.percentage) || 0;
                            const width = Math.min(100, Math.max(0, percentage));
                            const percentageText = percentage.toFixed(1);
                            const color = getStatusColor(status.status);
                            // Show percentage inside bar if wide enough (>= 20%), otherwise outside
                            const showPercentageInside = width >= 20;
                            const ordersCount = parseInt(status.orders_count || 0);
                            const revenueWithShipping = parseFloat(status.revenue || 0);
                            const revenueWithoutShipping = parseFloat(status.revenue_without_shipping || 0);
                            return `
                                <div class="roas-status-item">
                                    <div class="roas-status-item-header">
                                        <div class="roas-status-bar">
                                            <div class="roas-status-bar-fill" style="width: ${width}%; background: ${color};">
                                                ${showPercentageInside ? `<span class="roas-status-percentage">${percentageText}%</span>` : ''}
                                            </div>
                                            ${!showPercentageInside ? `<span class="roas-status-percentage-outside">${percentageText}%</span>` : ''}
                                        </div>
                                        <div class="roas-status-label">${status.label}</div>
                                        <div class="roas-status-value">${(status.roas != null ? status.roas : 0).toFixed(1)} ROAS</div>
                                    </div>
                                    <div class="roas-status-item-details">
                                        <div class="roas-status-detail">
                                            <div class="roas-status-detail-label">{{ trans('Orders') }}</div>
                                            <div class="roas-status-detail-value">${ordersCount}</div>
                                        </div>
                                        <div class="roas-status-detail">
                                            <div class="roas-status-detail-label">{{ trans('Total Sale (With Shipping)') }}</div>
                                            <div class="roas-status-detail-value">${formatCurrency(revenueWithShipping)}</div>
                                        </div>
                                        <div class="roas-status-detail">
                                            <div class="roas-status-detail-label">{{ trans('Total Sale (Without Shipping)') }}</div>
                                            <div class="roas-status-detail-value">${formatCurrency(revenueWithoutShipping)}</div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        statusContainer.innerHTML = '<div class="roas-loading">{{ trans("No data available") }}</div>';
                    }
                    
                    // Update top products
                    const productsContainer = document.getElementById('topProducts');
                    if (data.top_products && data.top_products.length > 0) {
                        productsContainer.innerHTML = data.top_products.map(product => {
                            const roasClass = product.roas >= 4 ? 'high' : product.roas >= 2 ? 'medium' : 'low';
                            const thumb = product.image
                                ? `<img src="${product.image}" alt="" class="roas-product-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                   <span class="roas-product-icon" style="display:none;">📦</span>`
                                : `<span class="roas-product-icon">📦</span>`;
                            return `
                                <div class="roas-product-item">
                                    <div class="roas-product-thumb">${thumb}</div>
                                    <div class="roas-product-name">${product.name}</div>
                                    <div class="roas-product-roas ${roasClass}">${product.roas.toFixed(1)} ROAS</div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        productsContainer.innerHTML = '<div class="roas-loading">{{ trans("No products available") }}</div>';
                    }
                    
                    // Ensure View All link has click handler
                    const viewAllLink = document.querySelector('.roas-view-all');
                    if (viewAllLink) {
                        viewAllLink.onclick = function(e) {
                            e.preventDefault();
                            showAllProducts(currentAccountId, currentAccountName, currentDetailId);
                        };
                    }
                })
                .catch(error => {
                    console.error('Error fetching ROAS breakdown:', error);
                    alert('{{ trans("Error loading ROAS breakdown data") }}');
                    closeRoasBreakdown();
                });
        }

        function closeRoasBreakdown() {
            const modal = document.getElementById('roasBreakdownModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function formatCurrency(amount) {
            const symbol = 'EGP'; 
            const decimals = 2;
            const formatted = parseFloat(amount || 0).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            @php
                $symbolFormat = 1;
                $isSymbolBefore = in_array($symbolFormat, [1, 3]);
            @endphp
            @if($isSymbolBefore)
                return symbol + ' ' + formatted;
            @else
                return formatted + ' ' + symbol;
            @endif
        }

        function getStatusColor(status) {
            const colors = {
                'delivered': '#22c55e',
                'confirmed': '#3b82f6',
                'pending': '#f59e0b',
                'returned': '#ef4444',
            };
            return colors[status] || '#6b7280';
        }

        function downloadRoasReport() {
            // TODO: Implement report download
            alert('{{ trans("Report download feature coming soon") }}');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRoasBreakdown();
                closeProductsModal();
            }
        });

        // Products Modal Functions (detailId optional - when set, products are scoped to that campaign/children)
        function showAllProducts(accountId, accountName, detailId) {
            const modal = document.getElementById('roasProductsModal');
            const title = document.getElementById('roasProductsModalTitle');
            
            title.textContent = '{{ trans("All Products") }} - ' + accountName;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Show loading
            const productsTableBody = document.getElementById('roasProductsTableBody');
            productsTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:40px; color:var(--muted);">{{ trans("Loading...") }}</td></tr>';
            
            let productsUrl = '{{ url("/admin/ads/accounts") }}/' + accountId + '/products';
            if (detailId) {
                productsUrl += '?detail_id=' + encodeURIComponent(detailId);
            }
            fetch(productsUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        productsTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:40px; color:rgba(239,68,68,0.8);">' + data.error + '</td></tr>';
                        return;
                    }
                    
                    if (data.products && data.products.length > 0) {
                        productsTableBody.innerHTML = data.products.map((product, index) => {
                            const roasClass = product.roas >= 4 ? 'high' : product.roas >= 2 ? 'medium' : 'low';
                            return `
                                <tr>
                                    <td style="width:50px; text-align:center; color:var(--muted2);">${index + 1}</td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            <div class="roas-product-icon" style="width:36px; height:36px; font-size:16px;">📦</div>
                                            <div style="font-weight:600; color:var(--text);">${product.name}</div>
                                        </div>
                                    </td>
                                    <td style="text-align:right; font-weight:700; color:var(--text);">${formatCurrency(product.revenue)}</td>
                                    <td style="text-align:center; color:var(--muted);">${product.quantity || 0}</td>
                                    <td style="text-align:right;">
                                        <span class="roas-product-roas ${roasClass}" style="font-weight:800;">${product.roas.toFixed(2)}</span>
                                    </td>
                                </tr>
                            `;
                        }).join('');
                    } else {
                        productsTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:40px; color:var(--muted);">{{ trans("No products found") }}</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    productsTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:40px; color:rgba(239,68,68,0.8);">{{ trans("Error loading products") }}</td></tr>';
                });
        }

        function closeProductsModal() {
            const modal = document.getElementById('roasProductsModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Initialize date range picker
        $(document).ready(function() {
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.dateRange) {
                AIZ.plugins.dateRange();
            }
            
            // Auto-submit form on date change
            $('.aiz-date-range').on('apply.daterangepicker', function(ev, picker) {
                $(this).closest('form').submit();
            });
            
            // Handle search input - submit on Enter key
            $('input[name="search"]').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $(this).closest('form').submit();
                }
            });
            
            // Optional: Auto-submit after user stops typing (debounce)
            let searchTimeout;
            $('input[name="search"]').on('input', function() {
                clearTimeout(searchTimeout);
                const $form = $(this).closest('form');
                const searchValue = $(this).val();
                
                // Only auto-submit if search field is not empty and user stopped typing for 500ms
                if (searchValue.length > 0) {
                    searchTimeout = setTimeout(function() {
                        $form.submit();
                    }, 500);
                } else if (searchValue.length === 0 && '{{ request("search") }}') {
                    // If search is cleared, submit immediately
                    $form.submit();
                }
            });
        });
    </script>
@endsection

