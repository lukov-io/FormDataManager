FormDataManager.window.CreateForm = function(config) {
    config = config || {};
    config.createWindow = this;
    Ext.applyIf(config,{
        title: _('formdatamanager.formdatamanager_create')
        ,url: FormDataManager.config.connectorUrl
        ,baseParams: {
            action: 'formslist/create'
        }
        ,fields: [
            {
                xtype: 'textfield',
                fieldLabel: _('formdatamanager.formname'),
                name: 'form[attributes][class]',
                anchor: '50%',
                regex: /^[a-zA-Z_\x80-\xff][a-zA-Z0-9_ \x80-\xff]*$/,
                msgTarget: "under",
                allowBlank: false,
                invalidText: _('formdatamanager.tablename_regex'),
                listeners: {
                    'change': {
                        fn:function (tf ,nv ,ov) {
                           let tableNameField = Ext.getCmp("formdatamanager-tablename");
                            tableNameField.setValue(nv);
                        }
                        ,scope:this}

                }
            },
            {
                xtype: 'hidden',
                id: "formdatamanager-tablename",
                name: 'form[attributes][table]',
                anchor: '50%',
            },
            {
                xtype: 'textfield',
                name: `form[field][0][attributes][key]`,
                fieldLabel: _('formdatamanager.fieldname'),
                description: _('formdatamanager.fieldname_desc'),
                anchor: '50%',
                regex: /[a-zA-z]/,
                msgTarget: "under",
                invalidText: _('formdatamanager.tablerow_type'),
                allowBlank: false
            },
            {
                xtype: 'combo',
                name: `form[field][0][attributes][dbtype]`,
                store: ["TINYTEXT", "TEXT", "TIMESTAMP", "INT"],
                fieldLabel: _('formdatamanager.fieldtype'),
                description: _('formdatamanager.fieldtype_desc'),
                anchor: '50%',
                allowBlank: false,
                regex: /TINYTEXT|TEXT|TIMESTAMP|INT/,
                msgTarget: "under",
                invalidText: _('formdatamanager.tablerow_type'),
                listeners: {
                    'change': {
                        fn:function (tf ,nv ,ov) {
                            let tableNameField = Ext.getCmp("formdatamanager-phptype-0");

                            switch (nv) {
                                case "TINYTEXT":
                                case "TEXT":
                                    tableNameField.setValue('string');
                                    break;
                                case "int":
                                    tableNameField.setValue('integer');
                                    break;
                                case "TIMESTAMP":
                                    tableNameField.setValue('date');
                            }
                        }
                        ,scope:this}
                }

            },
            {
                xtype: 'hidden',
                id: "formdatamanager-phptype-0",
                name: 'form[field][0][attributes][phptype]',
                anchor: '50%',
            },
        ],
        bbar: [
            {
                xtype: "button",
                text : _('formdatamanager.addbutton'),
                handler: function () {
                    let s = config.createWindow;
                    let count = (s.items.get(0).items.length - 5) / 3;
                    if (!count) {
                        count = 1;
                    }

                    s.items.get(0).add(
                        [
                            {
                                xtype: 'textfield',
                                name: `form[field][${count}][attributes][key]`,
                                fieldLabel: _('formdatamanager.fieldname'),
                                description: _('formdatamanager.fieldname_desc'),
                                anchor: '50%',
                                regex: /[a-zA-z]/,
                                msgTarget: "under",
                                invalidText: _('formdatamanager.tablerow_type'),
                                allowBlank: false,
                            },
                            {
                                xtype: 'combo',
                                name: `form[field][${count}][attributes][dbtype]`,
                                store: ["TINYTEXT", "TEXT", "TIMESTAMP", "INT"],
                                fieldLabel: _('formdatamanager.fieldtype'),
                                description: _('formdatamanager.fieldtype_desc'),
                                anchor: '50%',
                                regex: /TINYTEXT|TEXT|TIMESTAMP|INT/,
                                msgTarget: "under",
                                invalidText: _('formdatamanager.tablerow_type'),
                                allowBlank: false,
                                listeners: {
                                    'change': {
                                        fn:function (tf ,nv ,ov) {
                                            let tableNameField = Ext.getCmp("formdatamanager-phptype-" + count);
                                            console.log(tableNameField);
                                            switch (nv) {
                                                case "TINYTEXT":
                                                case "TEXT":
                                                    tableNameField.setValue('string');
                                                    break;
                                                case "int":
                                                    tableNameField.setValue('integer');
                                                    break;
                                                case "TIMESTAMP":
                                                    tableNameField.setValue('date');
                                            }
                                        }
                                        ,scope:this}
                                },
                            },
                            {
                                xtype: 'hidden',
                                id: "formdatamanager-phptype-" + count,
                                name: `form[field][${count}][attributes][phptype]`,
                                anchor: '50%',
                            }
                        ]
                    )

                    s.doLayout();
                }
            },
        ],
    });
    FormDataManager.window.CreateForm.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.window.CreateForm,MODx.Window);
Ext.reg('formdatamanager-window-formdatamanager-create',FormDataManager.window.CreateForm);