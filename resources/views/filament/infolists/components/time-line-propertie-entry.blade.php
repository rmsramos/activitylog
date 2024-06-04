<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>

        {{ $getModifiedState() ?? (!is_array($getState()) ? $getState() ?? $getPlaceholder() : null) }}

    </div>
</x-dynamic-component>
