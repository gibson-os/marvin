Ext.define('GibsonOS.module.marvin.chat.View', {
    extend: 'GibsonOS.module.core.component.view.View',
    alias: ['widget.gosModuleMarvinChatView'],
    chatId: null,
    initComponent() {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.chat.Prompt({
            chatId: me.chatId
        });

        me.tpl = new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="marvinChatSendMessageContainer">',
            '<div class="marvinChatMessageArrow">',
            '</div>',
            '<div class="marvinChatMessage">',
            '{prompt}',
            '<div class="marvinChatMessageStatus"></div>',
            '</div>',
            '</div>',
            '<div class="marvinChatReceivedMessageContainer">',
            '<div class="marvinChatMessageArrow">',
            '</div>',
            '<div class="marvinChatMessage">',
            '<tpl for="responses">',
            '{message}',
            '</tpl>',
            '<div class="marvinChatMessageStatus"></div>',
            '</div>',
            '</div>',
            '</tpl>'
        );

        me.callParent();
    }
});