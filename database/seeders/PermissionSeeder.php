<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'dashboard' => [
                'name' => 'Quản lý Dashboard',
                'title' => 'Dashboard Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang dashboard', 'title' => 'View Dashboard'],
                ]
            ],
            'order' => [
                'name' => 'Quản lý Đơn hàng',
                'title' => 'Order Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang đơn hàng', 'title' => 'View Orders'],
                    'create' => ['name' => 'Tạo đơn hàng', 'title' => 'Create Order'],
                    'edit' => ['name' => 'Sửa đơn hàng', 'title' => 'Edit Order'],
                    'delete' => ['name' => 'Xóa đơn hàng', 'title' => 'Delete Order'],
                    'change_status' => ['name' => 'Thay đổi trạng thái đơn hàng', 'title' => 'Change Order Status'],
                    'change_address' => ['name' => 'Thay đổi địa chỉ đơn hàng', 'title' => 'Change Order Address'],
                ]
            ],
            'refund' => [
                'name' => 'Quản lý Hoàn tiền',
                'title' => 'Refund Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang hoàn tiền', 'title' => 'View Refunds'],
                    'create' => ['name' => 'Tạo yêu cầu hoàn tiền', 'title' => 'Create Refund'],
                    'edit' => ['name' => 'Sửa yêu cầu hoàn tiền', 'title' => 'Edit Refund'],
                    'delete' => ['name' => 'Xóa yêu cầu hoàn tiền', 'title' => 'Delete Refund'],
                    'approve' => ['name' => 'Phê duyệt hoàn tiền', 'title' => 'Approve Refund'],
                    'reject' => ['name' => 'Từ chối hoàn tiền', 'title' => 'Reject Refund'],
                ]
            ],
            'revenue' => [
                'name' => 'Quản lý Doanh thu',
                'title' => 'Revenue Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang doanh thu', 'title' => 'View Revenue'],
                    'export' => ['name' => 'Xuất báo cáo doanh thu', 'title' => 'Export Revenue Report'],
                    'filter' => ['name' => 'Lọc dữ liệu doanh thu', 'title' => 'Filter Revenue Data'],
                ]
            ],
            'voucher' => [
                'name' => 'Quản lý Voucher',
                'title' => 'Voucher Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang voucher', 'title' => 'View Vouchers'],
                    'create' => ['name' => 'Tạo voucher', 'title' => 'Create Voucher'],
                    'edit' => ['name' => 'Sửa voucher', 'title' => 'Edit Voucher'],
                    'delete' => ['name' => 'Xóa voucher', 'title' => 'Delete Voucher'],
                    'activate' => ['name' => 'Kích hoạt voucher', 'title' => 'Activate Voucher'],
                    'deactivate' => ['name' => 'Vô hiệu hóa voucher', 'title' => 'Deactivate Voucher'],
                    'ads' => ['name' => 'Quản lý quảng cáo voucher', 'title' => 'Manage Voucher Ads'],
                ]
            ],
            'flash_sale' => [
                'name' => 'Quản lý Flash Sale',
                'title' => 'Flash Sale Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang flash sale', 'title' => 'View Flash Sales'],
                    'create' => ['name' => 'Tạo flash sale', 'title' => 'Create Flash Sale'],
                    'edit' => ['name' => 'Sửa flash sale', 'title' => 'Edit Flash Sale'],
                    'delete' => ['name' => 'Xóa flash sale', 'title' => 'Delete Flash Sale'],
                    'activate' => ['name' => 'Kích hoạt flash sale', 'title' => 'Activate Flash Sale'],
                    'deactivate' => ['name' => 'Vô hiệu hóa flash sale', 'title' => 'Deactivate Flash Sale'],
                    'manage_items' => ['name' => 'Quản lý sản phẩm flash sale', 'title' => 'Manage Flash Sale Items'],
                ]
            ],
            'product' => [
                'name' => 'Quản lý Sản phẩm',
                'title' => 'Product Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang sản phẩm', 'title' => 'View Products'],
                    'create' => ['name' => 'Tạo sản phẩm', 'title' => 'Create Product'],
                    'edit' => ['name' => 'Sửa sản phẩm', 'title' => 'Edit Product'],
                    'delete' => ['name' => 'Xóa sản phẩm', 'title' => 'Delete Product'],
                    'restore' => ['name' => 'Khôi phục sản phẩm', 'title' => 'Restore Product'],
                    'upload_image' => ['name' => 'Tải ảnh sản phẩm', 'title' => 'Upload Product Image'],
                    'add_flash_sale' => ['name' => 'Thêm sản phẩm vào flash sale', 'title' => 'Add Product to Flash Sale'],
                    'remove_flash_sale' => ['name' => 'Xóa sản phẩm khỏi flash sale', 'title' => 'Remove Product from Flash Sale'],
                ]
            ],
            'category' => [
                'name' => 'Quản lý Danh mục',
                'title' => 'Category Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang danh mục', 'title' => 'View Categories'],
                    'create' => ['name' => 'Tạo danh mục', 'title' => 'Create Category'],
                    'edit' => ['name' => 'Sửa danh mục', 'title' => 'Edit Category'],
                    'delete' => ['name' => 'Xóa danh mục', 'title' => 'Delete Category'],
                    'restore' => ['name' => 'Khôi phục danh mục', 'title' => 'Restore Category'],
                ]
            ],
            'variant' => [
                'name' => 'Quản lý Biến thể sản phẩm',
                'title' => 'Product Variant Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang biến thể', 'title' => 'View Variants'],
                    'create' => ['name' => 'Tạo biến thể', 'title' => 'Create Variant'],
                    'edit' => ['name' => 'Sửa biến thể', 'title' => 'Edit Variant'],
                    'delete' => ['name' => 'Xóa biến thể', 'title' => 'Delete Variant'],
                    'restore' => ['name' => 'Khôi phục biến thể', 'title' => 'Restore Variant'],
                    'upload_image' => ['name' => 'Tải ảnh biến thể', 'title' => 'Upload Variant Image'],
                ]
            ],
            'color' => [
                'name' => 'Quản lý Màu sắc',
                'title' => 'Color Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang màu sắc', 'title' => 'View Colors'],
                    'create' => ['name' => 'Tạo màu sắc', 'title' => 'Create Color'],
                    'edit' => ['name' => 'Sửa màu sắc', 'title' => 'Edit Color'],
                    'delete' => ['name' => 'Xóa màu sắc', 'title' => 'Delete Color'],
                ]
            ],
            'size' => [
                'name' => 'Quản lý Kích thước',
                'title' => 'Size Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang kích thước', 'title' => 'View Sizes'],
                    'create' => ['name' => 'Tạo kích thước', 'title' => 'Create Size'],
                    'edit' => ['name' => 'Sửa kích thước', 'title' => 'Edit Size'],
                    'delete' => ['name' => 'Xóa kích thước', 'title' => 'Delete Size'],
                ]
            ],
            'user' => [
                'name' => 'Quản lý Tài khoản',
                'title' => 'User Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang tài khoản', 'title' => 'View Users'],
                    'create' => ['name' => 'Tạo tài khoản', 'title' => 'Create User'],
                    'edit' => ['name' => 'Sửa tài khoản', 'title' => 'Edit User'],
                    'delete' => ['name' => 'Xóa tài khoản', 'title' => 'Delete User'],
                    'lock' => ['name' => 'Khóa tài khoản', 'title' => 'Lock User'],
                    'unlock' => ['name' => 'Mở khóa tài khoản', 'title' => 'Unlock User'],
                    'bulk_delete' => ['name' => 'Xóa hàng loạt tài khoản', 'title' => 'Bulk Delete Users'],
                ]
            ],
            'role' => [
                'name' => 'Quản lý Vai trò',
                'title' => 'Role Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang vai trò', 'title' => 'View Roles'],
                    'create' => ['name' => 'Tạo vai trò', 'title' => 'Create Role'],
                    'edit' => ['name' => 'Sửa vai trò', 'title' => 'Edit Role'],
                    'delete' => ['name' => 'Xóa vai trò', 'title' => 'Delete Role'],
                    'order' => ['name' => 'Sắp xếp vai trò', 'title' => 'Order Roles'],
                ]
            ],
            'permission' => [
                'name' => 'Quản lý Quyền hạn',
                'title' => 'Permission Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang quyền hạn', 'title' => 'View Permissions'],
                    'create' => ['name' => 'Tạo quyền hạn', 'title' => 'Create Permission'],
                    'edit' => ['name' => 'Sửa quyền hạn', 'title' => 'Edit Permission'],
                    'delete' => ['name' => 'Xóa quyền hạn', 'title' => 'Delete Permission'],
                    'order' => ['name' => 'Sắp xếp quyền hạn', 'title' => 'Order Permissions'],
                ]
            ],
            'comment' => [
                'name' => 'Quản lý Bình luận',
                'title' => 'Comment Management',
                'permissions' => [
                    'view' => ['name' => 'Xem trang bình luận', 'title' => 'View Comments'],
                    'edit' => ['name' => 'Sửa bình luận', 'title' => 'Edit Comment'],
                    'reply' => ['name' => 'Trả lời bình luận', 'title' => 'Reply to Comment'],
                    'delete_reply' => ['name' => 'Xóa trả lời bình luận', 'title' => 'Delete Comment Reply'],
                ]
            ],
        ];

        foreach ($modules as $moduleKey => $moduleData) {
            // Tạo permission tổng cho module
            $parentPermission = Permission::firstOrCreate([
                'name' => $moduleData['name'],
                'title' => $moduleData['title'],
                'parent_id' => null
            ]);

            // Tạo các permission con
            foreach ($moduleData['permissions'] as $action => $permissionData) {
                Permission::firstOrCreate([
                    'name' => $permissionData['name'],
                    'title' => $permissionData['title'],
                    'parent_id' => $parentPermission->id
                ]);
            }
        }
    }
} 