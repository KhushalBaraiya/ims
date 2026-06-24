@extends('layouts.admin')
@section('title', __('messages.activity_logs'))

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ __('messages.activity_logs') }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.activity_logs') }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bx bx-filter-alt me-2 text-primary"></i>{{ __('messages.filters') }}</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('activity-logs.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.search') }}</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                            value="{{ request('search') }}" placeholder="{{ __('messages.search_activity_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">{{ __('messages.user') }}</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">{{ __('messages.all_users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_from') }}</label>
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">{{ __('messages.date_to') }}</label>
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bx bx-search me-1"></i>{{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-reset"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Logs Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bx bx-history me-2 text-info"></i>{{ __('messages.activity_log_list') }}
                <span class="badge bg-label-info ms-1">{{ $logs->total() }}</span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">{{ __('messages.th_no') }}</th>
                            <th>{{ __('messages.activity') }}</th>
                            <th>{{ __('messages.description_label') }}</th>
                            <th>{{ __('messages.user') }}</th>
                            <th>{{ __('messages.ip_address') }}</th>
                            <th>{{ __('messages.th_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $logs->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ $log->activity }}</span>
                                </td>
                                <td class="text-muted small" style="max-width:300px;">
                                    {{ $log->description ?? '—' }}
                                </td>
                                <td>
                                    @if ($log->user)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-label-primary fw-bold"
                                                style="width:28px;height:28px;font-size:.75rem;">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </div>
                                            <span class="small fw-semibold">{{ $log->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ __('messages.system') }}</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $log->ip_address ?? '—' }}</td>
                                <td class="small text-muted">
                                    <span title="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bx bx-history" style="font-size:2.5rem;opacity:.2;"></i>
                                    <p class="mt-2 mb-0">{{ __('messages.no_records') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($logs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection
