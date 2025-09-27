Ext.define('GibsonOS.module.marvin.store.Chat', {
    extend: 'GibsonOS.data.Store',
    alias: ['hcMarvinChatStore'],
    autoLoad: true,
    pageSize: 100,
    proxy: {
        type: 'gosDataProxyAjax',
        url: baseDir + 'marvin/index/chats',
        method: 'GET'
    },
    model: 'GibsonOS.module.marvin.model.Chat'
});