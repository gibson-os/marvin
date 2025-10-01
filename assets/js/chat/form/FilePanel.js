Ext.define('GibsonOS.module.marvin.chat.form.FilePanel', {
    extend: 'GibsonOS.module.core.component.Panel',
    alias: ['widget.gosModuleMarvinChatFormFilePanel'],
    enableContextMenu: true,
    enableToolbar: false,
    style: 'background: transparent;',
    initComponent() {
        let me = this;

        me.viewItem = new GibsonOS.module.marvin.chat.form.FileView();
        me.items = [me.viewItem];

        me.callParent();
    },
    deleteFunction() {
        const fileView = this.down('gosModuleMarvinChatFormFileView');

        fileView.getStore().remove(fileView.getSelectionModel().getSelection());
    }
});