@extends('dashboard.layouts.layout')

@section('main-content')
    <style>
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f6;
            border: 1px solid #e2e2e5;
            border-radius: 3px;
            overflow: auto;
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
        }

        .dd-list,
        .dd-list .dd-list {
            padding-left: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            background: none !important;
        }

        .dd-list .dd-list {
            margin-left: 40px !important;
            border-left: none !important;
            background: none !important;
        }

        .dd-item {
            list-style: none !important;
            margin-bottom: 0 !important;
        }

        .nested-list-content {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 0 !important;
            min-height: 44px !important;
            background: #f8f9fa !important;
            border-bottom: 1px solid #e2e2e5 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin-bottom: 0 !important;
            position: relative;
        }

        .nested-list-content:last-child {
            border-bottom: none !important;
        }

        .dd-handle,
        .nested-list-handle {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            min-width: 40px !important;
            height: 44px !important;
            background: #e2e2e5 !important;
            border-right: 1px solid #d1d1d1 !important;
            cursor: move !important;
            font-size: 18px !important;
            color: #888 !important;
            margin-right: 0 !important;
            border-radius: 0 !important;
        }

        .m-checkbox {
            margin: 0 10px 0 0 !important;
            flex: 1 1 auto !important;
            display: flex !important;
            align-items: center !important;
        }

        .btnControll {
            margin-left: auto !important;
            display: flex !important;
            gap: 6px !important;
        }

        .nested-list-content>* {
            vertical-align: middle !important;
        }

        .dd-collapse,
        .dd-expand,
        .dd-collapse:before,
        .dd-expand:before {
            display: none !important;
        }
    </style>
    <div class="page-content" id="kt_page_sticky_card">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="lead text-right">
                                <div class="float-right">
                                    <!-- Đã xóa các nút Thu gọn, Chọn tất cả, Xóa mục đã chọn -->
                                </div>
                            </div>
                            <button type="button" data-url="{{ route('dashboard.roles.create') }}"
                                class="btn btn-success font-weight-bolder loadModal_toggle">
                                <i class="fas fa-plus-circle icon-md"></i> {{ __('Thêm mới') }}
                            </button>
                        </div>
                        <form action="{{ route('dashboard.roles.order') }}" method="POST" id="nestable-form">
                            @csrf
                            <div class="dd" id="nestable">
                                {!! $datatable !!}
                            </div>
                        </form>

                    </div>
                </div>


            </div>
        </div>
    </div>

    {{-- Modal load --}}
    <div class="modal fade" id="loadModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

    {{-- Modal xóa --}}
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dashboard.roles.destroy', 0) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Xác nhận thao tác') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body">{{ __('Bạn thực sự muốn xóa?') }}</div>

                    <div class="modal-footer">
                        <input type="hidden" name="id" class="id" />
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Xóa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        $(document).ready(function() {
            // Load modal edit/create
            $(document).on('click', '.loadModal_toggle, .edit_toggle', function(e) {
                e.preventDefault();
                const url = $(this).data("url");
                
                // Hiển thị loading
                $('#loadModal .modal-content').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>');
                
                // Hiển thị modal trước
                const modalEl = document.getElementById('loadModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                
                // Load content với timeout
                $('#loadModal .modal-content').load(url, function(response, status, xhr) {
                    if (status === 'error') {
                        $('#loadModal .modal-content').html('<div class="text-center p-4 text-danger">Lỗi tải dữ liệu</div>');
                        return;
                    }
                    
                    // Đợi một chút để đảm bảo DOM đã được render
                    setTimeout(function() {
                        initializeModalComponents();
                    }, 100);
                });
            });

            // Xử lý nút xóa
            $(document).on('click', '.delete_toggle', function(e) {
                e.preventDefault();
                const id = $(this).attr('rel');
                $('#deleteModal .id').val(id);
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
            
            // Xử lý submit form xóa bằng AJAX để hiển thị alert giống thêm/sửa
            $(document).on('submit', '#deleteModal form', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(form[0]);
                const url = form.attr('action');
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Đang xử lý...');

                // Đảm bảo có CSRF token
                if (!formData.has('_token')) {
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    if (csrfToken) {
                        formData.append('_token', csrfToken);
                    }
                }

                $.ajax({
                    url: url,
                    type: 'POST', // sử dụng POST + _method=DELETE
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Đóng modal và hiển thị thông báo thành công
                        $('#deleteModal').modal('hide');
                        if (typeof response === 'object' && response.message) {
                            showAlert('success', response.message);
                        } else {
                            showAlert('success', 'Xóa thành công!');
                        }
                        // Cập nhật lại dữ liệu bảng
                        updateTableData();
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra. Vui lòng thử lại.';
                        if (xhr.status === 403) {
                            try {
                                const res = JSON.parse(xhr.responseText);
                                message = res.message || message;
                            } catch (_) {}
                        }
                        showAlert('error', message + (xhr.status ? ' (Status: ' + xhr.status + ')' : ''));
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text(originalText);
                    }
                });
            });

                        // Function để khởi tạo các components trong modal
            function initializeModalComponents() {
                // Khởi tạo select2
                $('#loadModal .select2').select2({
                    dropdownParent: $('#loadModal')
                });
                
                // Khởi tạo form submission handler
                initializeFormSubmission();
                
                // Khởi tạo JSTree nếu có
                if ($('#kt_tree_3').length > 0) {
                    initializeJSTree();
                }
            }
            
            // Function để xử lý form submission
            function initializeFormSubmission() {
                $('#loadModal form').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    
                    const form = $(this);
                    const formData = new FormData(form[0]);
                    const url = form.attr('action');
                    const method = 'POST';
                    if (!formData.has('_token')) {
                        const csrfToken = $('meta[name="csrf-token"]').attr('content');
                        if (csrfToken) {
                            formData.append('_token', csrfToken);
                        }
                    }
                    
                    // Clear previous errors
                    form.find('.is-invalid').removeClass('is-invalid');
                    form.find('.invalid-feedback').remove();
                    
                    // Show loading
                    const submitBtn = form.find('button[type="submit"]');
                    const originalText = submitBtn.text();
                    submitBtn.prop('disabled', true).text('Đang xử lý...');                 
                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                                                 success: function(response) {
                             if (typeof response === 'string') {
                                 $('#loadModal').modal('hide');
                                 showAlert('success', 'Thao tác thành công!');
                                 // Cập nhật dữ liệu thay vì reload
                                 updateTableData();
                                 return;
                             }
                             
                             if (response && response.success) {
                                 // Show success message
                                 $('#loadModal').modal('hide');
                                 showAlert('success', response.message);
                                 
                                 // Cập nhật dữ liệu thay vì reload
                                 updateTableData();
                             } else {
                                 showAlert('error', 'Response không thành công');
                             }
                         },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                // Validation errors
                                const errors = xhr.responseJSON.errors;
                                displayValidationErrors(form, errors);
                            } else {
                                // Other errors
                                console.error('Error details:', xhr.responseText);
                                showAlert('error', 'Có lỗi xảy ra. Vui lòng thử lại. Status: ' + xhr.status);
                            }
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).text(originalText);
                        }
                    });
                });
            }
            
            // Function để hiển thị validation errors
            function displayValidationErrors(form, errors) {
                $.each(errors, function(field, messages) {
                    const input = form.find(`[name="${field}"]`);
                    if (input.length) {
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    }
                });
            }
            
                         // Function để hiển thị alert
             function showAlert(type, message) {
                 const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                 const alertHtml = `
                     <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                         ${message}
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                 `;
                 
                 // Insert alert at the top of the page
                 $('.page-content .container-fluid').prepend(alertHtml);
                 
                 // Auto remove after 5 seconds
                 setTimeout(function() {
                     $('.alert').fadeOut();
                 }, 5000);
             }
             
             // Function để cập nhật dữ liệu bảng thay vì reload trang
             function updateTableData() {
                 // Hiển thị loading với animation
                 $('#nestable').fadeOut(200, function() {
                     $(this).html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Đang cập nhật...</div>').fadeIn(200);
                 });
                 
                 // Load lại dữ liệu bảng
                 $.get(window.location.href)
                     .done(function(data) {
                         // Tìm và cập nhật phần datatable
                         const newData = $(data).find('#nestable').html();
                         if (newData) {
                             $('#nestable').fadeOut(200, function() {
                                 $(this).html(newData).fadeIn(200);
                                 // Re-initialize các components nếu cần
                                 initializeTableComponents();
                             });
                         }
                     })
                     .fail(function() {
                         $('#nestable').fadeOut(200, function() {
                             $(this).html('<div class="alert alert-danger">Lỗi khi cập nhật dữ liệu</div>').fadeIn(200);
                         });
                     });
             }
             
             // Function để khởi tạo lại các components của bảng
             function initializeTableComponents() {
                 // Re-bind các event handlers cho các nút edit/delete
                 // Các components khác nếu cần
             }
            
            // Event handler khi modal được hiển thị
            $(document).on('shown.bs.modal', '#loadModal', function() {
                // Đảm bảo JSTree được khởi tạo nếu chưa có
                if ($('#kt_tree_3').length > 0 && !$('#kt_tree_3').data('jstree')) {
                    setTimeout(function() {
                        initializeJSTree();
                    }, 200);
                }
            });
            

            
            // Function để khởi tạo JSTree
            function initializeJSTree() {
                // Destroy JSTree cũ nếu có
                if ($('#kt_tree_3').data('jstree')) {
                    $('#kt_tree_3').jstree('destroy');
                }
                
                // Lấy data từ input hidden
                var jsondata = [];
                var permissionsJson = $('#permission_ids').data('permissions-json');
                if (permissionsJson) {
                    try {
                        if (typeof permissionsJson === 'string') {
                            jsondata = JSON.parse(permissionsJson);
                        } else {
                            jsondata = permissionsJson;
                        }
                    } catch (e) {
                        console.error('Error parsing permissions JSON:', e);
                        jsondata = [];
                    }
                }
                
                // Khởi tạo JSTree
                $('#kt_tree_3').jstree({
                    "plugins": ["wholerow", "checkbox", "types", "search"],
                    "core": {
                        "dblclick_toggle": false,
                        "themes": {
                            "responsive": false,
                            "icons": false,
                            "dots": true,
                        },
                        "data": jsondata
                    },
                    "checkbox": {
                        "three_state": true,
                        "cascade": "up+down"
                    },
                    "types": {
                        "default": {
                            "icon": "fa fa-folder text-warning"
                        },
                        "file": {
                            "icon": "fa fa-file text-warning"
                        }
                    },
                }).bind("loaded.jstree", function(e, data) {
                    // Set selected nodes sau khi load
                    var perSelected = $('#permission_ids').val();
                    var arrPer = perSelected ? perSelected.split(",") : [];
                    $.each(arrPer, function(index, value) {
                        $('#kt_tree_3').jstree("select_node", value, true);
                    });
                })
                .on('changed.jstree', function(e, data) {
                    var i, j, r = [];
                    for (i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                    }
                    $('#permission_ids').val(r.join(','));
                })
                .on('uncheck_node.jstree', function(e, data) {
                    // Khi bỏ chọn node cha, tự động bỏ chọn tất cả node con
                    var node = data.instance.get_node(data.node);
                    var children = data.instance.get_children_dom(node);
                    children.each(function() {
                        data.instance.uncheck_node($(this));
                    });
                });
            }
        });
    </script>
@endsection
