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

        me.callParent();

        me.on('refresh', () => {
            me.setClickEvents();
        });

        me.tpl = new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="marvinChatSendMessageContainer">',
            '<div class="marvinChatMessageArrow"></div>',
            '<div class="marvinChatMessage">',
            '<p>{prompt}</p>',
            '<div class="marvinChatMessageStatus">{createdAt}</div>',
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
            '<div class="marvinChatMessageStatus">',
            '<tpl if="done == null">',
            '{started}',
            '<tpl else>',
            '{done}',
            '</tpl>',
            '</div>',
            '</div>',
            '</tpl>',
            '</div>',
            '</div>',
            '</tpl>'
        );
    },
    setClickEvents() {
        const me = this;
        const receivedMessages = document.querySelectorAll('#' + me.getId() + ' .marvinChatReceivedMessageContainer .marvinChatMessage');

        Ext.iterate(receivedMessages, (message) => {
            const aiMessages = message.querySelectorAll('.marvinChatAiMessage');

            Ext.iterate(aiMessages, (aiMessage) => {
                const minimizeButton = aiMessage.querySelector('.marvinChatAiMessageMinimize');
                const maximizeButton = aiMessage.querySelector('.marvinChatAiMessageMaximize');

                if (minimizeButton === null || maximizeButton === null) {
                    return;
                }

                minimizeButton.onclick = () => {
                    minimizeButton.style.display = 'none';
                    maximizeButton.style.display = 'block';

                    if (aiMessage !== message.querySelector('.marvinChatAiMessage:last-child')) {
                        aiMessage.style.borderRight = '1px solid #AAA';
                    }

                    Ext.iterate(message.querySelectorAll('.marvinChatAiMessage'), (otherAiMessage) => {
                        otherAiMessage.style.display = 'block';
                    });
                }
                maximizeButton.onclick = () => {
                    maximizeButton.style.display = 'none';
                    minimizeButton.style.display = 'block';
                    aiMessage.style.borderRight = '0 none';

                    Ext.iterate(message.querySelectorAll('.marvinChatAiMessage'), (otherAiMessage) => {
                        if (otherAiMessage === aiMessage) {
                            return true;
                        }

                        otherAiMessage.style.display = 'none';
                    });
                }
            });
        });
    }
});