@php
    $rendered = preg_replace_callback(
        '/\{\{([A-Z0-9_]+)\}\}/',
        function ($m) {
            $class = in_array($m[1], \App\Models\Term::RESERVED)
                ? 'bg-success/15 text-success'
                : 'bg-primary/15 text-primary';

            return '<mark class="' . $class . ' px-1 py-0.5 rounded font-mono text-sm not-italic">'
                . e('{{' . $m[1] . '}}')
                . '</mark>';
        },
        e(trim($term->body))
    );
@endphp
<div class="prose prose-sm max-w-none text-base-content/80 whitespace-pre-wrap font-mono text-sm leading-relaxed">{!! $rendered !!}</div>
