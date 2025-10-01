Ext.define('GibsonOS.module.marvin.model.chat.File', {
    extend: 'GibsonOS.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'name',
        type: 'string'
    }, {
        name: 'file',
        type: 'object'
    }]
});