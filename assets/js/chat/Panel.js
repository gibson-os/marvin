Ext.define('GibsonOS.module.marvin.chat.Panel', {
    extend: 'GibsonOS.module.core.component.Panel',
    alias: ['widget.gosModuleMarvinChatPanel'],
    layout: 'border',
    chatId: null,
    initComponent() {
        const me = this;

        me.items = [{
            xtype: 'gosModuleMarvinChatView',
            region: 'center',
            chatId: me.chatId
        },{
            xtype: 'gosModuleMarvinChatForm',
            flex: 0,
            region: 'south',
            collapsible: false,
            split: true,
            height: 100,
            hideCollapseTool: true,
            header: false,
            chatId: me.chatId
        }];

        me.callParent();
    }
});