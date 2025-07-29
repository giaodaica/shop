<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $province_code
 * @property string|null $ward_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Provinces|null $province
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Wards|null $ward
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddressBook whereWardCode($value)
 */
	class AddressBook extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property string|null $keywords
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BotQA whereUpdatedAt($value)
 */
	class BotQA extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_variants_id
 * @property int|null $flash_sale_items_id
 * @property int $quantity
 * @property string $price_at_time
 * @property string $promotion_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products|null $product
 * @property-read \App\Models\Product_variants $productVariant
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CartFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereFlashSaleItemsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePriceAtTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereProductVariantsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePromotionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart_item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart_item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart_item query()
 */
	class Cart_item extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Products> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CategoriesFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categories withoutTrashed()
 */
	class Categories extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vouchers> $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoriesVouchers whereUpdatedAt($value)
 */
	class CategoriesVouchers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $color_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $color_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_variants> $productVariants
 * @property-read int|null $product_variants_count
 * @method static \Database\Factories\ColorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color whereColorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color whereColorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Color whereUpdatedAt($value)
 */
	class Color extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $discount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlashSaleItems> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSale whereUpdatedAt($value)
 */
	class FlashSale extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_variant_id
 * @property int $flash_sale_id
 * @property string $name
 * @property string $variant_image_url
 * @property int $max_quantity
 * @property int $sold_quantity
 * @property string $price_at_flash_sale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $product_id
 * @property int|null $color_id
 * @property int|null $size_id
 * @property string $import_price
 * @property string $listed_price
 * @property string $sale_price
 * @property-read \App\Models\Color|null $color
 * @property-read \App\Models\FlashSale $flashSale
 * @property-read \App\Models\Products|null $product
 * @property-read \App\Models\Product_variants $productVariant
 * @property-read \App\Models\Size|null $size
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereFlashSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereImportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereListedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereMaxQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems wherePriceAtFlashSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereProductVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereSoldQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlashSaleItems whereVariantImageUrl($value)
 */
	class FlashSaleItems extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Product_variants|null $productVariant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImageProductVariants newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImageProductVariants newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImageProductVariants query()
 */
	class ImageProductVariants extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLock> $userLocks
 * @property-read int|null $user_locks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LockReason whereUpdatedAt($value)
 */
	class LockReason extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $voucher_id
 * @property int|null $user_id
 * @property int|null $address_books_id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $total_amount
 * @property string $final_amount
 * @property string $discount_amount
 * @property string $status
 * @property string $code_order
 * @property string|null $pay_method
 * @property string $status_pay
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $shipping_method
 * @property int $shipping_fee
 * @property string|null $image_ship
 * @property string|null $image_user
 * @property string|null $user_comment
 * @property int $user_confirm
 * @property string|null $province_code
 * @property string|null $ward_code
 * @property-read \App\Models\AddressBook|null $addressBook
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderHistories> $orderHistories
 * @property-read int|null $order_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Provinces|null $province
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Vouchers|null $voucher
 * @property-read \App\Models\Wards|null $ward
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddressBooksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCodeOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereFinalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereImageShip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereImageUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePayMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatusPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserConfirm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereWardCode($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $from_status
 * @property string $to_status
 * @property string $note
 * @property string|null $content
 * @property string $users
 * @property string $time_action
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereFromStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereTimeAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereToStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderHistories whereUsers($value)
 */
	class OrderHistories extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_variant_id
 * @property int|null $flash_sale_items_id
 * @property int $product_id
 * @property string $product_name
 * @property string $product_image_url
 * @property string $import_price
 * @property string $listed_price
 * @property string $sale_price
 * @property int $quantity
 * @property string $promotion_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Products $product
 * @property-read \App\Models\Product_variants $productVariant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereFlashSaleItemsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereImportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereListedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductVariantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePromotionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variant_attribute_values newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variant_attribute_values newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variant_attribute_values query()
 */
	class Product_variant_attribute_values extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $color_id
 * @property int $size_id
 * @property string $name
 * @property string $variant_image_url
 * @property string $import_price
 * @property string $listed_price
 * @property string $sale_price
 * @property int $stock
 * @property int $is_show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $use_flash_sale
 * @property int $sold_quantity
 * @property-read \App\Models\Color $color
 * @property-read \App\Models\Products $product
 * @property-read \App\Models\Size $size
 * @method static \Database\Factories\Product_variantsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereImportPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereListedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereSoldQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereUseFlashSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants whereVariantImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product_variants withoutTrashed()
 */
	class Product_variants extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $views_page
 * @property-read \App\Models\Categories $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_variants> $variants
 * @property-read int|null $variants_count
 * @method static \Database\Factories\ProductsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products whereViewsPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Products withoutTrashed()
 */
	class Products extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $province_code
 * @property string $name
 * @property string $short_name
 * @property string $code
 * @property string $place_type
 * @property string $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AddressBook> $addressBooks
 * @property-read int|null $address_books_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wards> $wards
 * @property-read int|null $wards_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces wherePlaceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinces whereUpdatedAt($value)
 */
	class Provinces extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $money
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $refund_id
 * @property string|null $action
 * @property string|null $notes
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundLogs whereUserId($value)
 */
	class RefundLogs extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $order_id
 * @property string|null $bank
 * @property string|null $bank_account_name
 * @property string $amount
 * @property string $status
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stk
 * @property string|null $images
 * @property string|null $QR_images
 * @property string|null $admin_notes
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereBankAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereQRImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereStk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundMoney whereUserId($value)
 */
	class RefundMoney extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property string $content
 * @property array<array-key, mixed>|null $images
 * @property string|null $admin_reply
 * @property int|null $rating
 * @property int $is_show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReviewReply> $replies
 * @property-read int|null $replies_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereAdminReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUserId($value)
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $review_id
 * @property int $admin_id
 * @property string $reply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Review $review
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewReply whereUpdatedAt($value)
 */
	class ReviewReply extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $size_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_variants> $productVariants
 * @property-read int|null $product_variants_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size whereSizeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Size whereUpdatedAt($value)
 */
	class Size extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $default_address
 * @property string|null $default_phone
 * @property string $role
 * @property float $total_spent
 * @property int $point
 * @property string $rank
 * @property string $status
 * @property int|null $locked_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $google_id
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $province_code
 * @property string|null $ward_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AddressBook> $addressBooks
 * @property-read int|null $address_books_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLock> $lockHistory
 * @property-read int|null $lock_history_count
 * @property-read User|null $lockedByUser
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vouchers> $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDefaultAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDefaultPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLockedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTotalSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWardCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $lock_reason_id
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $lockedByUser
 * @property-read \App\Models\LockReason $reason
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereLockReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserLock whereUserId($value)
 */
	class UserLock extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute query()
 */
	class Variant_attribute extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute_values newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute_values newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant_attribute_values query()
 */
	class Variant_attribute_values extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $voucher_id
 * @property int $order_id
 * @property int $is_used
 * @property string $used_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoucherHistories whereVoucherId($value)
 */
	class VoucherHistories extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $code
 * @property string|null $url_image
 * @property string|null $block_show
 * @property string $type_discount
 * @property int $value
 * @property string|null $block
 * @property string|null $image
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int $used
 * @property int $received
 * @property int|null $max_used
 * @property int|null $min_order_value
 * @property string $status
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $max_discount
 * @property-read \App\Models\CategoriesVouchers $cate_vouchers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereBlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereBlockShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereMaxUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereMinOrderValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereTypeDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereUrlImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vouchers withoutTrashed()
 */
	class Vouchers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $voucher_id
 * @property int|null $user_id
 * @property int|null $order_id
 * @property int|null $actor
 * @property string $content
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereActor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersLog whereVoucherId($value)
 */
	class VouchersLog extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $voucher_id
 * @property string $is_used
 * @property string|null $issued_date
 * @property string $status
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereIssuedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VouchersUsers whereVoucherId($value)
 */
	class VouchersUsers extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $ward_code
 * @property string $name
 * @property string $province_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AddressBook> $addressBooks
 * @property-read int|null $address_books_count
 * @property-read \App\Models\Provinces $province
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wards whereWardCode($value)
 */
	class Wards extends \Eloquent {}
}

