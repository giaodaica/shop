<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\LockReason;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Tài khoản')->only(['index']);
        $this->middleware('permission:Tạo tài khoản')->only(['create', 'store']);
        $this->middleware('permission:Sửa tài khoản')->only(['edit', 'update']);
        $this->middleware('permission:Xóa tài khoản')->only(['destroy']);
        $this->middleware('permission:Khóa tài khoản')->only(['lock']);
        $this->middleware('permission:Mở khóa tài khoản')->only(['unlock']);
        $this->middleware('permission:Xóa hàng loạt tài khoản')->only(['bulkDelete']);
    }

    // Hiển thị danh sách người dùng (có thể lọc theo trạng thái)
    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // active | trashed | all
        $lockReasons = LockReason::all();
        $roles = Role::all();
        $query = User::query();

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
        }

        $users = $query->latest()->paginate(10);
        return view('dashboard.pages.users.index', compact('users', 'status', 'lockReasons', 'roles'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        $roles = Role::all();
        return view('dashboard.pages.users.create', compact('roles'));
    }

    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
            'default_address' => 'required|string|max:255',
            'default_phone'   => ['required', 'regex:/^0\d{9}$/'],
            'rank'            => 'nullable|in:newbie,silver,gold,diamond',
            'point'           => 'nullable|integer|min:0',
            'total_spent'     => 'nullable|numeric|min:0',
            'role_id'         => 'required|exists:roles,id',
        ], [
            'name.required'           => 'Họ tên không được để trống.',
            'name.max'                => 'Họ tên không được vượt quá 255 ký tự.',
            'email.unique'            => 'Email đã tồn tại.',
            'email.required'          => 'Email không được để trống.',
            'email.email'             => 'Email không đúng định dạng.',
            'password.required'       => 'Vui lòng nhập mật khẩu.',
            'password.min'            => 'Mật khẩu ít nhất 8 ký tự.',
            'password.regex'          => 'Mật khẩu phải bao gồm 1 chữ cái in hoa, chữ cái in thường và số',
            'password.confirmed'      => 'Xác nhận mật khẩu không khớp.',
            'default_phone.required' => 'Vui lòng nhập số điện thoại.',
            'default_phone.max'       => 'Số điện thoại không được vượt quá 20 ký tự.',
            'default_phone.regex' => 'Số điện thoại không hợp lệ.',
            'default_address.max'     => 'Địa chỉ không được vượt quá 255 ký tự.',
            'default_address.required' => 'Vui lòng nhập địa chỉ.',
            'role_id.required'        => 'Vui lòng chọn vai trò.',
            'role_id.exists'          => 'Vai trò không hợp lệ.',
        ]);

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => 'admin', // Mặc định là admin cho form tạo mới
            'default_address' => $request->default_address,
            'default_phone'   => $request->default_phone,
            'total_spent'     => $request->total_spent ?? 0,
            'point'           => $request->point ?? 0,
            'rank'            => $request->rank ?? 'newbie',
        ]);

        // Gán role cho user
        if ($request->has('role_id')) {
            $role = Role::findOrFail($request->role_id);
            $user->assignRole($role);
        }

        return redirect()->route('users.index')->with('success', 'Tạo tài khoản thành công');
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        if ($user->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'Tài khoản khách hàng không được chỉnh sửa.');
        }

        return view('dashboard.pages.users.edit', compact('user', 'roles'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'Tài khoản khách hàng không được chỉnh sửa.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => $user->id === Auth::id() ? ['nullable', 'string', 'min:8'] : ['nullable'],
            'password_confirmation' => $user->id === Auth::id() ? ['same:password'] : ['nullable'],
            'default_phone' => ['required', 'regex:/^0\d{9}$/'],
            'default_address' => ['required', 'string', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',

            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp.',

            'default_phone.required' => 'Vui lòng nhập số điện thoại.',
            'default_phone.regex' => 'Số điện thoại không hợp lệ.',

            'default_address.required' => 'Vui lòng nhập địa chỉ.',

            'role_id.required' => 'Vui lòng chọn vai trò.',
            'role_id.exists' => 'Vai trò không tồn tại.',
        ]);

        // Cập nhật thông tin user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'default_phone' => $request->default_phone,
            'default_address' => $request->default_address,
            'rank' => $request->rank ?? 'newbie',
            'point' => $request->point ?? 0,
            'total_spent' => $request->total_spent ?? 0,
        ]);

        // Cập nhật mật khẩu - chỉ cho phép sửa mật khẩu của chính mình
        if ($request->filled('password')) {
            if ($user->id === Auth::id()) {
                // Cho phép sửa mật khẩu của chính mình
                $user->update(['password' => bcrypt($request->password)]);
            } else {
                // Không cho phép sửa mật khẩu của người khác
                return redirect()->back()->with('error', 'Bạn không thể thay đổi mật khẩu của người khác.');
            }
        }

        // Cập nhật vai trò - chặn việc thay đổi vai trò của chính mình
        if ($request->has('role_id')) {
            $selectedRole = Role::findOrFail($request->role_id);
            $currentRole = $user->roles->first();
            
            // Nếu đang sửa chính mình và cố gắng thay đổi vai trò
            if ($user->id === Auth::id() && $currentRole && $currentRole->id != $request->role_id) {
                return redirect()->back()->with('error', 'Bạn không thể thay đổi vai trò của chính mình.');
            }
            
            $user->syncRoles([$selectedRole]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'Cập nhật tài khoản thành công');
    }

    // Xoá mềm người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Chặn quyền xóa chính bản thân mình
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

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
            // Chặn quyền xóa hàng loạt có chứa chính bản thân mình
            if (in_array(Auth::id(), $ids)) {
                return response()->json(['success' => false, 'message' => 'Bạn không thể xóa tài khoản của chính mình.'], 400);
            }

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
            'lockedByUser',
            'addressBooks.province',
            'addressBooks.ward',
            'lockHistory.lockedByUser',
            'lockHistory.reason'
        ])->findOrFail($id);
        // dd($user->lockedByUser);
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
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'lock_reason_id' => 'required|exists:lock_reasons,id',
            'note'           => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);

        // Nếu đã bị khóa >= 3 lần thì không cho mở nữa
        $lockCount = $user->lockHistory()->count();
        if ($lockCount >= 3) {
            $user->status = 'inactive';
            $user->save();

            return back()->with('error', 'Người dùng này đã bị khóa 3 lần và đã bị khóa vĩnh viễn.');
        }

        // Không cho tự khóa chính mình
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không thể khóa tài khoản chính mình.');
        }

        // Không khóa admin cuối cùng
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->where('status', 'active')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('warning', 'Không thể khóa quản trị viên cuối cùng.');
            }
        }

        // Ghi log vào bảng user_locks
        UserLock::create([
            'user_id'        => $user->id,
            'locked_by'      => Auth::id(),
            'lock_reason_id' => $request->lock_reason_id,
            'note'           => $request->note,
        ]);

        $user->locked_by = Auth::id();
        $user->status = 'inactive';
        $user->save();

        // Gửi mail
        Mail::to($user->email)->send(new \App\Mail\UserLockedMail(
            $user,
            LockReason::find($request->lock_reason_id)->name,
            $request->note
        ));

        return back()->with('success', 'Đã khóa tài khoản và gửi email.');
    }





    public function unlock(User $user)
    {
        // Đếm số lần user đã bị khóa
        $lockCount = $user->lockHistory()->count();

        if ($lockCount >= 3) {
            return back()->with('error', 'Tài khoản này đã bị khóa 3 lần và không thể mở lại.');
        }

        // Nếu chưa quá 3 lần => cho mở
        $user->update(['status' => 'active']);
        Mail::to($user->email)->send(new \App\Mail\UserUnLockedMail($user));

        return back()->with('success', 'Tài khoản đã được mở lại.');
    }

    public function lockHistory()
    {

        $locks = UserLock::with(['user', 'lockedByUser', 'reason'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.pages.users.lock_history', compact('locks'));
    }
    public function personalLockHistory($id)
    {
        $user = User::findOrFail($id);

        // Lấy lịch sử khóa của user này
        $locks = $user->lockHistory()
            ->with(['lockedByUser', 'reason'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.pages.users.personal_lock_history', compact('user', 'locks'));
    }
}
