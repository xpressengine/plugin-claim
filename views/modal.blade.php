


<form id="claim-modal" class="skin-poly-modal" method="post" enctype="multipart/form-data">
    <div class="xe-modal-header skin-modal-header">
        <h2 class="xe-modal-title skin-modal-title" id="modalLabel">신고하기</h2>
        <div class="skin-modal-desc">신고는 반대 의견을 표시하는 기능이 아닙니다.</div>
    </div>
    <div class="xe-modal-body skin-modal-body">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="skin-modal-sub">신고사유<span class="skin-modal-desc">여러 사유에 해당하는 경우 대표적인 사유 1개 선택</span></div>
        
        <div class="skin-modal-content-toggle-wrap">
            <label>
                <input name="claim_type" type="radio" value="1" />
                회원 분란 유도
            </label>
            <label>
                <input name="claim_type" type="radio" value="1" />
                허위사실 유포
            </label>
            <label>
                <input name="claim_type" type="radio" value="1" />
                욕설/반말북적절한 언어
            </label>
            <label>
                <input name="claim_type" type="radio" value="1" />
                음란성/선정성
            </label>
            <label>
                <input name="claim_type" type="radio" value="1" />
                영리목적/홍보상
            </label>
        </div>
        <textarea name="calim_description" maxLength="300" placeholder="신고사유 설명이 추가로 필요하실 경우에만 작성해주세요.\n(최대 300자 이내로 작성해주세요.)" value="">
        </textarea>
        
        <div class="skin-modal-content-alert">
            <ul>
                <li>허위 신고의 경우, 신고자의 서비스 활동이 제한될 수 있으니 신중하게 신고해주세요.</li>
                <li>권리침해/저작권위방 등은 권리침해 신고센터를 통해 문의해주세요.</li>
            </ul>
        </div>
    </div>
    <div class="xe-modal-footer skin-modal-footer">
        <button type="button" class="xe-btn xe-btn-secondary skin-modal-btn skin-modal-btn--cancle" data-dismiss="xe-modal">{{ xe_trans('xe::cancel') }}</button>
        <button type="submit" class="xe-btn xe-btn-primary skin-modal-btn skin-modal-btn--submit xe-btn-submit">{{ xe_trans('xe::confirm') }}</button>
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

    var textAreas = $('#claim-modal textarea');
    Array.prototype.forEach.call(textAreas, function(elem) {
        elem.placeholder = elem.placeholder.replace(/\\n/g, '\n');
    });

</script>
<style>
    .skin-poly-modal * {
        font-family: 'Pretendard';
        color: white;
    }
   
    .skin-poly-modal .skin-modal-header {
        background: #333;
        border-bottom: none;
        padding: 20px 20px 10px 20px;
    }
    .skin-poly-modal .skin-modal-title {
        font-weight: 700;
        font-size: 24px;
    }
    .skin-poly-modal .skin-modal-body {
        background: #333;
        padding: 10px 20px;
    }
    .skin-poly-modal .skin-modal-footer {
        border-top: none;
        background: #333;
        padding: 10px 20px 20px 20px;
    }
    .skin-poly-modal .skin-modal-btn {
        border: none;
    }
    .skin-poly-modal .skin-modal-btn--cancle {
        background: #dcdde0;
        color: #777;
    }
    .skin-poly-modal .skin-modal-btn--submit {
        background: #9670ff;
    }
    .skin-poly-modal .skin-modal-btn--submit:hover {
        background: #6A3DCB;
    }

    .skin-poly-modal .skin-modal-sub {
        display: flex;
        font-size: 16px;
        font-weight: 700;
        column-gap: 6px;
        padding-bottom: 10px;
        align-items: flex-end;
    }

    .skin-poly-modal .skin-modal-desc {
        font-size: 12px;
        color: #ccc;
    }

    .skin-poly-modal .skin-modal-content-alert {
        background: #4d4d4d;
        color: white;
        padding: 15px;
        margin: 10px 0;
    }

    .skin-poly-modal .skin-modal-content-toggle-wrap {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        row-gap: 6px;
        padding-bottom: 10px;
    }

    .skin-poly-modal .skin-modal-content-toggle-wrap label {
        width: 50%;
    }

    .skin-poly-modal input[type="radio"] {
        accent-color: #854CFE;
        width: 16px;
        height: 16px;
    }

    .skin-poly-modal label {
        display: flex;
        align-items: center;
        column-gap: 6px;
        color: #ccc;
    }

    .skin-poly-modal ul {
        list-style: disc;
        padding-left: 15px;
    }
    .skin-poly-modal ul li {
        padding-bottom: 6px;
    }
    .skin-poly-modal textarea {
        background: #4d4d4d;
        width: 100%;
        height: 150px;
        padding: 10px;
    }
</style>