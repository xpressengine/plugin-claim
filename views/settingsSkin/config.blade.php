<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Claim Configues</h3>
                    </div>
                </div>
                <form method="post" id="board_manage_form" action="{{ route('manage.claim.claim.config.update')  }}">
                    {{ csrf_field() }}

                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="panel">

                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">Settings</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-category-select">
                                                <div class="clearfix">
                                                    <label>Category</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-9">
                                                        <select id="" name="category" class="form-control" data-id="{{ $config->get('categoryId') }}" data-url="{{ route('manage.claim.claim.config.storeCategory')  }}">
                                                            <option value="true" {!! $config->get('category') == true ? 'selected="selected"' : '' !!} >{{xe_trans('xe::use')}}</option>
                                                            <option value="false" {!! $config->get('category') == false ? 'selected="selected"' : '' !!} >{{xe_trans('xe::disuse')}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <a
                                                            class="btn btn-default pull-right"
                                                            href="{{ route('manage.category.show', ['id' => $config->get('categoryId') ?? '']) }}"
                                                            @if($config->get('category') == false) disabled="disabled" @endif
                                                        >
                                                            {{xe_trans('claim::categoryManage')}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary"><i class="xi-download"></i>Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
