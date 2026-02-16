@props(['disabled' => false])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn', 'disabled' => $disabled ? 'disabled' : false]) }}>
    {{ $slot }}
</button>