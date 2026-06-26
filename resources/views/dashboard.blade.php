@extends('layouts.admin')
@section('title', __('messages.menu_dashboard'))

@push('styles')
    <style>
        .stats-card-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            border-radius: 50%;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media(max-width:768px) {
            .chart-container {
                height: 250px;
            }
        }

        .stat-border-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .stat-border-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    {{-- Welcome Banner --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card overflow-hidden" style="background:linear-gradient(135deg,#696cff 0%,#9155fd 100%);">
                <div class="d-flex align-items-center row">
                    <div class="col-sm-7">
                        <div class="card-body text-white py-4">
                            <h4 class="text-white mb-1">{{ __('messages.welcome_back') }}, {{ auth()->user()->name }} 👋</h4>
                            <p class="mb-3" style="opacity:.8;">{{ __('messages.store_overview') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('purchases.index') }}" class="btn btn-light btn-sm"><i
                                        class="bx bx-cart-download me-1"></i> {{ __('messages.purchases') }}</a>
                                <a href="{{ route('products.create') }}" class="btn btn-outline-light btn-sm"><i
                                        class="bx bx-plus me-1"></i> {{ __('messages.add_products') }}</a>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-sm"><i
                                        class="bx bx-group me-1"></i> {{ __('messages.users') }}</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-5 d-none d-sm-flex align-items-center justify-content-center py-3">
                        <div class="text-center text-white" id="dashboardClock">
                            <div id="dashClock-time"
                                style="font-size:2.6rem;font-weight:700;letter-spacing:.03em;line-height:1.1;font-variant-numeric:tabular-nums;">
                                --:--:--</div>
                            <div id="dashClock-ampm"
                                style="font-size:1rem;font-weight:600;opacity:.75;margin-top:2px;letter-spacing:.1em;">--
                            </div>
                            <div id="dashClock-date" style="font-size:.9rem;opacity:.8;margin-top:6px;font-weight:500;">--
                                -- ----</div>
                            <div id="dashClock-day"
                                style="font-size:.78rem;opacity:.6;margin-top:2px;letter-spacing:.08em;text-transform:uppercase;">
                </div>
            </div>
        </div>
    </div>

    {{-- Toggle metrics button --}}
    <div class="d-flex justify-content-start mb-4">
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardMetricsCollapse" aria-expanded="false" aria-controls="dashboardMetricsCollapse" id="toggleMetricsBtn" style="border-radius:8px; padding:10px 20px; transition: all 0.2s ease;">
            <i class="bx bx-show fs-5"></i>
            <span>Show Financial & Transaction Metrics</span>
        </button>
    </div>

    {{-- Collapsible metrics wrapper --}}
    <div class="collapse" id="dashboardMetricsCollapse">
        {{-- Today Stats --}}
        <div class="row mb-2">
            <div class="col-12">
                <p class="text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em;">
                    {{ strtoupper(__('messages.today')) }}</p>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card stat-border-card border-primary h-100">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('messages.todays_purchases') }}</p>
                            <h3 class="mb-0 text-primary">{{ $todayPurchases ?? 0 }}</h3>
                        </div>
                        <span class="stats-card-icon bg-label-primary"><i class="bx bx-cart-download"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card stat-border-card border-success h-100">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('messages.todays_sales') }}</p>
                            <h3 class="mb-0 text-success">{{ format_currency($todaySales ?? 0) }}</h3>
                        </div>
                        <span class="stats-card-icon bg-label-success"><i class="bx bx-rupee"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 mb-4">
                <div class="card stat-border-card border-warning h-100">
                    <div class="card-body py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">{{ __('messages.pending_sales') }}</p>
                            <h3 class="mb-0 text-warning">{{ $pendingSales ?? 0 }}</h3>
                        </div>
                        <span class="stats-card-icon bg-label-warning"><i class="bx bx-time-five"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overall Stats --}}
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-muted fw-semibold mb-2" style="font-size:.75rem;letter-spacing:.08em;">
                    {{ strtoupper(__('messages.overall')) }}</p>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.total_revenue') }}</p>
                                <h4 class="mb-1 fw-bold">{{ format_currency($totalRevenue ?? 0) }}</h4>
                                <small class="text-success"><i class="bx bx-trending-up"></i>
                                    {{ __('messages.from_sales') }}</small>
                            </div>
                            <span class="avatar-initial rounded-circle bg-label-success p-3" style="font-size:1.5rem;"><i
                                    class="bx bx-rupee"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.total_purchases') }}</p>
                                <h4 class="mb-1 fw-bold">{{ $totalPurchases ?? 0 }}</h4>
                                <small class="text-info"><i class="bx bx-check-circle"></i>
                                    {{ __('messages.all_time') }}</small>
                            </div>
                            <span class="avatar-initial rounded-circle bg-label-info p-3" style="font-size:1.5rem;"><i
                                    class="bx bx-cart-download"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-muted small">{{ __('messages.total_users') }}</p>
                                <h4 class="mb-1 fw-bold">{{ $totalUsers ?? 0 }}</h4>
                                <small class="text-primary"><i class="bx bx-user-check"></i>
                                    {{ __('messages.registered') }}</small>
                            </div>
                            <span class="avatar-initial rounded-circle bg-label-primary p-3" style="font-size:1.5rem;"><i
                                    class="bx bx-group"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Sales --}}
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('messages.recent_sales') }}</h5>
                        <a href="{{ route('sales.index') }}"
                            class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover align-middle mb-0">
                          <thead class="table-light">
                              <tr>
                                  <th>{{ __('messages.th_invoice') }}</th>
                                  <th>{{ __('messages.th_customer') }}</th>
                                  <th>{{ __('messages.th_total') }}</th>
                                  <th>{{ __('messages.th_paid') }}</th>
                                  <th>{{ __('messages.th_status') }}</th>
                                  <th>{{ __('messages.th_date') }}</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse($recentSales ?? [] as $sale)
                                  <tr>
                                      <td><strong class="text-primary">{{ $sale->invoice_no }}</strong></td>
                                      <td>{{ $sale->customer->name ?? '-' }}</td>
                                      <td><strong>{{ format_currency($sale->grand_total) }}</strong></td>
                                      <td class="text-success">{{ format_currency($sale->paid_amount) }}</td>
                                      <td>
                                          @if ($sale->status === 'Completed')
                                              <span
                                                  class="badge rounded-pill bg-success">{{ __('messages.completed') }}</span>
                                          @elseif($sale->status === 'Draft')
                                              <span
                                                  class="badge rounded-pill bg-warning text-dark">{{ __('messages.draft') }}</span>
                                          @else
                                              <span
                                                  class="badge rounded-pill bg-danger">{{ __('messages.cancelled') }}</span>
                                          @endif
                                      </td>
                                      <td><small class="text-muted">{{ $sale->invoice_date }}</small></td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="6" class="text-center text-muted py-4">
                                          {{ __('messages.no_recent_sales') }}</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">{{ __('messages.quick_stats') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">{{ $totalCategories ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('messages.categories') }}</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="border-end">
                                    <h4 class="text-success mb-1">{{ $totalBrands ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('messages.brands') }}</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="border-end">
                                    <h4 class="text-warning mb-1">{{ $totalSuppliers ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('messages.suppliers') }}</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <h4 class="text-info mb-1">{{ $totalCustomers ?? 0 }}</h4>
                                <small class="text-muted">{{ __('messages.customers') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function() {
            function tick() {
                // Clock element is not used in the simplified UI, but we keep the stub if any layout looks for it
            }
            tick();
            setInterval(tick, 1000);
        })();

        $(document).ready(function() {
            // Handle Collapse toggle text/icon animation
            $('#dashboardMetricsCollapse').on('show.bs.collapse', function () {
                $('#toggleMetricsBtn')
                    .html('<i class="bx bx-hide fs-5"></i> <span>Hide Financial & Transaction Metrics</span>')
                    .removeClass('btn-primary')
                    .addClass('btn-outline-primary');
            });
            $('#dashboardMetricsCollapse').on('hide.bs.collapse', function () {
                $('#toggleMetricsBtn')
                    .html('<i class="bx bx-show fs-5"></i> <span>Show Financial & Transaction Metrics</span>')
                    .removeClass('btn-outline-primary')
                    .addClass('btn-primary');
            });
        });
    </script>
@endpush
