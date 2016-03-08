@section('page_title')
    <h2>신고관리</h2>
@stop

@section('page_description')
    신고를 관리하는 페이지 입니다.
@stop

{{ XeFrontend::js('plugins/xe_claim/DefaultManagerSkin/assets/board.js')->load() }}
{{-- include contents blade file --}}
@yield('content', isset($content) ? $content : '')
