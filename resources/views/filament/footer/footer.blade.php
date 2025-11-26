@php
    use Filament\Support\Enums\Width;
    
    $startTime = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
    $loadTime = number_format(microtime(true) - $startTime, 3);
    $appName = config('app.name');
    
    $footerPosition = 'footer';
    $borderTopEnabled = true;
    $loadTimeEnabled = true;
    $loadTimePrefix = 'This page loaded in';
    $githubUrl = config('fillakit.github_url');
    $logoPath = null;
    $logoUrl = null;
    $logoText = null;
    $logoHeight = 20;
    $links = [
        ['title' => 'About', 'url' => '#'],
        ['title' => 'Privacy Policy', 'url' => '#'],
    ];
    
    if ($githubUrl) {
        $links[] = ['title' => 'Github', 'url' => $githubUrl];
    }
    $sentence = null;
    $isHtmlSentence = false;
    
    $maxContentWidth = filament()->getMaxContentWidth() ?? Width::SevenExtraLarge;
@endphp

@php
    $elements = [];
    
    $elements[] = [
        'type' => 'copyright',
        'content' => '© ' . now()->format('Y') . ($sentence ? ($isHtmlSentence ? '<span class="flex items-center gap-2">' . $sentence . '</span>' : ' ' . $sentence) : ' ' . $appName),
        'isHtml' => $isHtmlSentence,
    ];
    
    if ($logoPath) {
        $logoContent = ($logoUrl ? '<a href="' . $logoUrl . '" class="inline-flex transition-opacity hover:opacity-75" target="_blank">' : '') .
            '<img src="' . $logoPath . '" alt="Logo" class="w-auto object-contain" style="height: ' . $logoHeight . 'px;">' .
            ($logoUrl ? '</a>' : '');
        if ($logoText) {
            $logoContent = $logoText . ' ' . $logoContent;
        }
        $elements[] = [
            'type' => 'logo',
            'content' => $logoContent,
            'isHtml' => true,
        ];
    }
    
    if ($loadTime) {
        $elements[] = [
            'type' => 'loadtime',
            'content' => ($loadTimePrefix ?? '') . ' ' . $loadTime . 's',
            'isHtml' => false,
        ];
    }
    
    if (count($links) > 0) {
        $linksContent = '<ul class="flex items-center gap-x-2 gap-y-1 flex-wrap sm:gap-x-3">';
        foreach ($links as $link) {
            $linksContent .= '<li><a href="' . $link['url'] . '" class="transition-colors hover:text-gray-700 dark:hover:text-gray-300" target="_blank">' . $link['title'] . '</a></li>';
        }
        $linksContent .= '</ul>';
        $elements[] = [
            'type' => 'links',
            'content' => $linksContent,
            'isHtml' => true,
        ];
    }
@endphp

<footer
    @class([
        'fi-footer flex flex-wrap items-center justify-center gap-x-2 gap-y-1 text-sm text-gray-500 dark:text-gray-400',
        'sm:gap-x-3 sm:gap-y-1.5' => $footerPosition !== 'sidebar' && $footerPosition !== 'sidebar.footer',
        'border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 sm:pt-6 sm:mt-6' => $borderTopEnabled === true,
        'text-center p-2' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer',
        'fi-sidebar gap-2 h-auto' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer',
        'mx-auto w-full px-3 sm:px-4 md:px-6 lg:px-8 pb-4 sm:pb-6' => $footerPosition === 'footer',
        match ($maxContentWidth) {
            Width::ExtraSmall, 'xs' => 'max-w-xs',
            Width::Small, 'sm' => 'max-w-sm',
            Width::Medium, 'md' => 'max-w-md',
            Width::Large, 'lg' => 'max-w-lg',
            Width::ExtraLarge, 'xl' => 'max-w-xl',
            Width::TwoExtraLarge, '2xl' => 'max-w-2xl',
            Width::ThreeExtraLarge, '3xl' => 'max-w-3xl',
            Width::FourExtraLarge, '4xl' => 'max-w-4xl',
            Width::FiveExtraLarge, '5xl' => 'max-w-5xl',
            Width::SixExtraLarge, '6xl' => 'max-w-6xl',
            Width::SevenExtraLarge, '7xl' => 'max-w-7xl',
            Width::Full, 'full' => 'max-w-full',
            Width::MinContent, 'min' => 'max-w-min',
            Width::MaxContent, 'max' => 'max-w-max',
            Width::FitContent, 'fit' => 'max-w-fit',
            Width::Prose, 'prose' => 'max-w-prose',
            Width::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
            Width::ScreenMedium, 'screen-md' => 'max-w-screen-md',
            Width::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
            Width::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
            Width::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',
            default => $maxContentWidth,
        } => $footerPosition === 'footer',
    ])
>
    @foreach($elements as $index => $element)
        <span class="flex items-center">
            @if($element['isHtml'])
                {!! $element['content'] !!}
            @else
                {{ $element['content'] }}
            @endif
        </span>
        @if(!$loop->last)
            <span class="text-gray-300 dark:text-gray-600 select-none mx-0.5 sm:mx-1">·</span>
        @endif
    @endforeach
</footer>
