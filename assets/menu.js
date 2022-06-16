var ClaimToggleMenu = {
    storeBoard: function(e, url, from, targetId, shortCut) {
        e.preventDefault();
        var _o = $(e.target)[0];

        XE.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {from: from, targetId:targetId, shortCut:shortCut},
            success: function (json) {
                $('body').trigger('click');
                XE.toast('success', XE.Lang.trans('claim::msgClaimReceived'));
            }
        });
    },
    destroyBoard: function(e, url, from, targetId) {
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
    },
  openModal: function(e, url, from, targetId, shortCut) {
      e.preventDefault();

      XE.pageModal(url, {
        data: {
          from: from,
          targetId:targetId,
          shortCut: shortCut
        },
        type: 'get'
      });
  },
};
