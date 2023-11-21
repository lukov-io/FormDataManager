FormDataManager.window.CreateForm = function (config) {
    config = config || {};
    let currentWindow = this;
    Ext.applyIf(config, {
        title: _('formdatamanager.formdatamanager_create'),
        url: FormDataManager.config.connectorUrl,
        baseParams: {
            action: 'formslist/create'
        },
        fields: [
            {
                xtype: 'textfield',
                fieldLabel: _('formdatamanager.formname'),
                name: 'form[class]',
                anchor: '100%',
                regex: /^[a-zA-Z_\x80-\xff][a-zA-Z0-9_ \x80-\xff]*$/,
                msgTarget: "under",
                allowBlank: false,
                invalidText: _('formdatamanager.tablename_regex'),
                listeners: {
                    'change': {
                        fn: function (tf, nv, ov) {
                            let tableNameField = Ext.getCmp("formdatamanager-tablename");
                            tableNameField.setValue(nv);
                        }
                        , scope: this
                    }

                }
            },
            {
                xtype: 'hidden',
                id: "formdatamanager-tablename",
                name: 'form[table]',
            },
            {
                xtype: 'textfield',
                name: `form[fields][]`,
                fieldLabel: _('formdatamanager.fieldname'),
                description: _('formdatamanager.fieldname_desc'),
                anchor: '45%',
                regex: /[a-zA-Z]/,
                msgTarget: "under",
                invalidText: _('formdatamanager.tablerow_type'),
                allowBlank: false,
                listeners: {
                    'change': {
                        fn: function (tf, nv, ov) {
                            let tableNameField = Ext.getCmp("ddddd");
                            let tableNameField1 = Ext.getCmp("formdatamanager-phptype-0");
                            tableNameField.name = `form[fieldmeta][${nv}][dbtype]`;
                            tableNameField1.name = `form[fieldmeta][${nv}][phptype]`;
                        }
                        , scope: this
                    }

                }
            },
            {
                xtype: 'combo',
                name: `form[fieldMeta][0][dbtype]`,
                id: "ddddd",
                store: ["TINYTEXT", "TEXT", "TIMESTAMP", "INT"],
                fieldLabel: _('formdatamanager.fieldtype'),
                description: _('formdatamanager.fieldtype_desc'),
                anchor: '45%',
                allowBlank: false,
                regex: /TINYTEXT|TEXT|TIMESTAMP|INT/,
                msgTarget: "under",
                invalidText: _('formdatamanager.tablerow_type'),
                listeners: {
                    'change': {
                        fn: function (tf, nv, ov) {
                            let tableNameField = Ext.getCmp("formdatamanager-phptype-0");

                            switch (nv) {
                                case "TINYTEXT":
                                case "TEXT":
                                    tableNameField.setValue('string');
                                    break;
                                case "INT":
                                    tableNameField.setValue('integer');
                                    break;
                                case "TIMESTAMP":
                                    tableNameField.setValue('date');
                            }
                        }
                        , scope: this
                    }
                }
            },
            {
                xtype: 'hidden',
                id: "formdatamanager-phptype-0",
                name: 'form[fieldMeta][0][phptype]',
            },
        ],
        bbar: [
            {
                xtype: "button",
                text: _('formdatamanager.addbutton'),
                handler: function () {
                    currentWindow.count = currentWindow.count ?? 1;
                    let i = currentWindow.count;

                    currentWindow.items.get(0).add(
                        [
                            {
                                xtype: 'textfield',
                                name: `form[fields][]`,
                                fieldLabel: _('formdatamanager.fieldname'),
                                description: _('formdatamanager.fieldname_desc'),
                                anchor: '45%',
                                regex: /[a-zA-Z]/,
                                msgTarget: "under",
                                invalidText: _('formdatamanager.tablerow_type'),
                                allowBlank: false,
                                listeners: {
                                    'change': {
                                        fn: function (tf, nv, ov) {
                                            let tableNameField = Ext.getCmp("formdatamanager-phptype-" + i);
                                            let tableNameField1 = Ext.getCmp("formdatamanager-dbtype-" + i);
                                            tableNameField1.name = `form[fieldmeta][${nv}][dbtype]`;
                                            tableNameField.name = `form[fieldmeta][${nv}][phptype]`;
                                        }
                                        , scope: this
                                    }

                                }
                            },
                            {
                                xtype: 'combo',
                                id: "formdatamanager-dbtype-" + i,
                                name: `form[fieldMeta][${currentWindow.count}][dbtype]`,
                                store: ["TINYTEXT", "TEXT", "TIMESTAMP", "INT"],
                                fieldLabel: _('formdatamanager.fieldtype'),
                                description: _('formdatamanager.fieldtype_desc'),
                                anchor: '45%',
                                regex: /TINYTEXT|TEXT|TIMESTAMP|INT/,
                                msgTarget: "under",
                                invalidText: _('formdatamanager.tablerow_type'),
                                allowBlank: false,
                                listeners: {
                                    'change': {
                                        fn: function (tf, nv, ov) {
                                            let tableNameField = Ext.getCmp("formdatamanager-phptype-" + i);
                                            switch (nv) {
                                                case "TINYTEXT":
                                                case "TEXT":
                                                    tableNameField.setValue('string');
                                                    break;
                                                case "INT":
                                                    tableNameField.setValue('integer');
                                                    break;
                                                case "TIMESTAMP":
                                                    tableNameField.setValue('date');
                                            }
                                        }
                                        , scope: this
                                    }
                                },
                            },
                            {
                                xtype: 'hidden',
                                id: "formdatamanager-phptype-" + i,
                                name: `form[fieldMeta][${currentWindow.count}][phptype]`,
                            }
                        ]
                    )

                    currentWindow.count++;

                    currentWindow.doLayout();
                }
            },
        ],
    });
    FormDataManager.window.CreateForm.superclass.constructor.call(this, config);

    this.on('success', function (a) {
        let i = Ext.getCmp("formdatamanager-vtabs-forms");
        let form = a.a.result.object;
        let fields = ["id"];
        fields.push(...form.fields.split(','));
        let columns = [
            {
                header: '№',
                dataIndex: 'id',
                sortable: true,
                width: 30
            }
        ]

        for (let item of fields) {
            if (item === "id") {
                continue;
            }

            columns.push({
                header: item,
                dataIndex: item,
                sortable: true,
            });
        }

        let empty = i.getItem("formdatamanager-emptyforms")

        if (empty) {
            empty.destroy();
        }

        i.add({
            title: form.formName,
            id: 'formdatamanager-grid-' + form.formName,
            xtype: 'formdatamanager-grid-forms',
            baseParams: {
                action: 'forms/getList',
                class: form.formName,
            },
            fields: fields,
            columns: columns,
            cls: 'main-wrapper'
            , preventRender: true
        });

        i.setActiveTab('formdatamanager-grid-' + form.formName);
        i.doLayout();
    });
};
Ext.extend(FormDataManager.window.CreateForm, MODx.Window);
Ext.reg('formdatamanager-window-formdatamanager-create', FormDataManager.window.CreateForm);