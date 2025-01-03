<button style="{{ 'background-color:' . App\Models\Company::query()->first()->btn_1_color . ';color:' . App\Models\Company::query()->first()->btn_1_text_color }}"
    {{ $attributes->merge(['class' => 'flex items-center space-x-2 px-4 py-2 rounded-md transition-colors duration-200']) }}>
    {{ $slot }}
</button>
