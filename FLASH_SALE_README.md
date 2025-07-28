# Flash Sale System

## Tổng quan
Hệ thống Flash Sale cho phép tạo và quản lý các chương trình giảm giá theo thời gian với các tính năng:

- Hiển thị countdown timer cho flash sale đang diễn ra
- Hiển thị thời gian sắp diễn ra cho flash sale upcoming
- Quản lý số lượng sản phẩm flash sale
- Tự động cập nhật trạng thái flash sale

## Cấu trúc Database

### Bảng `flash_sales`
- `id`: ID flash sale
- `start_date`: Thời gian bắt đầu
- `end_date`: Thời gian kết thúc
- `status`: Trạng thái (upcoming, active, ended, canceled)
- `discount`: Phần trăm giảm giá

### Bảng `flash_sale_items`
- `id`: ID item
- `product_variant_id`: ID biến thể sản phẩm
- `flash_sale_id`: ID flash sale
- `name`: Tên sản phẩm
- `variant_image_url`: URL ảnh sản phẩm
- `max_quantity`: Số lượng tối đa
- `sold_quantity`: Số lượng đã bán
- `price_at_flash_sale`: Giá flash sale
- `product_id`, `color_id`, `size_id`: Thông tin sản phẩm

## Cách sử dụng

### 1. Tạo dữ liệu test
```bash
php artisan db:seed --class=FlashSaleSeeder
```

### 2. Chạy command cập nhật trạng thái
```bash
php artisan flashsale:update-status
```

### 3. Truy cập trang flash sale
```
/flash-sale
```

## Tính năng chính

### 1. Hiển thị Flash Sale Active
- Countdown timer hiển thị thời gian còn lại
- Danh sách sản phẩm flash sale với giá giảm
- Hiển thị số lượng còn lại

### 2. Hiển thị Flash Sale Upcoming
- Hiển thị thời gian sắp diễn ra
- Danh sách flash sale sắp tới

### 3. Thêm vào giỏ hàng
- Nút "Mua ngay" thêm sản phẩm flash sale vào giỏ hàng
- Kiểm tra số lượng còn lại
- Kiểm tra thời gian flash sale

## API Endpoints

### Lấy flash sale active
```
GET /api/flash-sale/active
```

### Lấy flash sale upcoming
```
GET /api/flash-sale/upcoming
```

## Cron Job

Để tự động cập nhật trạng thái flash sale, thêm vào `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('flashsale:update-status')->everyMinute();
}
```

## Lưu ý

1. Flash sale chỉ hiển thị khi có dữ liệu active hoặc upcoming
2. Countdown timer chỉ hoạt động khi có flash sale active
3. Sản phẩm flash sale có giới hạn số lượng
4. Hệ thống tự động kiểm tra thời gian và trạng thái 