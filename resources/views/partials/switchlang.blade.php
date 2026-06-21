@if (count(config('panel.available_languages', [])) > 1)
    @php
        $languageMeta = [
            'ar' => ['icon' => 'fa-language', 'color' => '#6366f1'],
            'en' => ['icon' => 'fa-globe-americas', 'color' => '#38bdf8'],
        ];
        $languageFallbackColors = ['#34d399', '#f472b6', '#fb923c', '#a78bfa', '#fbbf24'];
    @endphp

    <div class="switchlang-panel mb-4">
        <div class="switchlang-panel__header">
            <span class="switchlang-panel__header-icon">
                <i class="fas fa-globe" aria-hidden="true"></i>
            </span>
            <span class="switchlang-panel__header-label">{{ trans('global.language') }}</span>
        </div>
        <div class="switchlang-panel__items">
            @foreach (config('panel.available_languages') as $langLocale => $langName)
                @php
                    $meta = $languageMeta[$langLocale] ?? [
                        'icon' => 'fa-flag',
                        'color' => $languageFallbackColors[$loop->index % count($languageFallbackColors)],
                    ];
                @endphp
                <a class="switchlang-item @if (currentEditingLang() == $langLocale) active @endif"
                    href="{{ url()->current() }}?lang={{ $langLocale }}"
                    style="--switchlang-item-color: {{ $meta['color'] }};">
                    <span class="switchlang-item__icon">
                        <i class="fas {{ $meta['icon'] }}" aria-hidden="true"></i>
                    </span>
                    <span class="switchlang-item__label">{{ $langName }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
