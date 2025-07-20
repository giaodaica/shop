<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Models\Provinces;
use App\Models\Wards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressBookController extends Controller
{
    public function index()
    {
        $addresses = AddressBook::where('user_id', Auth::id())->with(['province', 'ward'])->get();
        $provinces = Provinces::orderBy('name')->get();
        return view('pages.shop.addresses', compact('addresses', 'provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_code' => 'required|exists:provinces,province_code',
            'ward_code' => 'required|exists:wards,ward_code',
            'address' => 'required|string|max:500'
        ], [
            'name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'province_code.required' => 'Vui lòng chọn tỉnh/thành phố',
            'province_code.exists' => 'Tỉnh/thành phố không hợp lệ',
            'ward_code.required' => 'Vui lòng chọn xã/phường',
            'ward_code.exists' => 'Xã/phường không hợp lệ',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết'
        ]);

        AddressBook::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'province_code' => $request->province_code,
            'ward_code' => $request->ward_code,
            'address' => $request->address
        ]);

        return redirect()->back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_code' => 'required|exists:provinces,province_code',
            'ward_code' => 'required|exists:wards,ward_code',
            'address' => 'required|string|max:500'
        ], [
            'name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'province_code.required' => 'Vui lòng chọn tỉnh/thành phố',
            'province_code.exists' => 'Tỉnh/thành phố không hợp lệ',
            'ward_code.required' => 'Vui lòng chọn xã/phường',
            'ward_code.exists' => 'Xã/phường không hợp lệ',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết'
        ]);

        $address = AddressBook::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $address->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'province_code' => $request->province_code,
            'ward_code' => $request->ward_code,
            'address' => $request->address
        ]);

        return redirect()->back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    public function destroy($id)
    {
        $address = AddressBook::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $address->delete();

        return redirect()->back()->with('success', 'Xóa địa chỉ thành công!');
    }

    public function getWards(Request $request)
    {
        $wards = Wards::where('province_code', $request->province_code)
            ->orderBy('name')
            ->get(['ward_code', 'name']);
        
        return response()->json($wards);
    }
}
