Ext.define('GibsonOS.module.marvin.model.Chat', {
    extend: 'GibsonOS.data.Model',
    fields: [{
        name: 'id',
        type: 'int',
        useNull: true
    },{
        name: 'name',
        type: 'string'
    }]
});