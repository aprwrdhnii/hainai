@extends('layouts.app')
@section('title', 'Testimoni Pelanggan')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Testimoni Pelanggan</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Ulasan dan rating dari pelanggan</p>
    </div>
</div>

<!-- Testimonials Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($testimonials as $testimonial)
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition flex flex-col h-full">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300">
                    {{ substr($testimonial->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-sm text-gray-800 dark:text-white">{{ $testimonial->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $testimonial->created_at->format('d M Y') }}</p>
                </div>
            </div>
            
            <div class="flex gap-1">
                @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star-fill text-xs {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-700' }}"></i>
                @endfor
            </div>
        </div>
        
        <div class="flex-1 mb-4 relative">
             <i class="bi bi-quote absolute -top-2 -left-2 text-4xl text-gray-100 dark:text-gray-700 z-0"></i>
            <p class="text-sm text-gray-600 dark:text-gray-300 relative z-10 italic">
                "{{ $testimonial->content }}"
            </p>
        </div>

        <div class="pt-4 border-t border-gray-50 dark:border-gray-700/50 flex justify-end">
            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Hapus testimoni ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition flex items-center gap-1">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
        <i class="bi bi-chat-quote text-4xl text-gray-300 dark:text-gray-600 mb-3 block"></i>
        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum ada testimoni</h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Testimoni pelanggan akan muncul di sini.</p>
    </div>
    @endforelse
</div>

@if($testimonials->hasPages())
<div class="mt-6">
    {{ $testimonials->links() }}
</div>
@endif
@endsection
