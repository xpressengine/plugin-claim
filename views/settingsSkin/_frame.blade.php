@section('page_title')
    <h2>{{xe_trans('claim::claimManage')}}</h2>
@stop

{{-- include contents blade file --}}
@section('content')
    <div class="container-fluid container-fluid--part claim">
    {!! isset($content) ? $content : '' !!}
    </div>
@show
