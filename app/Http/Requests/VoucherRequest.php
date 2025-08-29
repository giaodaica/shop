<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        $id = $this->route('id'); // lấy id trên route
        $voucher = $id ? \App\Models\Vouchers::find($id) : null;

        $rules = [
            'code' => [
                'required',
                'alpha_num',
                'min:10',
                'max:50',
                Rule::unique('vouchers', 'code')->ignore($id),
            ],
            'type_discount' => ['required', 'in:percent,value'],
            'value' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->type_discount === 'percent' && (!is_numeric($value) || $value < 0 || $value > 100)) {
                        $fail('Giảm giá theo % chỉ chấp nhận giá trị từ 0 đến 100');
                    }
                    if ($this->type_discount === 'value' && (!is_numeric($value) || $value < 10000)) {
                        $fail('Không thể nhỏ hơn 10000');
                    }
                }
            ],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'used' => 'prohibited',
            'max_used' => 'required|numeric|min:0|max:1000',
            'min_order_value' => 'required|numeric|min:100000|max:50000000',
            'status' => 'prohibited',
            'category_id' => 'required|exists:categories_vouchers,id',
            'max_discount' => 'nullable|numeric|min:10000|max:50000000',
            'block' => 'nullable|string|in:1,2,3',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Nếu đang update và voucher là active
        if ($voucher && $voucher->status === 'active') {
            unset($rules['start_date']); // bỏ rule start_date
            // chỉnh lại rule end_date so với hôm nay thay vì so với start_date
            $rules['end_date'] = 'required|date|after:today';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.alpha_num' => 'Mã giảm giá chỉ được chứa chữ cái (a-z) hoặc số (0-9).',
            'code.min' => 'Mã giảm giá phải có ít nhất :min ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá :max ký tự.',
            'code.unique' => 'Mã giảm giá không được trùng.',

            'type_discount.required' => 'Loại giảm giá là bắt buộc.',
            'type_discount.in' => 'Loại giảm giá không hợp lệ.',

            'value.required' => 'Giá trị giảm giá là bắt buộc.',

            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng ngày.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng ngày.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'start_date.after_or_equal' => "Không thể là quá khứ",

            'used.prohibited' => 'Trường này không được phép gửi.',

            'max_used.required' => 'Giới hạn sử dụng là bắt buộc.',
            'max_used.numeric' => 'Giới hạn sử dụng phải là số.',
            'max_used.min' => 'Giới hạn sử dụng không được âm.',

            'min_order_value.required' => 'Giá trị đơn hàng tối thiểu là bắt buộc.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu phải từ 100000 trở lên.',

            'status.prohibited' => 'Trạng thái không hợp lệ.',

            'category_id.required' => 'Danh mục áp dụng là bắt buộc.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            'max_discount.numeric' => 'Giá trị giảm giá tối đa phải là số.',
            'max_discount.min' => 'Giá trị giảm giá tối đa phải từ 10000 trở lên.',
            'min_order_value.max' => 'Quá giới hạn cho phép là 50.000.000.',
            'max_used.max' => 'Quá giới hạn cho phép là 1000.',
            'max_discount.max' => 'Quá giới hạn cho phép là 50.000.000.',
            'block.string' => 'Nơi hiển thị phải là chuỗi.',
            'block.in' => 'Nơi hiển thị không hợp lệ.',
            'image.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
            'image.mimes' => 'Ảnh đại diện chỉ chấp nhận định dạng jpg, jpeg, png.',
            'image.max' => 'Ảnh đại diện không được vượt quá 2MB.',


        ];
    }
}
