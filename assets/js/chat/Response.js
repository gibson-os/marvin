GibsonOS.define('GibsonOS.module.marvin.chat.Response', {
    renderResponsesButton(prompt, width = 800) {
        if (!prompt) {
            return '';
        }

        const responses = prompt.responses;
        
        if (!responses || responses.length === 0) {
            return '';
        }

        return '<div class="marvinButton" onclick="GibsonOS.module.marvin.chat.Response.clickButton(this);"><div class="marvinPopup" style="width: ' + width + 'px;">' + this.renderResponses(responses) + '</div></div>';
    },
    clickButton(button) {
        const marvinPopup = button.querySelector('.marvinPopup');

        if (marvinPopup.style.display === 'block') {
            marvinPopup.style.display = 'none';

            return;
        }

        marvinPopup.style.display = 'block';
    },
    renderResponses(responses) {
        if (!responses || responses.length === 0) {
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
    }
});