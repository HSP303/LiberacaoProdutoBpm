<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="block text-lg font-medium text-gray-700 mb-3 mt-3">
            {{ $label }}
        </label>
    @endif

    <div>
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            class="border rounded p-2 w-full shadow-sm focus:ring focus:ring-blue-300"
        >
    </div>
</div>
