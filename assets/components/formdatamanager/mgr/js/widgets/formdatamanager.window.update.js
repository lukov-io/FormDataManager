FormDataManager.window.Update = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('formdatamanager.formdatamanager_update'),
        url: FormDataManager.config.connectorUrl,
        baseParams: {
            action: 'formslist/update',
            id: config.data.id
        },
        modal:true,
        fields: this.getFormFields(config.data)
    });
    FormDataManager.window.Update.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.window.Update,MODx.Window, {
    getFormFields(data) {
        let output = [];

        for ( let i of data.handlers) {
            output.push({
                xtype: 'checkbox',
                fieldLabel: i.name,
                handlerId: i.id,
                checked: i.isActive,
                description: _('formdatamanager.general_description'),
                name: `value[${i.id}]`,
            });
        }

        return output;
    }
});
Ext.reg('formdatamanager-window-formdatamanager-update',FormDataManager.window.Update);