<ul class="nav nav-tabs">
    <li role="presentation" {!!$action == 'index' ? ' class="active"' : '' !!}><a href="/{{ route('manage.claim.claim.index') }}" role="tab">Details</a></li>
    <li role="presentation" {!!$action == 'config' ? ' class="active"' : '' !!}><a href="/{{ route('manage.claim.claim.config') }}" role="tab">DynamicField</a></li>
</ul>