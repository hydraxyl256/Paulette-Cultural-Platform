<x-filament-widgets::page class="fi-resource-edit">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Column -->
        <div class="lg:col-span-2">
            <form wire:submit="save" class="space-y-6">
                {{ $this->form }}

                <!-- Form Actions -->
                <div class="flex gap-4 pt-6 border-t border-outline-variant border-opacity-15">
                    @foreach($this->getFormActions() as $action)
                    {{ $action }}
                    @endforeach
                </div>
            </form>
        </div>

        <!-- Live Preview Column -->
        <div class="lg:col-span-1">
            <div class="glass-tier-1 rounded-2xl p-6 sticky top-24">
                <h3 class="text-headline-sm font-manrope font-bold text-on-surface mb-6">
                    Live Preview
                </h3>

                <!-- Color Preview -->
                <div class="space-y-4 mb-6">
                    <!-- Primary Color -->
                    <div>
                        <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold mb-2">
                            Primary Button
                        </p>
                        <button 
                            class="w-full px-4 py-3 rounded-xl text-white font-semibold text-body-md transition-all hover:shadow-lift-md"
                            style="background: linear-gradient(135deg, {{ $this->form->getState()['primary_color'] ?? '#0f9361' }} 0%, {{ $this->lighten($this->form->getState()['primary_color'] ?? '#0f9361', 20) }} 100%)">
                            Apply Theme
                        </button>
                    </div>

                    <!-- Secondary Color -->
                    <div>
                        <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold mb-2">
                            Secondary Button
                        </p>
                        <button 
                            class="w-full px-4 py-3 rounded-xl border border-outline-variant border-opacity-30 font-semibold text-body-md transition-all hover:shadow-lift-sm"
                            style="background-color: {{ $this->form->getState()['surface_base'] ?? '#faf8ff' }}; color: {{ $this->form->getState()['primary_color'] ?? '#0f9361' }}">
                            Secondary
                        </button>
                    </div>

                    <!-- Accent Color -->
                    <div>
                        <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold mb-2">
                            Accent Element
                        </p>
                        <div class="w-full h-12 rounded-xl" style="background: {{ $this->form->getState()['accent_color'] ?? '#9d5dff' }}"></div>
                    </div>
                </div>

                <!-- Color Palette Grid -->
                <div class="border-t border-outline-variant border-opacity-15 pt-6 mb-6">
                    <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold mb-3">
                        Color Palette
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <div 
                                class="w-full h-16 rounded-lg mb-2 shadow-md border border-outline-variant border-opacity-15"
                                style="background: {{ $this->form->getState()['primary_color'] ?? '#0f9361' }}">
                            </div>
                            <p class="text-label-sm font-mono">Primary</p>
                        </div>
                        <div class="text-center">
                            <div 
                                class="w-full h-16 rounded-lg mb-2 shadow-md border border-outline-variant border-opacity-15"
                                style="background: {{ $this->form->getState()['secondary_color'] ?? '#d67800' }}">
                            </div>
                            <p class="text-label-sm font-mono">Secondary</p>
                        </div>
                        <div class="text-center">
                            <div 
                                class="w-full h-16 rounded-lg mb-2 shadow-md border border-outline-variant border-opacity-15"
                                style="background: {{ $this->form->getState()['accent_color'] ?? '#9d5dff' }}">
                            </div>
                            <p class="text-label-sm font-mono">Accent</p>
                        </div>
                        <div class="text-center">
                            <div 
                                class="w-full h-16 rounded-lg mb-2 shadow-md border border-outline-variant border-opacity-15"
                                style="background: {{ $this->form->getState()['error_color'] ?? '#c5192d' }}">
                            </div>
                            <p class="text-label-sm font-mono">Error</p>
                        </div>
                    </div>
                </div>

                <!-- Card Preview -->
                <div class="border-t border-outline-variant border-opacity-15 pt-6">
                    <p class="text-label-sm text-primary-low uppercase tracking-wide font-semibold mb-3">
                        Card Preview
                    </p>
                    <div class="glass-tier-1 p-4 rounded-xl">
                        <p class="text-headline-sm font-manrope font-bold text-on-surface mb-1">
                            Preview Card
                        </p>
                        <p class="text-body-sm text-primary-low">
                            This is how cards will look with your theme.
                        </p>
                        <button 
                            class="w-full mt-3 px-3 py-2 rounded-lg text-white font-semibold text-label-sm transition-all hover:shadow-lift-sm"
                            style="background: {{ $this->form->getState()['primary_color'] ?? '#0f9361' }}">
                            Sample CTA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update preview in real-time as colors change
        Livewire.on('formUpdated', () => {
            // Refresh preview
        });
    </script>
    @endpush
</x-filament-widgets::page>
