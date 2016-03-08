<div class="panel">
    <div class="panel-heading">

<div class="container">

    <div id="tools">
        <div class="text-right">
            <div class="btn-group">
                {{--<button type="button" class="btn btn-default" data-mode="destroy">--}}
                    {{--<i class="fa fa-times"></i>--}}
                    {{--삭제--}}
                {{--</button>--}}
            </div>
        </div>
    </div>
    <br>

    <form id="fList" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <table class="table table-striped table-bordered __xe_claim">
            <thead>
            <tr>
                <th>신고 회원 정보</th>
                <th>바로가기</th>
                <th>날짜</th>
                <th>IP</th>
                {{--<th>신고 삭제</th>--}}
                {{--<th><input type="checkbox" id="check-all"></th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($paginate as $item)
                <tr>
                    <td><b>[{{ $item->user->getDisplayName() }}]</b></td>
                    <td>{{ $item['claimType'] }} <a href="{{ $item['shortCut'] }}" class="btn btn-default" target="_blank">바로가기</a></td>
                    <td>{{ $item['createdAt'] }}</td>
                    <td>{{ $item['ipaddress'] }}</td>
                    {{--<td><button type="button" class="btn btn-default __xe_delete_claim" data-id="{{ $item['id'] }}">삭제</button></td>--}}
                    {{--<td><input type="checkbox" name="id[]" class="__xe_checkbox" value="{{ $item['id'] }}"></td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </form>

    <nav class="text-center">{!! $paginate->render() !!}</nav>

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
                    {{--alertBox(type, errorMessage);--}}
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
