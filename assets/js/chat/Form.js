Ext.define('GibsonOS.module.marvin.chat.Form', {
    extend: 'GibsonOS.module.core.component.form.Panel',
    alias: ['widget.gosModuleMarvinChatForm'],
    layout: 'border',
    frame: false,
    chatId: null,
    initComponent() {
        const me = this;

        me.items = [{
            xtype: 'gosCoreComponentFormFieldTextArea',
            name: 'prompt',
            region: 'center',
            hideLabel: true
        },{
            xtype: 'button',
            text: 'Senden',
            region: 'east',
            flex: 0,
            width: 100
        }];

        me.callParent();
    }
});