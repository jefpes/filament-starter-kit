@props(['label' => null,'messages' => null])
<div>
  @if ($label)
    <label class="block mb-2 text-sm font-medium" style="{{ 'color:' . App\Models\Company::query()->first()->select_text_color }}"> {{ __($label) }} </label>
  @endif
  <select style="{{ 'background-color:' . App\Models\Company::query()->first()->select_color . ';color:' . App\Models\Company::query()->first()->select_text_color }}"
    {{ $attributes->merge(['class' => 'border text-sm rounded-lg block w-full p-2']) }}>
    {{ $slot }}
  </select>

  @if ($messages)
    <ul class="text-sm text-red-600 space-y-1">
        @foreach ((array) $messages as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
  @endif
</div>
