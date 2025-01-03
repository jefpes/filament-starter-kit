@props(['money'])
<span {{ $attributes->merge([ 'class' => 'tracking-[0.10rem]']) }}> {{ $money ? 'R$ '.number_format($money, 2, ',', '.') : '' }}</span>
