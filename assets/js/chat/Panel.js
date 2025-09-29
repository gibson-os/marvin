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
        }, {
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

        me.down('gosModuleMarvinChatForm').getForm().on('actioncomplete', (form, action) => {
            const store = me.down('gosModuleMarvinChatView').getStore();

            store.getProxy().setExtraParam('id', Ext.decode(action.response.responseText).data.id);
            store.reload();
        });
    },
    setChatId(chatId) {
        const me = this;
        const modelGrid = me.down('gosModuleMarvinAiModelGrid');
        const modelGridStore = me.down('gosModuleMarvinAiModelGrid').getStore();
        const modelGridSelectionModel = modelGrid.getSelectionModel();
        const chatViewStore = me.down('gosModuleMarvinChatView').getStore();

        const waitModelLoading = () => {
            if (modelGridStore.isLoading()) {
                setTimeout(waitModelLoading, 25);
            }
        }

        me.setLoading(true);
        me.chatId = chatId

        me.down('gosModuleMarvinChatForm').getForm().findField('chatId').setValue(chatId ?? 0);
        chatViewStore.getProxy().setExtraParam('id', chatId);
        chatViewStore.removeAll();

        if (chatId === null) {
            waitModelLoading();
            modelGridSelectionModel.selectAll();
            me.setLoading(false);

            return;
        }

        modelGridSelectionModel.deselectAll();
        chatViewStore.load();

        GibsonOS.Ajax.request({
            url: baseDir + 'marvin/chat',
            method: 'GET',
            params: {
                id: chatId
            },
            success(response) {
                const data = Ext.decode(response.responseText).data;
                const selectedModels = [];

                waitModelLoading();
                data.models.forEach((model) => {
                    selectedModels.push(modelGridStore.getById(model.id));
                });

                modelGridSelectionModel.select(selectedModels);
            },
            callback() {
                me.setLoading(false);
            }
        })
    }
});