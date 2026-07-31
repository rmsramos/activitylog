@php
    $isContained = $isContained();
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        {{
            $attributes
                ->merge([
                    'id' => $getId(),
                ], escape: false)
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-in-repeatable',
                    'fi-contained' => $isContained,
                ])
        }}
    >
        @if (count($childComponentContainers = $getChildComponentContainers()))
            <ol class="relative border-gray-200 border-s dark:border-gray-700">
                <div
                    {{
                        (new \Illuminate\View\ComponentAttributeBag(['class' => 'gap-2']))
                            ->grid([
                                'default' => $getGridColumns('default'),
                                'sm' => $getGridColumns('sm'),
                                'md' => $getGridColumns('md'),
                                'lg' => $getGridColumns('lg'),
                                'xl' => $getGridColumns('xl'),
                                '2xl' => $getGridColumns('2xl'),
                            ])
                    }}
                >
                    @foreach ($childComponentContainers as $container)
                        <li
                            @class([
                                'mb-4 ms-6',
                                'fi-in-repeatable-item block',
                                'rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10' => $isContained,
                            ])
                        >
                            {{ $container }}
                        </li>
                    @endforeach
                </div>
            </ol>
        @elseif (($placeholder = $getPlaceholder()) !== null)
            <p class="fi-in-placeholder">
                {{ $placeholder }}
            </p>
        @endif
    </div>
</x-dynamic-component>