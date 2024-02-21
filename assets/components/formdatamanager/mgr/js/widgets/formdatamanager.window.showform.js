FormDataManager.window.showForm = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('formdatamanager.formdatamanager_create'),
        modal:true,
        fields: this.getFields(config.data),
        buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
        }]
    });
    FormDataManager.window.showForm.superclass.constructor.call(this, config);

    this.on('render', function (a) {
        this.maximize();
    });
};
Ext.extend(FormDataManager.window.showForm, MODx.Window,{
    getFields(data) {
        let output = [];
        for(let i in data) {
            if (data[i].length > 120) {
                output.push({
                    xtype: 'textarea',
                    fieldLabel: i,
                    value: data[i],
                    disabled: true
                });
                continue;
            }
            output.push({
                xtype: 'textfield',
                fieldLabel: i,
                value: data[i],
                disabled: true
            })
        }

        return output;
    }
});
Ext.reg('formdatamanager-window-formdatamanager-show', FormDataManager.window.showForm);