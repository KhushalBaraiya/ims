@extends('layouts.admin')
@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold fs-4">{{ __('admin.save_card_list') }}</h4>
            <a href="{{ route('admin.save-card.create') }}" class="btn btn-primary btn-lg">
                <i class="bx bx-plus"></i> {{ __('admin.add_save_card') }}
            </a>
        </div>
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0 fs-5" id="cardTable">
                <thead>
                    <tr>
                        <th>{{ __('admin.id') }}</th>
                        <th>{{ __('admin.card_information') }}</th>
                        <th>{{ __('admin.user') }}</th>
                        <th>{{ __('admin.expiry') }}</th>
                        <th>{{ __('admin.gateway_token') }}</th>
                        <th class="text-center">{{ __('admin.status') }}</th>
                        <th class="text-center">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cards as $card)
                        <tr>
                            <td>{{ $card->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $brandLabelClass =
                                            [
                                                'Visa' => 'bg-label-primary',
                                                'Mastercard' => 'bg-label-danger',
                                                'RuPay' => 'bg-label-success',
                                                'Amex' => 'bg-label-info',
                                            ][$card->card_brand] ?? 'bg-label-secondary';
                                        $brandBadgeClass =
                                            [
                                                'Visa' => 'bg-primary',
                                                'Mastercard' => 'bg-danger',
                                                'RuPay' => 'bg-success',
                                                'Amex' => 'bg-info',
                                            ][$card->card_brand] ?? 'bg-secondary';
                                    @endphp
                                    <div
                                        class="tbl-img-round d-flex align-items-center justify-content-center {{ $brandLabelClass }}">
                                        <i class="bx bx-credit-card"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $card->card_holder_name }}</strong><br>
                                        <small class="text-muted">
                                            <span
                                                class="badge {{ $brandBadgeClass }} me-1 rounded-pill px-3 py-1">{{ $card->card_brand ?? __('admin.card_default') }}</span>
                                            •••• {{ $card->last_four_digits }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $card->user->name ?? __('admin.dash') }}</td>
                            <td>
                                <span class="badge bg-label-secondary text-dark rounded-pill px-3 py-1">
                                    {{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->expiry_year }}
                                </span>
                            </td>
                            <td>
                                @if ($card->gateway_token)
                                    <code class="text-muted">{{ Str::limit($card->gateway_token, 20) }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button
                                    class="status-toggle-btn badge rounded-pill border px-3 py-2 bg-transparent {{ $card->status === 'active' ? 'border-success text-success' : 'border-danger text-danger' }}"
                                    data-id="{{ $card->id }}" data-model="SaveCard">
                                    {{ $card->status === 'active' ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.save-card.show', $card->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-info rounded-circle me-2 btn-action"
                                    title="{{ __('admin.view') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="{{ route('admin.save-card.edit', $card->id) }}"
                                    class="btn btn-sm btn-icon btn-outline-primary rounded-circle me-2 btn-action">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <form action="{{ route('admin.save-card.destroy', $card->id) }}" method="POST"
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
            $('#cardTable').DataTable({
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