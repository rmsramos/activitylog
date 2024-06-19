<div class="my-2 text-sm tracking-tight">
    @foreach($getState() as $key => $value)
        <span class="inline-block p-1 mr-1 font-medium text-gray-700 whitespace-normal rounded-md dark:text-gray-200 bg-gray-500/10">
            {{ $key }}
        </span>

        @if(is_array($value))
            <span class="whitespace-normal divide-x divide-gray-200 divide-solid dark:divide-gray-700">
                @foreach ($value as $nestedKey => $nestedValue)
                    <span class="inline-block mr-1">
                        {{ $nestedKey }}: {{ is_array($nestedValue) ? json_encode($nestedValue) : $nestedValue }}
                    </span>
                @endforeach
            </span>
        @else
            <span class="whitespace-normal">{{ $value }}</span>
        @endif
    @endforeach
</div>
