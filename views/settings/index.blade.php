
{{ app('xe.frontend')->js('assets/core/xe-ui-component/js/xe-page.js')->load() }}
{{ app('xe.frontend')->js('assets/vendor/jqueryui/jquery-ui.min.js')->load() }}
{{ app('xe.frontend')->css('assets/vendor/jqueryui/jquery-ui.min.css')->load() }}

@section('page_title')
    <h2>{{ xe_trans('xe::claim') }}</h2>
@endsection

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
                    <div class="pull-left">
                        <div class="input-group search-group">
                            <form method="GET" action="{{ route('settings.claim.index') }}" accept-charset="UTF-8" role="form" id="_search-form" class="form-inline">
                                <div class="form-group input-group-btn">
                                    <select name="claim_type" class="form-control">
                                        @php ($selectedType = request()->get('claim_type'))
                                        <option disabled selected>유형 선택</option>
                                        <option value="">전체</option>
                                        @foreach ($claimTypes as $name => $type)
                                            <option value="{{ $name }}" @if ($selectedType === $name) selected @endif>{{ xe_trans($type->getText()) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group input-group-btn">
                                    <select name="claim_status" class="form-control">
                                        @php ($selectedStatus = request()->get('claim_status'))
                                        <option disabled selected>상태 선택</option>
                                        <option value="">전체</option>
                                        @foreach ($claimStatuses as $name => $text)
                                            <option value="{{ $name }}" @if ($selectedStatus === $name) selected @endif>{{ xe_trans($text) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <span class="__xe_selectedKeyfield">
                                                @php ($selectedKeyField = request()->get('keyfield'))
                                                @switch($selectedKeyField)
                                                    @case ('author_name')신고한 유저@break
                                                    @case ('target_name')신고 대상 유저@break
                                                    @case ('message') 신고 사유 @break
                                                    @default 선택 @break
                                                @endswitch
                                            </span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="__xe_selectKeyfield" data-value="author_name">신고한 유저</a></li>
                                            <li><a href="#" class="__xe_selectKeyfield" data-value="target_name">신고 대상 유저</a></li>
                                            <li><a href="#" class="__xe_selectKeyfield" data-value="message">신고 사유</a></li>
                                        </ul>
                                    </div>
                                    <div class="search-input-group">
                                        <input type="text" name="keyword" class="form-control" aria-label="Text input with dropdown button" placeholder="{{xe_trans('xe::enterKeyword')}}" value="{{ Request::get('keyword') }}">
                                    </div>
                                </div>

                                <div class="form-group input-group-btn">
                                    <div class="input-group">
                                        <span class="input-group-addon">접수일</span>
                                        <input type="text" id="startDatePicker" name="start_date" class="form-control" value="{{ request()->get('start_date') }}" placeholder="{{ xe_trans('xe::enterStartDate') }}">
                                        <input type="text" id="endDatePicker" name="end_date" class="form-control" value="{{ request()->get('end_date') }}" placeholder="{{ xe_trans('xe::enterEndDate') }}">
                                    </div>
                                </div>
                                <input type="hidden" class="__xe_keyfield" name="keyfield" value="{{ Request::get('keyfield') }}">

                                <div class="form-group input-group-btn">
                                    <a href="{{ route('settings.claim.index') }}" class="btn btn-default"><span>리셋</span></a>
                                </div>

                                <div class="form-group input-group-btn">
                                    <button type="submit" class="btn btn-primary"><span>검색</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <form id="fList" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" style="max-width: 40px;">No.</th>
                                <th scope="col" style="width: 17%">신고한 사람</th>
                                <th scope="col" style="min-width: 104px;">접수일</th>
                                <th scope="col" style="min-width: 90px;">유형</th>
                                <th scope="col" style="width: 17%">신고 대상</th>
                                <th scope="col" style="min-width: 180px;">신고 사유</th>
                                <th scope="col" style="min-width: 90px;">상태</th>
                                <th scope="col" class="text-center" style="min-width: 90px;">관리</th>
                                <th scope="col" class="text-center" style="min-width: 90px;">삭제</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paginate as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($author = $item->user)
                                            <a href="{{ route('settings.user.edit', ['id'=> $item->user_id]) }}" target="_blank">{{ $author->display_name }}<br>{{ $author->email }}</a>
                                        @else
                                            {{ xe_trans('claim::unknownUser') }}
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $item->claim_type_text }} <a href="{{ $item->short_cut }}" target="_blank"><i class="xi-external-link"></i></a></td>
                                    <td>
                                        @if($targetUser = $item->targetUser)
                                            <a href="{{ route('settings.user.edit', ['id'=> $item->target_user_id]) }}" target="_blank">{{ $targetUser->display_name }}<br>{{ $targetUser->email }}</a>
                                        @else
                                            {{ xe_trans('claim::unknownUser') }}
                                        @endif
                                    </td>
                                    <td>{{ $item->message }}</td>
                                    <td>{{ xe_trans(array_get($claimStatuses, $item->status, $item->status)) }}</td>
                                    <td><button type="button" onclick="location.href = '{{ route('settings.claim.edit', $item->id) }}'" class="xe-btn xe-btn-outline">관리</button></td>
                                    <td>
                                        <button type="button" class="xe-btn xe-btn-danger __xe_delete_claim"
                                                data-text="{{ xe_trans('삭제한 신고는 되돌릴 수 없습니다. 이 신고를 삭제하시겠습니까?') }}"
                                                data-url="{{ route('settings.claim.delete', ['id' => $item->id]) }}">
                                            삭제
                                        </button>
                                    </td>
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

<form id="__claim_settings_delete_form" method="POST">
    {{ csrf_field() }}
</form>

<script>
    $(function (){
        $('.__xe_delete_claim').on('click', function (e) {
            e.preventDefault();
            if (confirm($(this).data('text'))) {
                var form = $('#__claim_settings_delete_form');
                form.attr('action', $(this).data('url'));
                form.submit();
            }
        });

        $("#startDatePicker").datepicker({
            dateFormat: "yy-mm-dd",
            maxDate: 0,
        });

        $("#endDatePicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        initDatePicker();
    })

    var ClaimList = (function() {
        var self;

        return {
            init: function() {
                self = this;

                $(function () {
                    self.cache();
                    self.bindEvents();
                });

                return this;
            },
            cache: function() {
                self.$selectKeyfield = $('.__xe_selectKeyfield');
                self.$selectedKeyfield = $('.__xe_selectedKeyfield');
                self.$keyfield = $('.__xe_keyfield');

                self.$dropdownToggle = $('.dropdown-toggle');
            },
            bindEvents: function() {
                self.$dropdownToggle.dropdown();
                self.$selectKeyfield.on('click', self.selectKeyfield);
            },
            selectKeyfield: function(e) {
                e.preventDefault();

                var $this = $(this),
                    val = $this.attr('data-value'),
                    name = $this.text();

                self.$selectedKeyfield.text(name);
                self.$keyfield.val(val);
            }
        }
    })().init();
</script>
