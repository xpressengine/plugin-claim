var ClaimToggleMenu = {
    storeClaim: function(e, url, from, targetId, shortCut) {
        e.preventDefault();
        var _o = $(e.target)[0];
        let reason = prompt(XE.Lang.trans('claim::enterClaimReason'), '');
        if (reason != null) {
            XE.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {from: from, targetId: targetId, shortCut: shortCut, message: reason},
                success: function (json) {
                    $('body').trigger('click');
                    XE.toast('success', XE.Lang.trans('claim::msgClaimReceived'));
                }
            });
        }
    },
    destroyClaim: function(e, url, from, targetId) {
        e.preventDefault();
        var _o = $(e.target)[0];

        XE.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId},
            success: function (json) {
                $('body').trigger('click');
                XE.toast('warning', XE.Lang.trans('claim::msgClaimCanceled'));
            }
        });
    }
};
