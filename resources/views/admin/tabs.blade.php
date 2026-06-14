{{-- Pestañas del Panel Admin --}}
{{-- Pasar la variable $activeTab al @include para resaltar la activa: 'books' | 'writers' | 'vendedores' --}}
<div class="tabs tabs-bordered font-inconsolata mb-6 overflow-x-auto flex-nowrap">
    <a href="{{ route('admin.books.index') }}"
       class="tab tab-sm md:tab-lg whitespace-nowrap {{ ($activeTab ?? '') === 'books' ? 'tab-active text-primary font-bold' : '' }}">
        <span class="icon-[tabler--books] size-4 md:size-5 mr-1 md:mr-2"></span>
        <span class="text-xs sm:text-sm md:text-base">Libros pendientes</span>
    </a>
    <a href="{{ route('admin.writers.index') }}"
       class="tab tab-sm md:tab-lg whitespace-nowrap {{ ($activeTab ?? '') === 'writers' ? 'tab-active text-primary font-bold' : '' }}">
        <span class="icon-[tabler--user-check] size-4 md:size-5 mr-1 md:mr-2"></span>
        <span class="text-xs sm:text-sm md:text-base">Autorización de escritores</span>
    </a>
    <a href="{{ route('admin.vendedores.index') }}"
       class="tab tab-sm md:tab-lg whitespace-nowrap {{ ($activeTab ?? '') === 'vendedores' ? 'tab-active text-primary font-bold' : '' }}">
        <span class="icon-[tabler--pig-money] size-4 md:size-5 mr-1 md:mr-2"></span>
        <span class="text-xs sm:text-sm md:text-base">Autorización de vendedores</span>
    </a>
</div>
