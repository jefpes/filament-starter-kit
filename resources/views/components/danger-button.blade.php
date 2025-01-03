<button style="{{ 'background-color:' . App\Models\Settings::query()->first()->btn_3_color . ';color:' . App\Models\Settings::query()->first()->btn_3_text_color }}"
{{ $attributes->merge(['class' => 'flex-1 focus:outline-none  focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2']) }}>
    {{ $slot }}
</button>
