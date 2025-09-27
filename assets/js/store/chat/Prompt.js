Ext.define('GibsonOS.module.marvin.store.chat.Prompt', {
    extend: 'GibsonOS.data.Store',
    alias: ['hcMarvinChatPromptStore'],
    autoLoad: true,
    pageSize: 100,
    proxy: {
        type: 'gosDataProxyAjax',
        url: baseDir + 'marvin/chat/prompts',
        method: 'GET'
    },
    model: 'GibsonOS.module.marvin.model.chat.Prompt'
});