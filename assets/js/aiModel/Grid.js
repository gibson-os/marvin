Ext.define('GibsonOS.module.marvin.aiModel.Grid', {
    extend: 'GibsonOS.module.core.component.grid.Panel',
    alias: ['widget.gosModuleMarvinAiModelGrid'],
    multiSelect: true,
    enableDrag: false,
    enableToolbar: false,
    enablePagingBar: false,
    hideHeaders: true,
    chatId: null,
    initComponent(arguments) {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.Model();

        me.callParent(arguments);
    },
    getColumns() {
        return [{
            header: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
    },
});