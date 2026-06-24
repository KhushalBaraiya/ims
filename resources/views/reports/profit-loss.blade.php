@extends('layouts.admin')
@section('title', 'Profit & Loss Report')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Profit & Loss Report</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Profit & Loss</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-success btn-sm flex-fill"><i
                            class="bx bx-search me-1"></i>Apply</button>
                    <a href="{{ route('reports.profit-loss') }}" class="btn btn-outline-secondary btn-sm"><i
                            class="bx bx-reset"></i></a>
                </div>
                <div class="col-md-4 d-flex gap-2 justify-content-end flex-wrap">
                    <a href="?date_from={{ now()->startOfMonth()->format('Y-m-d') }}&date_to={{ now()->format('Y-m-d') }}"
                        class="btn btn-outline-secondary btn-sm">This Month</a>
                    <a href="?date_from={{ now()->subMonth()->startOfMonth()->format('Y-m-d') }}&date_to={{ now()->subMonth()->endOfMonth()->format('Y-m-d') }}"
                        class="btn btn-outline-secondary btn-sm">Last Month</a>
                    <a href="?date_from={{ now()->startOfYear()->format('Y-m-d') }}&date_to={{ now()->format('Y-m-d') }}"
                        class="btn btn-outline-secondary btn-sm">This Year</a>
                </div>
            </form>
        </div>
    </div>

    {{-- P&L Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #696cff!important;">
                <div class="card-body py-4 text-center">
                    <i class="bx bx-trending-up text-primary mb-2" style="font-size:2rem;display:block;"></i>
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h4 class="fw-bold text-primary mb-0">{{ format_currency($totalRevenue) }}</h4>
                    <small class="text-muted">{{ $totalSalesCount }} completed invoices</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #ff3e1d!important;">
                <div class="card-body py-4 text-center">
                    <i class="bx bx-package text-danger mb-2" style="font-size:2rem;display:block;"></i>
                    <p class="text-muted small mb-1">Cost of Goods Sold</p>
                    <h4 class="fw-bold text-danger mb-0">{{ format_currency($totalCogs) }}</h4>
                    <small class="text-muted">Purchase cost of sold items</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #71dd37!important;">
                <div class="card-body py-4 text-center">
                    <i class="bx bx-dollar-circle text-success mb-2" style="font-size:2rem;display:block;"></i>
                    <p class="text-muted small mb-1">Gross Profit</p>
                    <h4 class="fw-bold {{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        {{ format_currency($grossProfit) }}</h4>
                    <small class="text-muted">Revenue − COGS</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"
                style="border-top:4px solid {{ $netProfit >= 0 ? '#71dd37' : '#ff3e1d' }}!important;">
                <div class="card-body py-4 text-center">
                    <i class="bx bx-award {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-2"
                        style="font-size:2rem;display:block;"></i>
                    <p class="text-muted small mb-1">Net Profit</p>
                    <h4 class="fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        {{ format_currency($netProfit) }}</h4>
                    <small class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold">Margin:
                        {{ $profitMargin }}%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- P&L Statement --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-file me-2 text-success"></i>P&L Statement</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">Income</td>
                            </tr>
                            <tr>
                                <td class="ps-4">Gross Revenue</td>
                                <td class="text-end fw-semibold text-primary">{{ format_currency($totalRevenue) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">— Sale Returns</td>
                                <td class="text-end text-danger small">({{ format_currency($totalSaleReturns) }})</td>
                            </tr>
                            <tr class="border-top">
                                <td class="ps-4 fw-bold">Net Revenue</td>
                                <td class="text-end fw-bold text-primary">{{ format_currency($netRevenue) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">Expenses</td>
                            </tr>
                            <tr>
                                <td class="ps-4">Cost of Goods Sold (COGS)</td>
                                <td class="text-end text-danger">{{ format_currency($totalCogs) }}</td>
                            </tr>
                            <tr class="border-top">
                                <td class="ps-4 fw-bold">Gross Profit</td>
                                <td class="text-end fw-bold {{ $grossProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($grossProfit) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="2" class="fw-bold ps-4 py-2 small text-uppercase text-muted">Adjustments
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">Purchase Returns (Recovered)</td>
                                <td class="text-end text-success">+{{ format_currency($totalPurchaseReturns) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">Tax Collected</td>
                                <td class="text-end small text-muted">{{ format_currency($totalTaxCollected) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted small">Discounts Given</td>
                                <td class="text-end small text-danger">({{ format_currency($totalDiscounts) }})</td>
                            </tr>
                            <tr class="border-top table-success">
                                <td class="ps-4 fw-bold fs-6">Net Profit / (Loss)</td>
                                <td class="text-end fw-bold fs-6 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($netProfit) }}
                                    <br><small class="fw-normal">Margin: {{ $profitMargin }}%</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Monthly Breakdown --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Monthly Revenue
                        Breakdown</h6>
                </div>
                <div class="card-body p-0">
                    @if ($monthlyData->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Month</th>
                                        <th class="text-end">Invoices</th>
                                        <th class="text-end">Revenue</th>
                                        <th>Revenue Bar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $maxRev = $monthlyData->max('revenue') ?: 1; @endphp
                                    @foreach ($monthlyData as $i => $m)
                                        <tr>
                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ \Carbon\Carbon::parse($m->month . '-01')->format('M Y') }}</td>
                                            <td class="text-end small">{{ $m->count }}</td>
                                            <td class="text-end fw-bold text-primary">{{ format_currency($m->revenue) }}
                                            </td>
                                            <td style="min-width:120px;">
                                                <div class="progress" style="height:8px;border-radius:4px;">
                                                    <div class="progress-bar bg-primary"
                                                        style="width:{{ round(($m->revenue / $maxRev) * 100) }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end">Total</td>
                                        <td class="text-end">{{ $monthlyData->sum('count') }}</td>
                                        <td class="text-end text-primary">
                                            {{ format_currency($monthlyData->sum('revenue')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bx bx-bar-chart-alt-2" style="font-size:3rem;opacity:.15;display:block;"></i>
                            <p class="mt-2 mb-0">No data for selected period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
