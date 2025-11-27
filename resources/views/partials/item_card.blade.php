<div class="col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden hover-scale">
        @php
            use Illuminate\Support\Str;

            $gallery = $item->galleries->first();
            $imageUrl = null;

            if ($gallery) {
                // Try casted value first
                $imgs = is_array($gallery->gallery) ? $gallery->gallery : (is_string($gallery->gallery) ? [$gallery->gallery] : []);

                // Fallback to raw DB attribute if cast returned null/empty
                if (empty($imgs)) {
                    $raw = $gallery->getAttributes()['gallery'] ?? null;
                    if ($raw) {
                        $decoded = @json_decode($raw, true);
                        $imgs = is_array($decoded) ? $decoded : [$raw];
                    }
                }

                $first = $imgs[0] ?? null;
                if ($first) {
                    $first = trim($first);
                    if (Str::startsWith($first, ['http://', 'https://'])) {
                        $imageUrl = $first;
                    } elseif (Str::startsWith($first, 'storage/')) {
                        $imageUrl = asset($first);
                    } else {
                        // assume stored under storage/ (public disk)
                        $imageUrl = asset('storage/' . ltrim($first, '/'));
                    }
                }
            }

            if (!$imageUrl && $item->image) {
                $imageUrl = asset('storage/' . $item->image);
            }
        @endphp

        <img src="{{ $imageUrl ?? asset('images/no-image.png') }}" class="card-img-top" alt="{{ $item->title }}">
        <div class="card-body d-flex flex-column">
            <h6 class="card-title fw-bold">{{ $item->title }}</h6>
            <p class="card-text text-muted mb-2">{{ Str::limit($item->subtitle ?? $item->content, 60) }}</p>
            <p class="mb-1"><i class="bi bi-tag-fill me-1"></i>{{ $item->category->Category_Name ?? '-' }}</p>
            <p class="mb-1"><i class="bi bi-telephone-fill me-1"></i>{{ $item->contacts->first()->telephone ?? '-' }}</p>
            <a href="{{ route('item.view', encrypt($item->id)) }}" class="btn btn-outline-primary mt-auto rounded-pill">View Details</a>
        </div>
    </div>
</div>
