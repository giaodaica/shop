<?php

namespace App\Http\Controllers;

use App\Mail\UserUnLockedMail;
use App\Models\User;
use App\Models\UserLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\LockReason;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng (có thể lọc theo trạng thái)
    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // active | trashed | all
        $lockReasons = LockReason::all();
        $query = User::query();

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
        }

        $users = $query->latest()->paginate(10);
        return view('dashboard.pages.users.index', compact('users', 'status', 'lockReasons'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('dashboard.pages.users.create');
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,guest',
            'rank'     => 'nullable|in:newbie,silver,gold,diamond',
            'point'    => 'nullable|integer|min:0',
            'total_spent' => 'nullable|numeric|min:0',
        ]);

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'default_address' => $request->default_address,
            'default_phone'   => $request->default_phone,
            'total_spent'     => $request->total_spent ?? 0,
            'point'           => $request->point ?? 0,
            'rank'            => $request->rank ?? 'newbie',
        ]);

        return redirect()->route('users.index')->with('success', 'Tạo tài khoản thành công');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'Tài khoản khách hàng không được chỉnh sửa.');
        }

        return view('dashboard.pages.users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        // dd($request);
        $user = User::withTrashed()->findOrFail($id);

        // Nếu không phải admin thì không được cập nhật
        if ($user->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'Tài khoản khách hàng không được chỉnh sửa.');
        }

        // Validation cơ bản
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,guest',
            'rank'  => 'nullable|in:newbie,silver,gold,diamond',
            'point' => 'nullable|integer|min:0',
            'total_spent' => 'nullable|numeric|min:0',
            'default_address' => 'nullable|string|max:255',
            'default_phone'   => 'nullable|string|max:20',
        ]);

        // Gán các giá trị có trong request, còn lại sẽ đặt thành null nếu không có
        $user->update([
            'name'            => $request->name,
            'email'           => $request->email,
            'role'            => $request->role,
            'default_address' => $request->filled('default_address') ? $request->default_address : null,
            'default_phone'   => $request->filled('default_phone') ? $request->default_phone : null,
            'total_spent' => $request->filled('total_spent') ? $request->total_spent : 0,
            'point' => $request->filled('point') ? $request->point : 0,
            'rank' => $request->filled('rank') ? $request->rank : 'newbie',
        ]);

        return redirect()->route('users.index')->with('success', 'Cập nhật tài khoản thành công');
    }


    // Xoá mềm người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Xoá người dùng thành công (xoá mềm)');
    }

    // Khôi phục người dùng đã xoá
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.index', ['status' => 'trashed'])->with('success', 'Khôi phục người dùng thành công');
    }

    // Xoá vĩnh viễn người dùng
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.index', ['status' => 'trashed'])->with('success', 'Xoá vĩnh viễn người dùng thành công');
    }

    // Xoá mềm hàng loạt
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids)) {
            User::whereIn('id', $ids)->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
    public function show($id)
    {
        $user = User::with([
            'orders.orderItems',
            'vouchers.cate_vouchers',
            'lockedByUser'
        ])->findOrFail($id);

        $activeTab = request('tab', 'overview');

        // Thống kê trạng thái đơn hàng
        $orderStats = [
            'total' => $user->orders->count(),
            'pending' => $user->orders->where('status', 'pending')->count(),
            'confirmed' => $user->orders->where('status', 'confirmed')->count(),
            'shipping' => $user->orders->where('status', 'shipping')->count(),
            'success' => $user->orders->where('status', 'success')->count(),
            'cancelled' => $user->orders->where('status', 'cancelled')->count(),
            'failed' => $user->orders->where('status', 'failed')->count(),
        ];

        // Chuẩn hóa dữ liệu voucher để truyền ra view
        $vouchers = $user->vouchers->map(function ($voucher) {
            $now = now();
            $status = 'Chưa dùng';

            if ($voucher->pivot->is_used === 'used') {
                $status = 'Đã dùng';
            } elseif ($voucher->end_date < $now) {
                $status = 'Hết hạn';
            }

            return [
                'code' => $voucher->code,
                'name' => $voucher->cate_vouchers->name ?? '---',
                'type' => $voucher->type_discount,
                'value' => $voucher->value,
                'end_date' => $voucher->end_date,
                'status' => $status,
            ];
        });
        $recentOrders = $user->orders->sortByDesc('created_at')->take(5);
        $totalFinalAmount = $user->orders()
            ->where('status', 'success')
            ->sum('final_amount');

        return view('dashboard.pages.users.show', compact('user', 'activeTab', 'orderStats', 'vouchers', 'recentOrders', "totalFinalAmount"));
    }

    public function lock(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        // Không cho khóa nếu chỉ còn 1 admin đang active
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->where('status', 'active')->count();

            if ($adminCount <= 1) {
                return redirect()->back()->with('warning', 'Không thể khóa người dùng này vì chỉ còn 1 quản trị viên.');
            }
        }

        $reason = LockReason::findOrFail($request->lock_reason_id);
        $note = $request->note;

        // Lưu vào bảng user_locks
        UserLock::create([
            'user_id' => $user->id,
            'lock_reason_id' => $reason->id,
            'note' => $note,
        ]);

        // Cập nhật trạng thái user
        $user->status = 'inactive';
        $user->locked_by = auth()->id();
        $user->save();

        // Gửi mail
        Mail::to($user->email)->send(new \App\Mail\UserLockedMail($user, $reason->name, $note));

        return redirect()->back()->with('success', 'Đã khóa tài khoản và gửi email.');
    }



    public function unlock(User $user)
    {
        $user->update(['status' => 'active']);
        Mail::to($user->email)->send(new UserUnLockedMail($user));

        return back()->with('success', 'Tài khoản đã được mở lại.');
    }
}
