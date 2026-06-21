@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.notification_list') }}</h4>
            <a href="{{ route('admin.notification.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.send_notification') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="notificationTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.message') }}</th>
                        <th>{{ __('admin.type') }}</th>
                        <th class="text-center">{{ __('admin.read') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th>{{ __('admin.date') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notif)
                        <tr>
                            <td>{{ $notif->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $typeColors = [
                                            'general' => 'secondary',
                                            'order' => 'primary',
                                            'promo' => 'warning',
                                            'alert' => 'danger',
                                        ];
                                        $color = $typeColors[$notif->type] ?? 'secondary';
                                    @endphp
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center bg-label-{{ $color }}">
                                        <i class="bx bx-bell text-{{ $color }}"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $notif->user->name ?? __('admin.dash') }}</strong><br>
                                        <small class="text-muted">{{ $notif->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ Str::limit($notif->message, 50) }}</td>
                            <td><span
                                    class="badge bg-{{ $color }} rounded-pill px-3 py-1">{{ admin_label($notif->type, 'nt') }}</span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $notif->is_read ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-1">
                                    {{ $notif->is_read ? __('admin.read') : __('admin.unread') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $notif->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $notif->id }}" data-model="Notification">
                                    {{ $notif->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td>{{ $notif->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.notification.show', $notif->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.notification.edit', $notif->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.notification.destroy', $notif->id) }}" method="POST"
                                    class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle btn-action btn-delete">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#notificationTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                ordering: true,
                searching: true,
                paging: true,
                info: true,
                order: [
                    [0, 'desc']
                ]
            });

            $(document).on('click', '.btn-delete', function() {
                const form = $(this).closest('.delete-form');
                Swal.fire({
                    title: '{{ __('admin.swal_are_you_sure') }}',
                    text: '{{ __('admin.swal_delete_text') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('admin.swal_yes_delete') }}',
                    cancelButtonText: '{{ __('admin.swal_cancel') }}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
