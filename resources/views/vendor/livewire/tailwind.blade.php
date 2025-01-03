@php
$scrollTo = $scrollTo ?? 'body';
$scrollIntoViewJsSnippet = $scrollTo !== false
? "document.querySelector('{$scrollTo}').scrollIntoView()"
: '';
@endphp

<div>
    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            <span>
                @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium border-gray-300 cursor-default leading-5 rounded-md"
                    style="background-color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; color: {{ App\Models\Company::query()->first()->btn_1_color }};">
                    {!! __('pagination.previous') !!}
                </span>
                @else
                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-md transition ease-in-out duration-150"
                    style="background-color: {{ App\Models\Company::query()->first()->btn_1_color }}; color: {{ App\Models\Company::query()->first()->btn_1_text_color }};">
                    {!! __('pagination.previous') !!}
                </button>
                @endif
            </span>

            <span>
                @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 rounded-md transition ease-in-out duration-150"
                    style="background-color: {{ App\Models\Company::query()->first()->btn_1_color }}; color: {{ App\Models\Company::query()->first()->btn_1_text_color }};">
                    {!! __('pagination.next') !!}
                </button>
                @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 rounded-md"
                    style="background-color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; color: {{ App\Models\Company::query()->first()->btn_1_color }};">
                    {!! __('pagination.next') !!}
                </span>
                @endif
            </span>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm leading-5">
                    <span>{!! __('Showing') !!}</span>
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    <span>{!! __('to') !!}</span>
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>{!! __('of') !!}</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>{!! __('results') !!}</span>
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                    <span>
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-l-md leading-5"
                                aria-hidden="true"
                                style="background-color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; color: {{ App\Models\Company::query()->first()->btn_1_color }};">
                                <x-icons.left class="w-5 h-5"></x-icons.left>
                            </span>
                        </span>
                        @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-l-md leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.previous') }}"
                            style="background-color: {{ App\Models\Company::query()->first()->btn_1_color }}; color: {{ App\Models\Company::query()->first()->btn_1_text_color }};">
                            <x-icons.left class="w-5 h-5"></x-icons.left>
                        </button>
                        @endif
                    </span>

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                    </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                    @foreach ($element as $page => $url)
                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                        @if ($page == $paginator->currentPage())
                        <span aria-current="page">
                            <span
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 border border-gray-300 cursor-default leading-5"
                                style="background-color: {{ App\Models\Company::query()->first()->btn_1_color }}; color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; filter: brightness(90%);">{{ $page }}</span>
                            </span>
                        @else
                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                            style="background-color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; color: {{
                                App\Models\Company::query()->first()->btn_1_color }};">
                            {{ $page }}
                        </button>
                        @endif
                    </span>
                    @endforeach
                    @endif
                    @endforeach

                    <span>
                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium rounded-r-md leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.next') }}"
                            style="background-color: {{ App\Models\Company::query()->first()->btn_1_color }}; color: {{ App\Models\Company::query()->first()->btn_1_text_color }};">
                            <x-icons.right class="w-5 h-5"></x-icons.right>
                        </button>
                        @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium rounded-r-md leading-5"
                                aria-hidden="true"
                                style="background-color: {{ App\Models\Company::query()->first()->btn_1_text_color }}; color: {{ App\Models\Company::query()->first()->btn_1_color }};">
                                <x-icons.right class="w-5 h-5"></x-icons.right>
                            </span>
                        </span>
                        @endif
                    </span>
                </span>
            </div>
        </div>
    </nav>
    @endif
</div>