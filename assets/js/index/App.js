Ext.define('GibsonOS.module.marvin.index.App', {
    extend: 'GibsonOS.App',
    alias: ['widget.gosModuleMarvinIndexApp'],
    title: 'Marvin',
    appIcon: 'icon_exe',
    width: 800,
    height: 400,
    requiredPermission: {
        module: 'marvin',
    },
    initComponent() {
        const me = this;

        me.items = [{
            xtype: 'gosModuleMarvinIndexPanel'
        }];

        me.callParent();
    }
});