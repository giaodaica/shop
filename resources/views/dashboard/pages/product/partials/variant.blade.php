<div class="row variant-item gx-2 gy-3 align-items-end border-bottom pb-3 mb-4">

    {{-- Size --}}
    <div class="col-lg-2">
        <label class="form-label">Size</label>
        <select class="form-select @error("variants.$index.size_id") is-invalid @enderror"
            name="variants[{{ $index }}][size_id]" required>
            <option value="">Size</option>
            @foreach ($sizes as $size)
                <option value="{{ $size->id }}"
                    {{ old("variants.$index.size_id", $variant['size_id'] ?? '') == $size->id ? 'selected' : '' }}>
                    {{ $size->size_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Color --}}
    <div class="col-lg-2">
        <label class="form-label">Màu</label>
        <div class="d-flex align-items-center">
            <select class="form-select me-2 color-select @error("variants.$index.color_id") is-invalid @enderror"
                name="variants[{{ $index }}][color_id]" data-color-preview="color-preview-{{ $index }}"
                required>
                <option value="">Color</option>
                @foreach ($colors as $color)
                    <option value="{{ $color->id }}" data-color-code="{{ $color->color_code }}"
                        {{ old("variants.$index.color_id", $variant['color_id'] ?? '') == $color->id ? 'selected' : '' }}>
                        {{ $color->color_name }}
                    </option>
                @endforeach
            </select>
            <div id="color-preview-{{ $index }}" class="border rounded"
                style="width: 24px; height: 32px; background-color:
                @php
$selectedColorId = old("variants.$index.color_id", $variant['color_id'] ?? '');
                    $selectedColor = $colors->firstWhere('id', $selectedColorId);
                    echo $selectedColor ? $selectedColor->color_code : '#fff'; @endphp;">
            </div>
        </div>
    </div>

    {{-- Import Price --}}
    <div class="col-lg-2">
        <label class="form-label">Giá nhập</label>
        <input type="number" class="form-control @error("variants.$index.import_price") is-invalid @enderror"
            name="variants[{{ $index }}][import_price]"
            value="{{ old("variants.$index.import_price", isset($variant['import_price']) ? number_format($variant['import_price'], 0, '', '') : '') }}"
            min="0" required>
    </div>

    {{-- Listed Price --}}
    <div class="col-lg-2">
        <label class="form-label">Giá niêm yết</label>
        <input type="number" class="form-control @error("variants.$index.listed_price") is-invalid @enderror"
            name="variants[{{ $index }}][listed_price]"
            value="{{ old("variants.$index.listed_price", isset($variant['listed_price']) ? number_format($variant['listed_price'], 0, '', '') : '') }}"
            min="0" required>
    </div>

    {{-- Sale Price --}}
    <div class="col-lg-2">
        <label class="form-label">Giá bán</label>
        <input type="number" class="form-control @error("variants.$index.sale_price") is-invalid @enderror"
            name="variants[{{ $index }}][sale_price]"
            value="{{ old("variants.$index.sale_price", isset($variant['sale_price']) ? number_format($variant['sale_price'], 0, '', '') : '') }}"
            min="0">
    </div>

    {{-- Stock --}}
    <div class="col-lg-2">
        <label class="form-label">Số lượng</label>
        <input type="number" class="form-control @error("variants.$index.stock") is-invalid @enderror"
            name="variants[{{ $index }}][stock]"
            value="{{ old("variants.$index.stock", isset($variant['stock']) ? $variant['stock'] : '') }}"
            min="0" required>
    </div>

    {{-- Image --}}
    <div class="col-lg-4">
        <label class="form-label">Ảnh biến thể</label>
        <div class="input-group">
            <input type="file"
                class="form-control variant-image-input @error("variants.$index.variant_image") is-invalid @enderror"
                name="variants[{{ $index }}][variant_image]" id="variant-image-{{ $index }}"
                data-index="{{ $index }}" accept="image/*">
            <label class="input-group-text" for="variant-image-{{ $index }}">Chọn ảnh</label>
        </div>
        <input type="hidden" name="variants[{{ $index }}][temp_variant_image_url]"
            value="{{ old("variants.$index.temp_variant_image_url", $variant['temp_variant_image_url'] ?? ($variant['variant_image_url'] ?? '')) }}">
        <div id="variant-image-preview-{{ $index }}" class="mt-2">
            @php
                $previewUrl =
                    old("variants.$index.temp_variant_image_url") ??
                    ($variant['temp_variant_image_url'] ?? (null ?? ($variant['variant_image_url'] ?? null)));
            @endphp

            @if ($previewUrl && file_exists(public_path(parse_url($previewUrl, PHP_URL_PATH))))
                <img src="{{ asset(parse_url($previewUrl, PHP_URL_PATH)) }}" alt="Preview" width="100">
            @elseif (!empty($variant['variant_image_url']) && file_exists(public_path($variant['variant_image_url'])))
                <img src="{{ asset($variant['variant_image_url']) }}" alt="Preview" width="100">
            @endif

        </div>
        @if (!empty($variant['id']))
            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] }}">
        @endif
    </div>

    {{-- Remove Button --}}
    <div class="col-lg-8 text-end">
        <button type="button" class="btn btn-danger btn-sm remove-variant">Xoá</button>
    </div>

    {{-- Grouped error block --}}
    @if (
        $errors->hasAny([
            "variants.$index.size_id",
            "variants.$index.color_id",
            "variants.$index.import_price",
            "variants.$index.listed_price",
            "variants.$index.sale_price",
            "variants.$index.stock",
            "variants.$index.variant_image",
            "variants.$index.temp_variant_image_url",
        ]))
        <div class="col-12 mt-2">
            <div class="text-danger small border-start border-3 border-danger ps-3">
                <ul class="mb-0">
                    @foreach ($errors->get("variants.$index.size_id") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.color_id") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.import_price") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.listed_price") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.sale_price") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.stock") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.variant_image") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                    @foreach ($errors->get("variants.$index.temp_variant_image_url") as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

</div>
