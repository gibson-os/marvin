Ext.define('GibsonOS.module.marvin.index.Panel', {
    extend: 'GibsonOS.module.core.component.Panel',
    alias: ['widget.gosModuleMarvinIndexPanel'],
    layout: 'border',
    initComponent() {
        const me = this;

        me.items = [{
            xtype: 'gosModuleMarvinChatPanel',
            disabled: true,
            region: 'center'
        }, {
            xtype: 'gosModuleMarvinChatGrid',
            flex: 0,
            region: 'west',
            collapsible: true,
            split: true,
            width: 250,
            hideCollapseTool: true,
            title: 'Chats'
        }];

        me.callParent();

        const chatPanel = me.down('gosModuleMarvinChatPanel');
        const chatGrid = me.down('gosModuleMarvinChatGrid');

        chatGrid.on('selectionchange', (grid, selected) => {
            chatPanel.setDisabled(selected.length !== 1);

            if (selected.length === 1) {
                chatPanel.setChatId(selected[0].getId());
            }
        });
        chatGrid.getStore().on('load', () => {
            chatGrid.addFunction();
        });

        me.down('gosModuleMarvinChatForm').getForm().on('actioncomplete', (form, action) => {
            const selected = chatGrid.getSelectionModel().getSelection()[0];
            const data = Ext.decode(action.response.responseText).data;

            selected.set('id', data.id);
            selected.set('name', data.name);
        });
    }
});