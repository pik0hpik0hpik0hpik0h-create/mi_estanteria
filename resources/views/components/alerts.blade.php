@if(session('success'))
<div class="font-inconsolata fixed bottom-5 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-5 bg-success border border-success-content/20 text-success-content px-4 py-2 rounded shadow-lg w-fit z-1000">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="font-inconsolata fixed bottom-5 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-5 bg-error border border-error-content/20 text-error-content px-4 py-2 rounded shadow-lg w-fit z-1000">
    {{ session('error') }}
</div>
@endif

@if(session('warning'))
<div class="font-inconsolata fixed bottom-5 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-5 bg-warning border border-warning-content/20 text-warning-content px-4 py-2 rounded shadow-lg w-fit z-1000">
    {{ session('warning') }}
</div>
@endif

@if(session('info'))
<div class="font-inconsolata fixed bottom-5 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-5 bg-info border border-info-content/20 text-info-content px-4 py-2 rounded shadow-lg w-fit z-1000">
    {{ session('info') }}
</div>
@endif