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
            xtype: 'gosModuleMarvinChatFormFilePanel',
            name: 'prompt',
            region: 'south',
            header: false,
            flex: 0,
            autoHeight: true
        }, {
            xtype: 'gosModuleMarvinAiModelGrid',
            name: 'prompt',
            region: 'west',
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

                const fileViewStore = me.down('gosModuleMarvinChatFormFileView').getStore();
                const selectedModels = [];
                const xhr = new XMLHttpRequest();
                const formData = new FormData();
                const formValues = me.getForm().getValues();

                formData.append('chatId', formValues.chatId);
                formData.append('prompt', formValues.prompt);

                Ext.iterate(me.down('gosModuleMarvinAiModelGrid').getSelectionModel().getSelection(), (record) => {
                    selectedModels.push({modelId: record.getId()});
                });

                formData.append('models', Ext.encode(selectedModels));

                fileViewStore.each((file) => {
                    formData.append('files[]', file.get('file'));
                });

                xhr.open('POST', baseDir + 'marvin/chat/prompt');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.upload.onprogress = (uploadEvent) => {
                    console.log(uploadEvent);
                    console.log(files[i]);
                };
                xhr.onreadystatechange = () => {
                    if (xhr.readyState !== 4) {
                        return false;
                    }

                    const response = Ext.decode(xhr.responseText);

                    if (response.failure) {
                        let data = response.data;

                        if (!data) {
                            data = {msg: response.msg ?? 'Datei konnte nicht hochgeladen werden!'}
                        }

                        GibsonOS.MessageBox.show(data);
                    }

                    me.setLoading(false);
                };

                xhr.send(formData);
            }
        }];

        me.callParent();

        me.on('afterrender', () => {
            me.setUploadField();
        });
    },
    setUploadField() {
        const me = this;
        const element = me.getEl().dom;

        const stopEvents = (event) => {
            event.stopPropagation();
            event.preventDefault();
        };
        element.ondragover = stopEvents;
        element.ondrageleave = stopEvents;
        element.ondrop = (event) => {
            stopEvents(event);

            const fileViewStore = me.down('gosModuleMarvinChatFormFileView').getStore()

            Ext.iterate(event.dataTransfer.files, (file) => {
                fileViewStore.add({
                    name: file.name,
                    file: file,
                });
            });
            //me.updateAttachedFilesList();
        };
    }
});