{{-- filepath: c:\Users\ADMIN\Documents\GitHub\shop\resources\views\dashboard\pages\contact\index.blade.php --}}
@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white">Danh sách liên hệ</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Tiêu đề</th>
                                    <th>Ngày gửi</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $contact)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td>{{ $contact->title }}</td>
                                        <td>{{ formatDate($contact->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('contact.show', $contact->id) }}"
                                                class="btn btn-sm btn-info">Xem</a>
                                            <form action="{{ route('contact.destroy', $contact->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('post')
                                                <button class="btn btn-sm btn-danger">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Chưa có liên hệ nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $data->links('') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
