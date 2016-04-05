<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">{{ xe_trans('xe::claim') }} {{ xe_trans('xe::management') }}</h3>
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="pull-right">
                        <div class="btn-group" role="group" aria-label="...">
                            {{--<button type="button" class="btn btn-default" data-mode="destroy">--}}
                                {{--<i class="fa fa-times"></i>--}}
                                {{--삭제--}}
                            {{--</button>--}}
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <form id="fList" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ xe_trans('xe::claim') }} {{ xe_trans('xe::user') }}</th>
                                <th scope="col">{{ xe_trans('xe::shortcut') }}</th>
                                <th scope="col">{{ xe_trans('xe::date') }}</th>
                                <th scope="col">{{ xe_trans('xe::ipAddress') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paginate as $item)
                                <tr>
                                    <td><b>[{{ $item->user->getDisplayName() }}]</b></td>
                                    <td>{{ $item['claimType'] }} <a href="{{ $item['shortCut'] }}" class="btn btn-default" target="_blank"><i class="xi-external-link"></i></a></td>
                                    <td>{{ $item['createdAt'] }}</td>
                                    <td>{{ $item['ipaddress'] }}</td>
                                    {{--<td><button type="button" class="btn btn-default __xe_delete_claim" data-id="{{ $item['id'] }}">삭제</button></td>--}}
                                    {{--<td><input type="checkbox" name="id[]" class="__xe_checkbox" value="{{ $item['id'] }}"></td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="pull-left">
                        <nav>
                            {!! $paginate->render() !!}
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{--<script type="text/javascript">--}}
    {{--$(function () {--}}

        {{--var list = $('.__xe_claim');--}}
        {{--list.on('click', '.__xe_delete_claim', function() {--}}
            {{--var id = $(this).attr('data-id');--}}

            {{--var _this = this;--}}
            {{--$.ajax({--}}
                {{--type: 'get',--}}
                {{--dataType: 'json',--}}
                {{--data: {id:id},--}}
                {{--url: '{{ route('manage.claim.claim.delete') }}',--}}
                {{--success: function(response) {--}}
                    {{--$(_this).parents('tr').hide();--}}
                {{--},--}}
                {{--error: function(response) {--}}
                    {{--var responseText = $.parseJSON(response.responseText);--}}
                    {{--var type = 'danger';--}}
                    {{--var errorMessage = responseText.message;--}}
                    {{--XE.toast(type, errorMessage);--}}
                    {{--self.openStep('close');--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}

        {{--$('#check-all').click(function () {--}}
            {{--if ($(this).is(':checked')) {--}}
                {{--$('input.__xe_checkbox').click();--}}
            {{--} else {--}}
                {{--$('input.__xe_checkbox').removeAttr('checked');--}}
            {{--}--}}
        {{--});--}}

        {{--$('#tools button').click(function () {--}}
            {{--var mode = $(this).attr('data-mode'), flag = false;--}}

            {{--$('input.__xe_checkbox').each(function () {--}}
                {{--if ($(this).is(':checked')) {--}}
                    {{--flag = true;--}}
                {{--}--}}
            {{--});--}}

            {{--if (flag !== true) {--}}
                {{--alert('select document');--}}
                {{--return;--}}
            {{--}--}}

            {{--var $f = $('#fList');--}}
            {{--$('<input>').attr('type', 'hidden').attr('name', 'redirect').val(location.href).appendTo($f);--}}

            {{--eval('actions.' + mode + '($f)');--}}
        {{--});--}}

    {{--});--}}
{{--</script>--}}
