@props(['href' => null, 'variant' => 'header'])

@php
  $href = $href ?? route('welcome');
  $linkClass = $variant === 'footer' ? 'footer-brand' : 'navbar-brand';
@endphp

<a {{ $attributes->merge(['class' => $linkClass]) }} href="{{ $href }}">
  <img src="{{ asset('assets/images/pts-logo.png') }}" alt="Preowned Technical Services" class="site-logo">
</a>
