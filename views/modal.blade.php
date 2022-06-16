<form id="claim-modal" method="post" enctype="multipart/form-data">
    <div class="xe-modal-header">
        <h4 class="xe-modal-title" id="modalLabel">신고</h4>
    </div>
    <div class="xe-modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </div>
    <div class="xe-modal-footer">
        <button type="button" class="xe-btn xe-btn-secondary" data-dismiss="xe-modal">{{ xe_trans('xe::cancel') }}</button>
        <button type="submit" class="xe-btn xe-btn-primary xe-btn-submit">{{ xe_trans('xe::confirm') }}</button>
    </div>
</form>

<script>
    $("#claim-modal").submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        console.log(this);
        console.log(formData);

        $.ajax({
            cache : false,
            url : "{{  route('fixed.claim.store') }}",
            processData: false,
            contentType: false,
            type : 'POST',
            data : formData,
            success : function(data) {
                $(this).find('button.xe-btn-secondary').click();
            }
        });
    });
</script>
