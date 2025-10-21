GibsonOS.define('GibsonOS.module.marvin.chat.Response', {
    renderResponsesButton(prompt, width = 800) {
        if (!prompt) {
            return '';
        }

        const responses = prompt.responses;

        if (!responses || responses.length === 0) {
            return '';
        }

        return '<div class="marvinButtonContainer"><div class="marvinButton" onclick="GibsonOS.module.marvin.chat.Response.clickButton(this);"></div><div class="marvinPopup" style="width: ' + width + 'px;">' + this.renderResponses(prompt) + '</div></div>';
    },
    clickButton(button) {
        const allMarvinPopups = document.querySelectorAll('.marvinPopup');
        const marvinPopup = button.parentElement.querySelector('.marvinPopup');
        const display = marvinPopup.style.display;

        Ext.iterate(allMarvinPopups, (marvinPopup) => {
            marvinPopup.style.display = 'none';
        });

        if (display !== 'block') {
            marvinPopup.style.display = 'block';
        }
    },
    renderResponses(prompt, component = null) {
        const responses = prompt.responses;

        if (!responses || responses.length === 0) {
            return '';
        }

        let html =
            '<div class="marvinChatReceivedMessageContainer">' +
            '<div class="marvinChatMessageArrow"></div>' +
            '<div class="marvinChatMessage marvinChatAiMessageContainer" data-id="' + prompt.id + '">'
        ;

        Ext.iterate(responses, (response) => {
            if (component !== null && component.maximizedMarvinResponseItems.indexOf(response.id.toString()) !== -1) {
                html += '<div class="marvinChatAiMessage" data-id="' + response.id + '" style="border-right: 0 none;">';
            } else if (component !== null && component.maximizedMarvinPromptItems.indexOf(prompt.id.toString()) !== -1) {
                html += '<div class="marvinChatAiMessage" data-id="' + response.id + '" style="display: none;">';
            } else {
                html += '<div class="marvinChatAiMessage" data-id="' + response.id + '">';
            }

            if (response.done === null) {
                html += '<div class="marvinChatAiMessageLoading"></div>';
            } else {
                if (component !== null && component.maximizedMarvinResponseItems.indexOf(response.id.toString()) !== -1) {
                    html += '<div class="marvinChatAiMessageMinimize" style="display: block;"></div>';
                    html += '<div class="marvinChatAiMessageMaximize" style="display: none;"></div>';
                } else {
                    html += '<div class="marvinChatAiMessageMinimize"></div>';
                    html += '<div class="marvinChatAiMessageMaximize"></div>';
                }
            }

            let message = response.message;

            if (
                message.indexOf('&lt;think&gt;') !== -1 &&
                message.indexOf('&lt;/think&gt;') !== -1
            ) {
                if (component !== null && component.expandedThinkingResponseItems.indexOf(response.id.toString()) !== -1) {
                    message = message.replace('&lt;think&gt;', '<div class="marvinChatAiMessageThink" style="height: auto;"><h3>Thinking</h3>');
                } else {
                    message = message.replace('&lt;think&gt;', '<div class="marvinChatAiMessageThink"><h3>Thinking</h3>');
                }

                message = message.replace('&lt;/think&gt;', '</div>');
            }

            html +=
                '<h3 class="marvinChatAiMessageModel">' + response.model.name + '</h3>' +
                '<div class="marvinChatAiMessageSpacer">' + message + '</div>' +
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
                    '<span title="' + response.runtime + 's nachgedacht">' +
                    '<div class="marvinChatAiMessageRuntime"></div> ' + calcRuntime(response.runtime) +
                    '</span> | '
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
    },
    setClickEvents(component = null) {
        const receivedMessages = document.querySelectorAll('.marvinChatReceivedMessageContainer .marvinChatMessage');

        Ext.iterate(receivedMessages, (message) => {
            const aiMessages = message.querySelectorAll('.marvinChatAiMessage');

            Ext.iterate(aiMessages, (aiMessage) => {
                const minimizeButton = aiMessage.querySelector('.marvinChatAiMessageMinimize');
                const maximizeButton = aiMessage.querySelector('.marvinChatAiMessageMaximize');
                const thinkingBlock = aiMessage.querySelector('.marvinChatAiMessageThink');

                if (thinkingBlock !== null) {
                    thinkingBlock.onclick = () => {

                        if (thinkingBlock.style.height === 'auto') {
                            thinkingBlock.style.height = '2em';

                            if (component !== null) {
                                const index = component.expandedThinkingResponseItems.indexOf(aiMessage.dataset.id);
                                component.expandedThinkingResponseItems.splice(index, 1);
                            }
                        } else {
                            thinkingBlock.style.height = 'auto';

                            if (component !== null) {
                                component.expandedThinkingResponseItems.push(aiMessage.dataset.id);
                            }
                        }
                    };
                }

                if (minimizeButton === null || maximizeButton === null) {
                    return;
                }

                minimizeButton.onclick = () => {
                    minimizeButton.style.display = 'none';
                    maximizeButton.style.display = 'block';

                    if (component !== null) {
                        component.maximizedMarvinMessage = null;

                        const index = component.maximizedMarvinResponseItems.indexOf(aiMessage.dataset.id);

                        if (index > -1) {
                            component.maximizedMarvinPromptItems.splice(component.maximizedMarvinPromptItems.indexOf(message.dataset.id), 1);
                            component.maximizedMarvinResponseItems.splice(index, 1);
                        }
                    }

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

                    if (component !== null) {
                        component.maximizedMarvinPromptItems.push(message.dataset.id);
                        component.maximizedMarvinResponseItems.push(aiMessage.dataset.id);
                    }

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