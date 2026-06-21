<!-- resources/views/demo/index.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>{{ __('admin.dashboard') }} - Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ __('admin.demo_list') }}</h2>
            <a href="{{ route('admin.demo.create') }}" class="btn btn-primary">
                {{ __('admin.add_demo') }}
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('admin.id') }}</th>
                            <th>{{ __('admin.image') }}</th>
                            <th>{{ __('admin.name') }}</th>
                            <th>{{ __('admin.email') }}</th>
                            <th>{{ __('admin.phone') }}</th>
                            <th>{{ __('admin.gender') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th width="200">{{ __('admin.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demos as $demo)
                            <tr>
                                <td>{{ $demo->id }}</td>
                                <td>
                                    @if ($demo->image)
                                        <img src="{{ asset('uploads/demo/' . $demo->image) }}" width="60"
                                            height="60" class="rounded">
                                    @endif
                                </td>
                                <td>{{ $demo->name }}</td>
                                <td>{{ $demo->email }}</td>
                                <td>{{ $demo->phone }}</td>
                                <td>{{ admin_label($demo->gender, 'gender') }}</td>
                                <td>

                                    @if ($demo->status == 'active')
                                        <span class="badge bg-success">
                                            {{ __('admin.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('admin.inactive') }}
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    <a href="{{ route('admin.demo.show', $demo->id) }}" class="btn btn-info btn-sm">
                                        {{ __('admin.view') }}
                                    </a>
                                    <a href="{{ route('admin.demo.edit', $demo->id) }}" class="btn btn-warning btn-sm">
                                        {{ __('admin.edit') }}
                                    </a>
                                    <form action="{{ route('admin.demo.destroy', $demo->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('admin.swal_are_you_sure') }}')">
                                            {{ __('admin.delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    {{ __('admin.no_data_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $demos->links() }}
            </div>
        </div>
    </div>
</body>

</html>
