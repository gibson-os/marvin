Ext.define('GibsonOS.module.marvin.chat.form.FileView', {
    extend: 'GibsonOS.module.core.component.view.View',
    alias: ['widget.gosModuleMarvinChatFormFileView'],
    multiSelect: true,
    itemSelector: 'span.marvinChatFile',
    trackOver: true,
    overItemCls: 'marvinChatFileOver',
    selectedItemCls: 'marvinChatFileSelected',
    initComponent() {
        let me = this;

        me.store = new GibsonOS.module.marvin.store.chat.File();

        me.callParent();

        me.tpl = new Ext.XTemplate(
            '<tpl for=".">',
            '<span class="marvinChatFile">{name}</span>',
            '</tpl>'
        );
    }
});