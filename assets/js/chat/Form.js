Ext.define('GibsonOS.module.marvin.chat.Form', {
    extend: 'GibsonOS.module.core.component.form.Panel',
    alias: ['widget.gosModuleMarvinChatForm'],
    layout: 'border',
    frame: false,
    chatId: null,
    initComponent() {
        const me = this;

        me.items = [{
            xtype: 'hiddenfield',
            name: 'chatId'
        }, {
            xtype: 'gosCoreComponentFormFieldTextArea',
            name: 'prompt',
            region: 'center',
            hideLabel: true
        }, {
            xtype: 'gosModuleMarvinAiModelGrid',
            name: 'prompt',
            region: 'west',
            hideLabel: true,
            flex: 0,
            collapsible: true,
            split: true,
            width: 100,
            hideCollapseTool: true,
            title: 'Models',
            header: false,
        }, {
            xtype: 'button',
            text: 'Senden',
            region: 'east',
            flex: 0,
            width: 100,
            handler() {
                me.setLoading(true);

                const selectedModels = [];

                Ext.iterate(me.down('gosModuleMarvinAiModelGrid').getSelectionModel().getSelection(), (record) => {
                    selectedModels.push({modelId: record.getId()});
                });

                me.submit({
                    xtype: 'gosFormActionAction',
                    url: baseDir + 'marvin/chat/prompt',
                    method: 'POST',
                    params: {
                        models: Ext.encode(selectedModels)
                    },
                    success(form, action) {
                        me.getForm().findField('chatId').setValue(Ext.decode(action.response.responseText).data.id);
                        me.getForm().findField('prompt').setValue('');
                        me.setLoading(false);
                    },
                    failure() {
                        me.setLoading(false);
                    }
                });
            }
        }];

        me.callParent();
    }
});