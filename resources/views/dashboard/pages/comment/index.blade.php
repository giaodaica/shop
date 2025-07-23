@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý bình luận</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Thương mại điện tử</a></li>
                                <li class="breadcrumb-item active">Quản lý bình luận</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- end page title -->
            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <div>
                        <div class="card">
                            <div class="card-header border-0">
                                <h5 class="mb-0">Danh sách bình luận</h5>
                            </div>
                            <div class="card-body">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>STT</th>
                                            <th>Sản phẩm</th>
                                            <th>Người bình luận</th>
                                            <th>Ảnh sản phẩm</th>
                                            <th>Nội dung</th>
                                            <th>Đánh giá</th>
                                            <th>Trạng thái</th>
                                            <th>Phản hồi của Admin</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($comments as $index => $comment)
                                            <tr>
                                                <td>{{ $comments->firstItem() + $index }}</td>
                                                <td>{{ $comment->product->name ?? 'Không có' }}</td>
                                                <td>{{ $comment->user->name ?? 'Ẩn danh' }}</td>
                                                <td>
                                                    @if($comment->product && $comment->product->image_url)
                                                        <img src="{{ asset($comment->product->image_url) }}" alt="Ảnh sản phẩm" width="80" height="80" style="object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </td>

                                                <td>{{ $comment->content }}</td>
                                                <td>{{ $comment->rating ?? 'Không có' }}</td>
                                                <td>
                                                    @if ($comment->is_show)
                                                        <span class="badge bg-success">Hiển thị</span>
                                                    @else
                                                        <span class="badge bg-secondary">Đã ẩn</span>
                                                    @endif
                                                </td>
                                                 <td>
                                                    @if ($comment->admin_reply)
                                                        <div class="bg-light text-dark p-2 mt-2 rounded">
                                                            <strong class="text-info">Admin đã phản hồi:</strong><br>
                                                            {{ $comment->admin_reply }}
                                                        </div>
                                                    @else
                                                    <span class="text-dark">Không có</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('dashboard.comments.update', $comment->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="is_show" value="{{ $comment->is_show ? 0 : 1 }}">
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $comment->is_show ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                            title="{{ $comment->is_show ? 'Ẩn bình luận này' : 'Hiển thị lại bình luận' }}">
                                                            <i class="ri-eye-{{ $comment->is_show ? 'off' : 'line' }}"></i>
                                                            {{ $comment->is_show ? 'Ẩn' : 'Hiện' }}
                                                        </button>
                                                    </form>

                                                </td>
                                               
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có bình luận nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    {{ $comments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection

@section('js-content')
    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('hide');
            }
        }, 3000);
    </script>
@endsection