@props(['title' => null, 'subtitle' => null])

<x-card :title="$title" :subtitle="$subtitle">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    {{ $slot }}
                </tr>
            </thead>
        </table>
    </div>
</x-card>
