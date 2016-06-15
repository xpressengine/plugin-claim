var ClaimToggleMenu = {
    storeBoard: function(e, url, from, targetId, shortCut) {
        e.preventDefault();
        var _o = $(e.target)[0];

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId, shortCut:shortCut},
            success: function (json) {
                $('body').trigger('click');
                XE.toast('info', '신고되었습니다');
            }
        });
    },
    destroyBoard: function(e, url, from, targetId) {
        e.preventDefault();
        var _o = $(e.target)[0];

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId},
            success: function (json) {
                $('body').trigger('click');
                XE.toast('info', '신고를 취소했습니다.');
            }
        });
    }
};
