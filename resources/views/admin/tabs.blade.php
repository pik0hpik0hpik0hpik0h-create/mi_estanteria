{{-- Pestañas del Panel Admin --}}
{{-- Pasar la variable $activeTab al @include para resaltar la activa: 'books' | 'writers' --}}
<div class="tabs tabs-bordered font-inconsolata mb-6">
    <a href="{{ route('admin.books.index') }}"
       class="tab tab-lg {{ ($activeTab ?? '') === 'books' ? 'tab-active text-primary font-bold' : '' }}">
        <span class="icon-[tabler--books] size-5 mr-2"></span>
        Libros pendientes
    </a>
    <a href="{{ route('admin.writers.index') }}"
       class="tab tab-lg {{ ($activeTab ?? '') === 'writers' ? 'tab-active text-primary font-bold' : '' }}">
        <span class="icon-[tabler--user-check] size-5 mr-2"></span>
        Autorización de escritores
    </a>
</div>
