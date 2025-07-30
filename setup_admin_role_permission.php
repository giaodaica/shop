<?php
require_once 'vendor/autoload.php';

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = readline("Nhập email user muốn gán quyền admin: ");


// 2. Tìm role admin
$role = Role::where('name', 'Quản trị viên')->first();
if (!$role) {
    echo "❌ Không tìm thấy role 'admin'. Vui lòng chạy RoleSeeder trước.\n";
    exit(1);
}
echo "✅ Đã tìm thấy role 'Quản trị viên'\n";

// 3. Tìm user
$user = User::where('email', $email)->first();
if (!$user) {
    echo "❌ Không tìm thấy user với email $email\n";
    exit(1);
}
echo "✅ Đã tìm thấy user: $user->name ($user->email)\n";

// 4. Gán role admin cho user
if (!$user->hasRole('Quản trị viên')) {
    $user->assignRole('Quản trị viên');
    echo "✅ Đã gán role 'Quản trị viên' cho user $user->email\n";
} else {
    echo "ℹ️ User $user->email đã có role 'Quản trị viên'\n";
}

// 5. Cập nhật cột 'role' trong bảng users
$user->role = 'admin'; 
$user->save();
echo "✅ Đã cập nhật cột role trong database\n";

// 6. Hiển thị thông tin permissions của user
echo "\n📋 Thông tin permissions của user:\n";
$permissions = $user->getAllPermissions();
echo "Số lượng permissions: " . $permissions->count() . "\n";

// Hiển thị danh sách permissions theo nhóm
$groupedPermissions = $permissions->groupBy('parent_id');
foreach ($groupedPermissions as $parentId => $perms) {
    if ($parentId === null) {
        echo "\n🔹 Permissions chính:\n";
    } else {
        $parentPermission = Permission::find($parentId);
        echo "\n🔹 " . ($parentPermission ? $parentPermission->name : 'Unknown') . ":\n";
    }
    
    foreach ($perms as $permission) {
        echo "  - " . $permission->name . "\n";
    }
}

echo "\n---\n🎉 Hoàn tất!\n";
echo "User $user->email đã có role 'admin' với đầy đủ permissions.\n";
echo "Bạn có thể vào dashboard và sử dụng tất cả chức năng.\n";
echo "---\n"; 