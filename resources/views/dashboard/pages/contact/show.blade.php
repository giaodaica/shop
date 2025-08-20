{{-- filepath: c:\Users\ADMIN\Documents\GitHub\shop\resources\views\dashboard\pages\contact\show.blade.php --}}
@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card mx-auto" style="max-width:900px;">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Chi tiết liên hệ #{{ $contact->id }}</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless align-middle" style="font-size: 1.08rem;">
                        <tr>
                            <th style="width:200px;">Họ tên người gửi:</th>
                            <td>{{ $contact->name }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $contact->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $contact->email }}</td>
                        </tr>
                        <tr>
                            <th>Tiêu đề:</th>
                            <td>{{ $contact->title }}</td>
                        </tr>
                        <tr>
                            <th>Nội dung:</th>
                            <td>
                                <div style="background:#f8f9fa; border-left:4px solid #198754; border-radius:4px; padding:16px 20px; min-height:100px; white-space:pre-line; font-size:1.08rem;">
                                    {{ $contact->content }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Người Phản Hồi</th>
                            <td>{{ $contact->user_name ?? 'Chưa phản hồi' }}</td>
                        </tr>
                        <tr>
                            <th>Phản hồi admin:</th>
                            <td>
                                <div style="background:#f1f3f4; border-left:4px solid #0d6efd; border-radius:4px; padding:16px 20px; min-height:80px; white-space:pre-line;">
                                    {{ $contact->admin_reply ?? 'Chưa trả lời' }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Thời gian phản hồi:</th>
                            <td>{{ $contact->time_reply ?? 'Chưa phản hồi' }}</td>
                        </tr>
                        <tr>
                            <th>Đã phản hồi?</th>
                            <td>
                                @if ($contact->is_replied)
                                    <span class="badge bg-success">Đã phản hồi</span>
                                @else
                                    <span class="badge bg-secondary">Chưa phản hồi</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày gửi:</th>
                            <td>{{ $contact->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lúc:</th>
                            <td>{{ $contact->updated_at }}</td>
                        </tr>
                    </table>

                    {{-- Form phản hồi admin --}}
                    @if (!$contact->is_replied)
                        <form action="{{ route('contact.reply', $contact->id) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="reply_title" class="form-label">Tiêu đề email</label>
                                <input type="text" name="reply_title" id="reply_title" class="form-control"
                                    value="Phản hồi liên hệ: {{ $contact->title }}">
                                @error('reply_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="admin_reply" class="form-label">Nội dung email</label>
                                <textarea name="admin_reply" id="admin_reply" class="form-control" rows="8">{{ old('admin_reply') }}</textarea>
                                @error('admin_reply')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <a class="btn btn-secondary" href="{{ route('contact.index') }}">Quay Lại</a>
                            <button type="submit" class="btn btn-success">Gửi phản hồi &amp; Email</button>
                        </form>
                    @else
                        <div class="alert alert-success mt-4">Đã phản hồi khách hàng.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
            });
        @endif
    </script>
@endsection
