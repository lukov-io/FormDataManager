FormDataManager.panel.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formdatamanager-panel-settings',
        config: {},
        url: FormDataManager.config.connectorUrl
        ,baseParams: {
            action: 'settings/generalSettings',
        }
        ,items: this.getFormField(),
        bbar: [
            {
                xtype: "button",
                text : "Обновить настройки",
                handler: function () {
                    let s = Ext.getCmp('formdatamanager-panel-settings');
                    s.submit();
                }
            }
        ],
        listeners: {
            'success': {
                fn: (f) => {
                    let result = f.result.object.value.split(',');
                    result = result.map(el => +el);

                    this.items.items.forEach(el => {
                        el.checked = result.includes(el.handlerId);
                    })
                },
                scope: this
            }
        }
    });
    FormDataManager.panel.Settings.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.panel.Settings, MODx.FormPanel,{
    getFormField: function () {
        let output = [];

        FormDataManager.config.handlers.forEach(el => {
            output.push({
                xtype: 'checkbox',
                fieldLabel: el.name,
                handlerId: el.id,
                checked: el.isDefault,
                description: _('formdatamanager.general_description'),
                name: `value[${el.id}]`,
            });
        })

        if (!output.length) {
            output.push({
                html: '<h3>There are no handlers.</h3>',
                border: false,
                cls: 'modx-page-header'
            })
        }

        return output;
    }

});
Ext.reg('formdatamanager-panel-settings',FormDataManager.panel.Settings);