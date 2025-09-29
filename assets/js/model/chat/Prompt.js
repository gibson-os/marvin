Ext.define('GibsonOS.module.marvin.model.chat.Prompt', {
    extend: 'GibsonOS.data.Model',
    fields: [{
        name: 'id',
        type: 'int'
    }, {
        name: 'prompt',
        type: 'string'
    }, {
        name: 'createdAt',
        type: 'string'
    }, {
        name: 'responses',
        type: 'array'
    }]
});