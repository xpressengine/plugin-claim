{{ Frontend::js('plugins/xe_claim/DefaultManagerSkin/assets/board.js')->load() }}
{{-- include contents blade file --}}
@yield('content', isset($content) ? $content : '')
