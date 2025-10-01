Ext.define('GibsonOS.module.marvin.store.chat.File', {
    extend: 'GibsonOS.data.Store',
    alias: ['hcMarvinChatPromptStore'],
    autoLoad: false,
    pageSize: 100,
    proxy: {
        type: 'memory',
    },
    model: 'GibsonOS.module.marvin.model.chat.File'
});