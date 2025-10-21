Ext.define('GibsonOS.module.marvin.chat.View', {
    extend: 'GibsonOS.module.core.component.view.View',
    alias: ['widget.gosModuleMarvinChatView'],
    overflowY: 'auto',
    loadMask: false,
    chatId: null,
    autoReload: true,
    savedScrollTop: null,
    autoReloadDelay: 10000,
    maximizedMarvinPromptItems: [],
    maximizedMarvinResponseItems: [],
    expandedThinkingResponseItems: [],
    initComponent() {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.chat.Prompt({
            chatId: me.chatId
        });

        me.callParent();

        me.on('refresh', () => {
            GibsonOS.module.marvin.chat.Response.setClickEvents(me);
            me.restoreScrollPosition();
        });

        me.getStore().on('beforeload', () => {
            me.saveScrollPosition();
        });

        me.getStore().on('load', (store, records) => {
            me.deactivateAutoReload();

            Ext.iterate(records, (record) => {
                Ext.iterate(record.get('responses'), (response) => {
                    if (response.done !== null) {
                        return true;
                    }

                    me.activateAutoReload();

                    return false;
                });
            });
        })

        me.tpl = new Ext.XTemplate(
            '<tpl for=".">',
            '<tpl if="role == \'USER\'">',
            '{[this.renderUserPrompt(values)]}',
            '<tpl else>',
            '{[this.renderSystemPrompt(values)]}',
            '</tpl>',
            '{[this.renderResponses(values)]}',
            '</tpl>',
            {
                renderSystemPrompt: me.renderSystemPrompt,
                renderUserPrompt: me.renderUserPrompt,
                renderPrompt: me.renderPrompt,
                renderResponses(prompt) {
                    return GibsonOS.module.marvin.chat.Response.renderResponses(prompt, me);
                }
            }
        );
    },
    saveScrollPosition() {
        const me = this;
        const el = me.getEl();

        if (el && el.dom) {
            me.savedScrollTop = el.dom.scrollTop;
        }
    },
    restoreScrollPosition() {
        const me = this;
        const el = me.getEl();

        if (el && el.dom && me.savedScrollTop !== null) {
            el.dom.scrollTop = me.savedScrollTop;
        }
    },
    renderPrompt(prompt) {
        let html =
            '<div class="marvinChatMessage">' +
            '<p>' + prompt.prompt + '</p>' +
            '<div class="marvinChatMessageImages">'
        ;

        Ext.iterate(prompt.images, (image) => {
            html +=
                '<span class="marvinChatFile">' +
                '<a href="' + baseDir + 'marvin/chat/image/id/' + image.id + '/' + image.name + '" target="_blank">' + image.name + '</a>' +
                '</span>'
            ;
        });

        return html + '<div class="marvinChatMessageStatus">' + prompt.createdAt + '</div></div></div>';
    },
    renderSystemPrompt(prompt) {
        return '<div class="marvinChatSystemMessageContainer">' + this.renderPrompt(prompt) + '</div>';
    },
    renderUserPrompt(prompt) {
        return '<div class="marvinChatSendMessageContainer"><div class="marvinChatMessageArrow"></div>' + this.renderPrompt(prompt) + '</div>';
    },
});