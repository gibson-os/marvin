Ext.define('GibsonOS.module.marvin.chat.View', {
    extend: 'GibsonOS.module.core.component.view.View',
    alias: ['widget.gosModuleMarvinChatView'],
    overflowY: 'auto',
    loadMask: false,
    chatId: null,
    autoReload: true,
    savedScrollTop: null,
    autoReloadDelay: 10000,
    initComponent() {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.chat.Prompt({
            chatId: me.chatId
        });

        me.callParent();

        me.on('refresh', () => {
            me.setClickEvents();
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
            '{[this.renderResponses(values.responses)]}',
            '</tpl>',
            {
                renderSystemPrompt: me.renderSystemPrompt,
                renderUserPrompt: me.renderUserPrompt,
                renderPrompt: me.renderPrompt,
                renderResponses: me.renderResponses,
            }
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
                '<div class="marvinChatFile">' +
                '<span class="marvinChatFile">' +
                '<a href="' + baseDir + 'marvin/chat/image/id/' + image.id + '/' + image.name + '" target="_blank">' + image.name + '</a>' +
                '</span>' +
                '</div>'
            ;
        });

        return html + '<div class="marvinChatMessageStatus">' + prompt.createdAt + '</div></div></div>';
    },
    renderSystemPrompt(prompt) {
        return '<div class="marvinChatSystemMessageContainer">' + this.renderPrompt(prompt) + '</div>';
    },
    renderUserPrompt(prompt) {
        console.log(prompt);
        console.log(this);
        return '<div class="marvinChatSendMessageContainer"><div class="marvinChatMessageArrow"></div>' + this.renderPrompt(prompt) + '</div>';
    },
    renderResponses(responses) {
        if (responses.length === 0) {
            return '';
        }

        let html =
            '<div class="marvinChatReceivedMessageContainer">' +
            '<div class="marvinChatMessageArrow"></div>' +
            '<div class="marvinChatMessage marvinChatAiMessageContainer">'
        ;

        Ext.iterate(responses, (response) => {
            html += '<div class="marvinChatAiMessage">';

            if (response.done === null) {
                html += '<div class="marvinChatAiMessageLoading"></div>';
            } else {
                html += '<div class="marvinChatAiMessageMinimize"></div>';
                html += '<div class="marvinChatAiMessageMaximize"></div>';
            }

            html +=
                '<h3 class="marvinChatAiMessageModel">' + response.model.name + '</h3>' +
                '<div class="marvinChatAiMessageSpacer">' + response.message + '</div>' +
                '<div class="marvinChatMessageStatus">'
            ;

            const runtimeDividers = [60, 60, 24];
            const runtimeUnits = ['s', 'm', 'h'];
            const calcRuntime = (runtime) => {
                for (let i = 0; i < runtimeDividers.length; i++) {
                    if (runtime < runtimeDividers[i]) {
                        return Math.floor(runtime) + runtimeUnits[i];
                    }

                    runtime = runtime / runtimeDividers[i];
                }
            }

            if (response.runtime !== null) {
                html +=
                    '<div class="marvinChatAiMessageRuntime"></div> ' +
                    '<span title="' + response.runtime + 's nachgedacht">' + calcRuntime(response.runtime) + '</span> | '
                ;
            }

            if (response.done !== null) {
                html += response.done;
            } else if (response.started !== null) {
                html += response.started;
            }

            html += '</div></div>';
        });

        return html + '</div></div>';
    }
});