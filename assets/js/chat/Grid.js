Ext.define('GibsonOS.module.marvin.chat.Grid', {
    extend: 'GibsonOS.module.core.component.grid.Panel',
    alias: ['widget.gosModuleMarvinChatGrid'],
    multiSelect: false,
    enableDrag: false,
    enablePagingBar: false,
    hideHeaders: true,
    chatId: null,
    initComponent(arguments) {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.Chat({
            chatId: me.chatId
        });

        me.callParent(arguments);
    },
    getColumns() {
        return [{
            header: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
    },
    addFunction() {
        const store = this.getStore();
        const first = store.first();

        if (first !== undefined && first.getId() === null) {
            return;
        }

        this.getSelectionModel().select(store.insert(0, [{id: null, name: '(Neuer Chat)'}]));
    },
});