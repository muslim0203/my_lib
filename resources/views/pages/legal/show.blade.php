<!DOCTYPE html>
<html lang="{{ $locale === 'ru' ? 'ru' : 'uz' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | my-lib.uz</title>
    <style>
        :root { color-scheme: light dark; --bg: #fff; --fg: #1f2328; --line: #d0d7de; --accent: #0969da; }
        @media (prefers-color-scheme: dark) { :root { --bg: #0d1117; --fg: #e6edf3; --line: #30363d; --accent: #4493f8; } }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--fg); font: 16px/1.65 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
        main { max-width: 820px; margin: 0 auto; padding: 24px 16px 64px; }
        nav { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
        nav a { padding: 4px 12px; border: 1px solid var(--line); border-radius: 999px; color: var(--fg); text-decoration: none; font-size: 14px; }
        nav a[aria-current="page"] { border-color: var(--accent); color: var(--accent); font-weight: 600; }
        h1 { font-size: 24px; line-height: 1.3; text-align: center; margin: 0 0 32px; }
        h2 { font-size: 18px; margin: 32px 0 8px; }
        p { margin: 0 0 12px; text-align: justify; overflow-wrap: anywhere; }
        nav.docs { margin-bottom: 12px; }
        footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid var(--line); font-size: 14px; }
        footer a { color: var(--accent); }
    </style>
</head>
<body>
<main>
    <nav class="docs">
        @foreach($documents as $doc)
            <a href="{{ route('legal.show', ['document' => $doc, 'locale' => $locale]) }}"
               @if($doc === $document) aria-current="page" @endif>{{ $documentTitles[$doc] }}</a>
        @endforeach
    </nav>
    <nav>
        @foreach($languages as $code => $label)
            <a href="{{ route('legal.show', ['document' => $document, 'locale' => $code]) }}" hreflang="{{ $code === 'ru' ? 'ru' : 'uz' }}"
               @if($code === $locale) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
    <article>
        {{-- resources/legal/*/*.html - repozitoriydagi o'zimizning fayl, foydalanuvchi ma'lumoti emas --}}
        {!! $content !!}
    </article>
    @if($contacts['email'] || $contacts['phone'])
        <footer>
            <strong>{{ ['oz' => '“AJ AND J GROUP” MChJ bilan aloqa', 'uz' => '“AJ AND J GROUP” МЧЖ билан алоқа', 'ru' => 'Контакты ООО «AJ AND J GROUP»'][$locale] }}:</strong>
            @if($contacts['email'])
                <a href="mailto:{{ $contacts['email'] }}">{{ $contacts['email'] }}</a>
            @endif
            @if($contacts['email'] && $contacts['phone']) · @endif
            @if($contacts['phone'])
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contacts['phone']) }}">{{ $contacts['phone'] }}</a>
            @endif
        </footer>
    @endif
</main>
</body>
</html>
