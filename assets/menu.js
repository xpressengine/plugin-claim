var ClaimToggleMenu = {
    storeBoard: function(o, url, from, targetId, shortCut) {
        var _o = $(o.target)[0];

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId, shortCut:shortCut},
            success: function (json) {
                XE.toast('info', '신고되었습니다');
            }
        });
    },
    destroyBoard: function(o, url, from, targetId) {
        var _o = $(o.target)[0];

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId},
            success: function (json) {
                XE.toast('info', '신고를 취소했습니다.');
            }
        });
    }
};
