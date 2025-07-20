<?php
require_once 'vendor/autoload.php';

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$email = readline("Nhập email user muốn gán quyền admin: ");

// 1. Tạo role admin nếu chưa có (có title)
$role = Role::firstOrCreate(
    ['name' => 'admin'],
    ['title' => 'Admin', 'guard_name' => 'web']
);
echo "✅ Đã có role 'admin'\n";

// 2. Tạo permission access dashboard nếu chưa có
$permission = Permission::firstOrCreate(['name' => 'access dashboard']);
echo "✅ Đã có permission 'access dashboard'\n";

// 3. Gán permission cho role admin nếu chưa có
if (!$role->hasPermissionTo($permission)) {
    $role->givePermissionTo($permission);
    echo "✅ Đã gán permission 'access dashboard' cho role 'admin'\n";
} else {
    echo "Role 'admin' đã có permission 'access dashboard'\n";
}

// 4. Gán role admin cho user
$user = User::where('email', $email)->first();
if (!$user) {
    echo "❌ Không tìm thấy user với email $email\n";
    exit(1);
}
$user->assignRole('admin');
echo "✅ Đã gán role 'admin' cho user $user->email\n";

// 5. Gán permission cho user (nếu muốn)
if (!$user->hasPermissionTo('access dashboard')) {
    $user->givePermissionTo('access dashboard');
    echo "✅ Đã gán permission 'access dashboard' cho user $user->email\n";
}

echo "\n---\nHoàn tất!\nUser $user->email đã có role 'admin' và permission 'access dashboard'.\nBạn có thể vào dashboard bình thường.\n---\n"; 