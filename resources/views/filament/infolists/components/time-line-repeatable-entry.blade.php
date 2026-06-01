@php
    use Illuminate\View\ComponentAttributeBag;$isContained = $isContained();
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
                        (new ComponentAttributeBag)
                            ->grid($getGridColumns())
                            ->class(['fi-in-repeatable-items gap-2'])
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
            <x-filament-infolists::entry-wrapper>
                {{ $placeholder }}
            </x-filament-infolists::entry-wrapper>
        @endif
    </div>
</x-dynamic-component>
