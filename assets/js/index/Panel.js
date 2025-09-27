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
        },{
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
            chatPanel.setDisabled(selected.length === 0);
        });
        chatGrid.getStore().on('load', () => {
            chatGrid.addFunction();
        });
    }
});