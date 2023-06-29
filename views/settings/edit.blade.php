
@section('page_title')
    <h2>{{ xe_trans('xe::claim') }}</h2>
@endsection

<div class="container-fluid container-fluid--part">
    <div class="row">
        <form class="form" name="fUserEdit" method="post" action="{{ route('settings.claim.update', [$log->id]) }}" enctype="multipart/form-data">
            {{ method_field('put') }}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">신고 관리 - No. {{ $log->id }}</h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>신고한 유저</label>
                                    @if ($author = $log->user)
                                        <a href="{{ route('settings.user.edit', ['id' => $log->user_id]) }}" target="_blank">
                                            <input type="text" class="form-control" value="{{ $author->display_name }} ({{ $author->email }})" disabled>
                                        </a>
                                    @else
                                        <input type="text" class="form-control" value="{{ xe_trans('claim::unknownUser') }}" disabled>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>신고한 유저 IP</label>
                                    <input type="text" class="form-control" value="{{ $log->ipaddress }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>접수일</label>
                                    <input type="text" class="form-control" value="{{ $log->created_at }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>유형</label>
                                    <input type="text" class="form-control" value="{{ $targetClaimTypeText }}" readonly>
                                    <a href="{{ $log->short_cut }}" target="_blank" style="float: right">바로가기 <i class="xi-external-link"></i></a>
                                </div>

                                <div class="form-group">
                                    <label>신고 대상</label>
                                    @if ($targetUser = $log->targetUser)
                                        <a href="{{ route('settings.user.edit', ['id' => $log->target_user_id]) }}" target="_blank">
                                            <input type="text" class="form-control" value="{{ $targetUser->display_name }} ({{ $targetUser->email }})" disabled>
                                        </a>
                                    @else
                                        <input type="text" class="form-control" value="{{ xe_trans('claim::unknownUser') }}" disabled>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="">신고 사유</label>
                                    <textarea rows="3" type="text" class="form-control" disabled>{{ $log->message }}</textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>상태</label>
                                    <select class="form-control" name="status" required>
                                        @foreach ($claimStatuses as $key => $text)
                                            <option value="{{ $key }}" @if ($log->status === $key) selected @endif>{{ xe_trans($text) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="">신고 처리 내용</label>
                                    <textarea rows="6" type="text" class="form-control" name="admin_message">{{ $log->admin_message }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-right">
                            <a href="javascript:history.back();" class="btn btn-default btn-lg">{{ xe_trans('xe::cancel') }}</a>
                            <button type="submit" class="btn btn-primary btn-lg">{{ xe_trans('xe::save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
