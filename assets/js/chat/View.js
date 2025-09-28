Ext.define('GibsonOS.module.marvin.chat.View', {
    extend: 'GibsonOS.module.core.component.view.View',
    alias: ['widget.gosModuleMarvinChatView'],
    overflowY: 'auto',
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
            '<p>{prompt}</p>',
            '<div class="marvinChatMessageStatus"></div>',
            '</div>',
            '</div>',
            '<div class="marvinChatReceivedMessageContainer">',
            '<div class="marvinChatMessageArrow">',
            '</div>',
            '<div class="marvinChatMessage marvinChatAiMessageContainer">',
            '<tpl for="responses">',
            '<div class="marvinChatAiMessage">',
            '<tpl if="done == null">',
            '<div class="marvinChatAiMessageLoading"></div>',
            '<tpl else>',
            '<div class="marvinChatAiMessageMinimize"></div>',
            '<div class="marvinChatAiMessageMaximize"></div>',
            '</tpl>',
            '<h3 class="marvinChatAiMessageModel">{model.name}</h3>',
            '<div class="marvinChatAiMessageSpacer">',
            '{message}',
            '</div>',
            '<div class="marvinChatMessageStatus"></div>',
            '</div>',
            '</tpl>',
            '</div>',
            '</div>',
            '</tpl>'
        );

        me.callParent();
    }
});