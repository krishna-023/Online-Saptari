@foreach($items as $item)
<tr class="align-middle transition-hover item-row" data-id="{{ $item->id }}">
    <td>
        <input type="checkbox" class="row-checkbox" value="{{ $item->id }}" data-encrypted-id="{{ encrypt($item->id) }}">
    </td>
    <td>
        <span class="badge bg-light text-dark">{{ $item->id }}</span>
    </td>
    <td>
        <span class="badge bg-primary bg-opacity-10 text-primary">
            {{ $item->category->Category_Name ?? 'N/A' }}
        </span>
    </td>
    <td>
        <div class="d-flex align-items-center">
            @if($item->image)
                <img src="{{ asset('storage/image/' . $item->image) }}"
                     alt="{{ $item->title }}"
                     class="rounded me-2"
                     style="width: 30px; height: 30px; object-fit: cover;">
            @endif
            <span class="fw-semibold">{{ $item->title }}</span>
        </div>
    </td>
    <td>{{ $item->subtitle ?? 'N/A' }}</td>
    <td>
        @if($item->contacts && $item->contacts->telephone)
            <a href="tel:{{ $item->contacts->telephone }}" class="text-decoration-none">
                <i class="ri-phone-line me-1"></i>{{ $item->contacts->telephone }}
            </a>
        @else
            N/A
        @endif
    </td>
    <td>
        @if($item->contacts && $item->contacts->email)
            <a href="mailto:{{ $item->contacts->email }}" class="text-decoration-none">
                <i class="ri-mail-line me-1"></i>{{ $item->contacts->email }}
            </a>
        @else
            N/A
        @endif
    </td>
    <td>
        <div class="d-flex gap-1">
            <a href="{{ route('item.view', encrypt($item->id)) }}"
               class="btn btn-outline-primary btn-sm"
               title="View">
                <i class="ph-eye"></i>
            </a>
            <a href="{{ route('item.edit', encrypt($item->id)) }}"
               class="btn btn-outline-secondary btn-sm"
               title="Edit">
                <i class="ph-pencil"></i>
            </a>
            <form action="{{ route('item.destroy', encrypt($item->id)) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this item?')"
                        title="Delete">
                    <i class="ph-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach
