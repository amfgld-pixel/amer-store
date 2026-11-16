<form class="category-admin-row {{ $isChild ? 'is-child' : 'is-parent' }}" method="post" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')

    <div>
        <span class="category-kind">{{ $isChild ? 'Subcategory' : 'Main category' }}</span>
        <input name="name" value="{{ $category->name }}" required>
    </div>
    <input name="name_bn" value="{{ $category->name_bn }}" placeholder="Bangla name">
    <input name="slug" value="{{ $category->slug }}" required>
    <select name="parent_id" aria-label="Parent category">
        <option value="">— Main category —</option>
        @foreach($parentOptions as $parent)
            @if($parent->id !== $category->id)
                <option value="{{ $parent->id }}" @selected($category->parent_id === $parent->id)>{{ $parent->name }}</option>
            @endif
        @endforeach
    </select>
    <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" required>
    <select name="is_active" aria-label="Status">
        <option value="1" @selected($category->is_active)>Active</option>
        <option value="0" @selected(!$category->is_active)>Inactive</option>
    </select>
    <textarea name="description" hidden>{{ $category->description }}</textarea>
    <button>Save</button>
</form>
