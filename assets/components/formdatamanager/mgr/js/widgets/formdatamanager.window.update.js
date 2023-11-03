FormDataManager.window.Update = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('formdatamanager.formdatamanager_update')
        ,url: FormDataManager.config.connectorUrl
        ,baseParams: {
            action: 'formslist/update'
        }
        ,fields: this.getFormFields(config.data)
    });
    FormDataManager.window.Update.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.window.Update,FormDataManager.window.CreateForm, {
    getFormFields(data) {
        let output = [
            {
                xtype: 'hidden',
                name: 'id',
                value: data.id
            },
            {
                xtype: 'textfield',
                fieldLabel: _('formdatamanager.formname'),
                name: 'form[class]',
                anchor: '50%',
                regex: /^[a-zA-Z_\x80-\xff][a-zA-Z0-9_ \x80-\xff]*$/,
                msgTarget: "under",
                value: data.formName,
                allowBlank: false,
                invalidText: _('formdatamanager.tablename_regex'),
                listeners: {
                    'change': {
                        fn:function (tf ,nv ,ov) {
                            let tableNameField = Ext.getCmp("formdatamanager-newtablename");
                            tableNameField.setValue(nv);
                        }
                        ,scope:this}

                }
            },
            {
                xtype: 'hidden',
                id: "formdatamanager-newtablename",
                name: 'form[table]',
                anchor: '50%',
            }
        ];

        for ( let i in data.fieldsMeta) {
            if (i === 'id') continue;
            output.push({
                    xtype: 'textfield',
                    name: `form[fields][]`,
                    fieldLabel: _('formdatamanager.fieldname'),
                    id: "formdatamanager-tablefield" + i,
                    description: _('formdatamanager.fieldname_desc'),
                    anchor: '50%',
                    value: i,
                    regex: /[a-zA-z]/,
                    msgTarget: "under",
                    invalidText: _('formdatamanager.tablerow_type'),
                    allowBlank: false,
                },
                {
                    xtype: "button",
                    text : _('formdatamanager.remove_field'),
                    handler: function () {
                        Ext.Msg.confirm("delete field", "test", (btn) => {
                            if (btn === "yes") {
                                let s = Ext.getCmp("formdatamanager-tablefield" + i);

                                console.log(this);
                            }
                        });
                    }
                }
            );
        }

        return output;
    }
});
Ext.reg('formdatamanager-window-formdatamanager-update',FormDataManager.window.Update);