<?php

namespace App\Http\Controllers\Spatie;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $user;
    protected $page_breadcrumbs;

    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Vai trò')->only(['index']);
        $this->middleware('permission:Tạo vai trò')->only(['create', 'store']);
        $this->middleware('permission:Sửa vai trò')->only(['edit', 'update']);
        $this->middleware('permission:Xóa vai trò')->only(['destroy']);
        $this->middleware('permission:Sắp xếp vai trò')->only(['order']);
    }
    public function index(Request $request)
    {
        $data = Role::orderBy('order', 'asc')->get();
        $datatable = $this->getHTMLCategory($data);
        $page_breadcrumbs = [
            [
                'page' => route('dashboard.roles.index'),
                'title' => 'Quản lý vai trò',
            ],
        ];
        return view('dashboard.pages.role.index')
            ->with('datatable', $datatable)
            ->with('page_breadcrumbs', $page_breadcrumbs);
    }


    /**
     * Show the form for creating a new newscategory
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $dataCategory = Role::orderBy('order', 'asc')->get();
        $permissions = Permission::orderBy('order', 'asc')->get();
        $array = array();
        foreach ($permissions as $permission) {
            if ($permission->parent_id == 0 || $permission->parent_id . "" == "") {
                $permission->parent_id = "#";
            }
            $array[] = [
                "id" => $permission->id . "",
                "parent" => $permission->parent_id . "",
                "text" => htmlentities($permission->name) . "",
                "state" => [
                    'opened' => true
                ],
            ];
        }
        $permissionsJson = json_encode($array);

        return view('dashboard.pages.role.create_edit', compact('dataCategory', 'permissionsJson', 'permissions'));
    }

    /**
     * Store a newly created newscategory in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|unique:roles',
                'name' => 'required|unique:roles',
            ], [
                'title.required' => __('Vui lòng nhật tiêu đề'),
                'title.unique' => __('Tiêu đề nhâp từ khóa name'),
                'name.unique' => __('Name đã tồn tại'),
                'name.required' => __('Vui lòng nhập name'),
            ]);
            
            // Ngăn việc tạo vai trò mới với tên 'Quản trị viên'
            if ($request->name === 'Quản trị viên') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể tạo vai trò với tên "Quản trị viên"'
                    ], 403);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['name' => 'Không thể tạo vai trò với tên "Quản trị viên"']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Có lỗi validation xảy ra'
                ], 422);
            }
            throw $e;
        }
        $input = $request->all();
        $data = Role::create($input);
        // Nếu form gửi lên là permissions[] (theo chuẩn resource), cần sync lại
        if ($request->has('permissions')) {
            $permissionIds = Permission::whereIn('name', $request->permissions)->pluck('id')->toArray();
            $data->permissions()->sync($permissionIds);
        } else {
            $permissionIds = isset($request->permission_ids) ? explode(",", $request->permission_ids) : [];
            
            // Tự động thêm permissions con khi có permission cha
            $allPermissionIds = $permissionIds;
            foreach ($permissionIds as $permissionId) {
                $childPermissions = \App\Helpers\PermissionHelper::getAllChildPermissions($permissionId);
                $childPermissionIds = $childPermissions->pluck('id')->toArray();
                $allPermissionIds = array_merge($allPermissionIds, $childPermissionIds);
            }
            
            $data->permissions()->sync(array_unique($allPermissionIds));
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Thêm mới thành công !'),
                'redirect' => route('dashboard.roles.index')
            ]);
        }
        
        return redirect()->route('dashboard.roles.index')
            ->with('success', __('Thêm mới thành công !'));
    }

    /**
     * Display the specified newscategory.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        //$data = Role::findOrFail($id);
        //ActivityLog::add($request, 'Show role #'.$data->id);
        //return view('admin.role.show', compact('item'));
    }

    /**
     * Show the form for editing the specified newscategory.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $data = Role::findOrFail($id);
        
        // Ngăn việc chỉnh sửa vai trò admin
        if ($data->name === 'Quản trị viên') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chỉnh sửa vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể chỉnh sửa vai trò Admin');
        }

        $dataCategory = Role::where('id', '!=', $id)->orderBy('order', 'asc')->get();
        $permissions = Permission::orderBy('order', 'asc')->get();
        $permissionsSelected = $data->permissions()->pluck('id')->toArray();
        $array = array();
        foreach ($permissions as $permission) {
            if ($permission->parent_id == 0 || $permission->parent_id . "" == "") {
                $permission->parent_id = "#";
            }
            $array[] = [
                "id" => $permission->id . "",
                "parent" => $permission->parent_id . "",
                "text" => htmlentities($permission->name) . "",
                "state" => [
                    'opened' => true
                ],
            ];
        }
        $permissionsJson = json_encode($array);
        return view('dashboard.pages.role.create_edit', compact('data', 'dataCategory', 'permissionsJson', 'permissionsSelected', 'permissions'));
    }

    /**
     * Update the specified newscategory in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = Role::findOrFail($id);
        
        // Ngăn việc cập nhật vai trò admin
        if ($data->name === 'Quản trị viên') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể cập nhật vai trò Admin');
        }
        
        // Ngăn việc thay đổi tên của vai trò admin thành tên khác
        if ($data->name === 'Quản trị viên' && $request->name !== 'Quản trị viên') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi tên của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi tên của vai trò Admin');
        }
        
        // Ngăn việc thay đổi tiêu đề của vai trò admin
        if ($data->name === 'Quản trị viên' && $request->title !== $data->title) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi tiêu đề của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi tiêu đề của vai trò Admin');
        }
        
        try {
            $this->validate($request, [
                'title' => 'required|unique:roles,title,' . $id,
                'name' => 'required|unique:roles,name,' . $id,
            ], [
                'title.required' => __('Vui lòng nhật tiêu đề'),
                'title.unique' => __('Tiêu đề đã tồn tại'),
                'name.unique' => __('Name đã tồn tại'),
                'name.required' => __('Vui lòng nhập name'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Có lỗi validation xảy ra'
                ], 422);
            }
            throw $e;
        }
        $input = $request->all();
        
        // Ngăn việc thay đổi parent_id của vai trò admin
        if ($data->name === 'Quản trị viên' && isset($input['parent_id']) && $input['parent_id'] != $data->parent_id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi cấu trúc phân cấp của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi cấu trúc phân cấp của vai trò Admin');
        }
        
        // Ngăn việc thay đổi order của vai trò admin
        if ($data->name === 'Quản trị viên' && isset($input['order']) && $input['order'] != $data->order) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi thứ tự của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi thứ tự của vai trò Admin');
        }
        
        // Ngăn việc thay đổi description của vai trò admin
        if ($data->name === 'Quản trị viên' && isset($input['description']) && $input['description'] != $data->description) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi mô tả của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi mô tả của vai trò Admin');
        }
        
        // Ngăn việc thay đổi guard_name của vai trò admin
        if ($data->name === 'Quản trị viên' && isset($input['guard_name']) && $input['guard_name'] != $data->guard_name) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể thay đổi guard_name của vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể thay đổi guard_name của vai trò Admin');
        }
        
        // Ngăn việc thay đổi timestamps của vai trò admin
        if ($data->name === 'Quản trị viên') {
            unset($input['created_at'], $input['updated_at']);
        }
        
        // Ngăn việc thay đổi id của vai trò admin
        if ($data->name === 'Quản trị viên') {
            unset($input['id']);
        }
        
        // Ngăn việc thay đổi các trường khác của vai trò admin
        if ($data->name === 'Quản trị viên') {
            // Chỉ cho phép cập nhật các trường cần thiết
            $allowedFields = ['title', 'name', 'description'];
            $input = array_intersect_key($input, array_flip($allowedFields));
        }
        
        $data->update($input);
        if ($request->has('permissions')) {
            $permissionIds = Permission::whereIn('name', $request->permissions)->pluck('id')->toArray();
            $data->permissions()->sync($permissionIds);
        } else {
            $permissionIds = isset($request->permission_ids) ? explode(",", $request->permission_ids) : [];
            
            // Ngăn việc thay đổi permissions của vai trò admin
            if ($data->name === 'Quản trị viên') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể thay đổi permissions của vai trò Admin'
                    ], 403);
                }
                return redirect()->route('dashboard.roles.index')
                    ->with('error', 'Không thể thay đổi permissions của vai trò Admin');
            }
            
            // Tự động thêm permissions con khi có permission cha
            $allPermissionIds = $permissionIds;
            foreach ($permissionIds as $permissionId) {
                $childPermissions = \App\Helpers\PermissionHelper::getAllChildPermissions($permissionId);
                $childPermissionIds = $childPermissions->pluck('id')->toArray();
                $allPermissionIds = array_merge($allPermissionIds, $childPermissionIds);
            }
            
            $data->permissions()->sync(array_unique($allPermissionIds));
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Cập nhật vai trò thành công [' . $data->title . ']'),
                'redirect' => route('dashboard.roles.index')
            ]);
        }
        
        return redirect()->route('dashboard.roles.index')->with('success', __('Cập nhật vai trò thành công [' . $data->title . ']'));
    }

    /**
     * Remove the specified newscategory from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $input = explode(',', $request->id);
        
        // Kiểm tra xem có vai trò admin nào trong danh sách cần xóa không
        $adminRoles = Role::whereIn('id', $input)->where('name', 'Quản trị viên')->get();
        if ($adminRoles->count() > 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa vai trò Admin'
                ], 403);
            }
            return redirect()->route('dashboard.roles.index')
                ->with('error', 'Không thể xóa vai trò Admin');
        }
        
        Role::destroy($input);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa thành công!'
            ]);
        }
        
        return redirect()->route('dashboard.roles.index')->with('success', __('Xóa thành công !'));
    }


    // AJAX Reordering function
    public function order(Request $request)
    {
        $source = e($request->get('source'));
        $destination = $request->get('destination');
        $item = Role::find($source);
        
        // Ngăn việc thay đổi thứ tự của vai trò admin
        if ($item && $item->name === 'Quản trị viên') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi thứ tự của vai trò Admin'
            ], 403);
        }
        
        $item->parent_id = isset($destination) ? $destination : 0;
        $item->save();
        $ordering = json_decode($request->get('order'));
        $rootOrdering = json_decode($request->get('rootOrder'));
        if ($ordering) {
            foreach ($ordering as $order => $item_id) {
                if ($itemToOrder = Role::find($item_id)) {
                    // Ngăn việc thay đổi thứ tự của vai trò admin
                    if ($itemToOrder->name === 'Quản trị viên') {
                        continue;
                    }
                    $itemToOrder->order = $order;
                    $itemToOrder->save();
                }
            }
        } else {
            foreach ($rootOrdering as $order => $item_id) {
                if ($itemToOrder = Role::find($item_id)) {
                    // Ngăn việc thay đổi thứ tự của vai trò admin
                    if ($itemToOrder->name === 'Quản trị viên') {
                        continue;
                    }
                    $itemToOrder->order = $order;
                    $itemToOrder->save();
                }
            }
        }
        return 'ok ';
    }
    // Getter for the HTML menu builder
    function getHTMLCategory($menu)
    {
        return $this->buildMenu($menu);
    }
    function buildMenu($menu, $parent_id = 0)
    {
        $result = null;
        foreach ($menu as $item)
            if ($item->parent_id == $parent_id) {
                $result .= "<li class='dd-item nested-list-item' data-order='{$item->order}' data-id='{$item->id}'>
      <div class='nested-list-content'>
        <span class='dd-handle nested-list-handle' style='cursor:move; margin-right:10px;'><span class='la la-arrows-alt'></span></span>";
                $result .= "<div style=\"margin:0 10px 0 0; flex:1 1 auto; display:flex; align-items:center;\">" . htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') . "</div>";
                $description = "Đang cập nhật...";
                if ($item->description) {
                    $description = $item->description;
                }
                $result .= "<div class='btnControll'>";
                
                // Ẩn nút sửa và xóa cho vai trò admin đầu tiên
                if ($item->name === 'Quản trị viên') {
                    $result .= "<span class='text-muted' style='font-size: 12px;'>Không thể chỉnh sửa</span>";
                } else {
                    $result .= "<a href='#' class='btn btn-sm btn-primary edit_toggle' data-url='" . route("dashboard.roles.edit", $item->id) . "' rel='{$item->id}' >Sửa</a>
            <a href=\"#\" class=\"btn btn-sm btn-danger  delete_toggle \" rel=\"{$item->id}\">
                                Xóa
            </a>";
                }
                
                $result .= "</div>
      </div>" . $this->buildMenu($menu, $item->id) . "</li>";
            }
        return $result ? "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Build dropdown for role category
    public static function buildMenuDropdownList($dataCategory, $selected, $idparent = 0, $stringSpecial = "")
    {
        $result = null;
        foreach ($dataCategory as $item) {
            if ($item->parent_id == $idparent) {
                $checked = "";
                foreach ((array)$selected as $key => $value) {
                    if ($value == $item->id) {
                        $checked = "selected";
                        break;
                    }
                }
                // Sửa tại đây: hiển thị theo name thay vì title
                $result .= "<option value='" . $item->id . "'" . $checked . ">" . e($stringSpecial . ' ' . $item->title) . "</option>";
                $result .= self::buildMenuDropdownList($dataCategory, $selected, $item->id, $stringSpecial . "¦– – ");
            }
        }
        return $result;
    }
    public static function buildMenuDropdownListNotIdParent0($dataCategory, $selected, $idparent = 0, $stringSpecial = "")
    {
        $result = null;
        foreach ($dataCategory as $item) {
            if ($item->parent_id == $idparent) {
                $checked = "";
                foreach ((array)$selected as $key => $value) {
                    if ($value == $item->id) {
                        $checked = "selected";
                        break;
                    }
                }
                $result .= "<option value='" . $item->id . "'" . $checked . ">" . e($stringSpecial . ' ' . $item->title) . "</option>";
                $result .= self::buildMenuDropdownList($dataCategory, $selected, $item->id, $stringSpecial . "¦– – ");
            } else {
                $checked_e = "";
                foreach ((array)$selected as $key => $value) {
                    if ($value == $item->id) {
                        $checked_e = "selected";
                        break;
                    }
                }
                $result .= "<option value='" . $item->id . "'" . $checked_e . ">" . e($stringSpecial . ' ' . $item->title) . "</option>";
            }
        }
        return $result;
    }
}
