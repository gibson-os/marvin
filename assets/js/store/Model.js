Ext.define('GibsonOS.module.marvin.store.Model', {
    extend: 'GibsonOS.data.Store',
    alias: ['hcMarvinModelStore'],
    autoLoad: true,
    pageSize: 100,
    proxy: {
        type: 'gosDataProxyAjax',
        url: baseDir + 'marvin/index/models',
        method: 'GET'
    },
    model: 'GibsonOS.module.marvin.model.Model'
});