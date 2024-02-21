FormDataManager.grid.FormDataManager = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formdatamanager-grid-formdatamanager',
        url: FormDataManager.config.connectorUrl,
        baseParams: { action: 'formslist/getList' },
        fields: ['id','formName', 'fields'],
        paging: true,
        remoteSort: true,
        columns: [{
            header: '№',
            dataIndex: 'id',
            sortable: true,
            width: 30
        },{
            header: 'Form name',
            dataIndex: 'formName',
            sortable: true,
            width: 100
        },{
            header: 'Fields',
            dataIndex: 'fields',
            sortable: true,
            width: 100
        }
        ],
        tbar: [
            {
                text: _('formdatamanager.formdatamanager_create')
                ,handler: { xtype: 'formdatamanager-window-formdatamanager-create' ,blankValues: true }
            }
        ]
    });

    FormDataManager.grid.FormDataManager.superclass.constructor.call(this,config)
};
Ext.extend(FormDataManager.grid.FormDataManager,MODx.grid.Grid,{
    dateData: {},
    search: function(tf ,nv ,ov) {
        let s = this.getStore();
        if (!arguments.length) {
            s.baseParams.query = {};
            this.getBottomToolbar().changePage(1);
            return
        }

        this.dateData[tf.name] = nv;
        s.baseParams.query = JSON.stringify(this.dateData);
        this.getBottomToolbar().changePage(1);
    },
    removeForm: function() {
        MODx.msg.confirm({
            title: _('formdatamanager.formdatamanager_remove'),
            text: _('formdatamanager.formdatamanager_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'formslist/remove',
                id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: () => {
                        let i = Ext.getCmp("formdatamanager-vtabs-forms");
                        if (i.items.getCount() <= 1) {
                            i.add({
                                title: "forms",
                                id: "formdatamanager-emptyforms",
                                html: '<h3>There are no forms. you can create them in the tab "Forms list"</h3>',
                                border: false,
                                cls: 'modx-page-header'
                            });
                        }
                        i.remove('formdatamanager-grid-' + this.menu.record.formName);
                        this.refresh();
                    },
                    scope: this
                }
            }
        })
    },
    update: function(btn,e) {
        e.preventDefault();
        let data = this.menu.record;
        let grid = this;

        MODx.Ajax.request({
            url: FormDataManager.config.connectorUrl,
            params: {
                action: 'forms/getObject',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function(r) {
                        data.handlers = r.object;

                        let updateFormDataManagerWindow = MODx.load({
                            xtype: 'formdatamanager-window-formdatamanager-update',
                            data: data,
                            listeners: {
                                'success': {
                                    fn:(e)=> {
                                        this.refresh();
                                        updateFormDataManagerWindow.destroy();
                                        updateFormDataManagerWindow = undefined;
                                    },
                                    scope:this
                                }
                            }
                        });

                        updateFormDataManagerWindow.show(e.target);
                    },
                    scope: this
                },
                failure: {
                    fn: function(r) {
                        e.record.reject();
                    },
                    scope: this
                }
            }
        });
    },
    getMenu: function() {
        return [
            {
                text: _('formdatamanager.formdatamanager_update'),
                handler: this.update
            },
            {
                text: _('formdatamanager.formdatamanager_remove'),
                handler: this.removeForm
            }
        ];
    }
});
Ext.reg('formdatamanager-grid-formdatamanager',FormDataManager.grid.FormDataManager);