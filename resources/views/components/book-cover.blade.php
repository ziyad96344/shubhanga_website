@props(['book', 'eager' => false])

<div {{ $attributes->merge(['class' => 'cover']) }}>
    <img src="{{ $book['image'] }}"
         alt="{{ $book['name'] ?? trim($book['series'].' '.$book['subject']) }}{{ $book['class'] !== '' ? ', '.\App\Support\Catalog::classLabel($book['class']) : '' }} cover"
         loading="{{ $eager ? 'eager' : 'lazy' }}"
         decoding="async"
         width="400" height="533">
</div>
