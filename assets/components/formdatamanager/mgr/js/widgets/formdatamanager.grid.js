FormDataManager.grid.FormDataManager = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formdatamanager-grid-formdatamanager'
        ,url: FormDataManager.config.connectorUrl
        ,baseParams: { action: 'formslist/getList' }
        ,fields: ['id','formName', 'fields']
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: '№'
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: 'Form name'
            ,dataIndex: 'formName'
            ,sortable: true
            ,width: 100
        },{
            header: 'Fields'
            ,dataIndex: 'fields'
            ,sortable: true
            ,width: 100
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
    remove: function() {
        MODx.msg.confirm({
            title: _('formdatamanager.formdatamanager_remove')
            , text: _('formdatamanager.formdatamanager_remove_confirm')
            , url: this.config.url
            , params: {
                action: 'formslist/remove'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        })
    },
    update: function(btn,e) {
        e.preventDefault();
        let data = this.menu.record;
        let grid = this;

        MODx.Ajax.request({
            url: FormDataManager.config.connectorUrl
            ,params: {
                action: 'forms/getObject'
                ,class: this.menu.record.formName
            }
            ,listeners: {
                success: {
                    fn: function(r) {
                        data.fieldsMeta = r.object;
                        if (!grid.updateFormDataManagerWindow) {
                            grid.updateFormDataManagerWindow = MODx.load({
                                xtype: 'formdatamanager-window-formdatamanager-update',
                                data: data,
                                listeners: {
                                    'success': {fn:this.refresh,scope:this}
                                }
                            });
                        }
                        grid.updateFormDataManagerWindow.show(e.target);
                    }
                    ,scope: this
                }
                ,failure: {
                    fn: function(r) {
                        e.record.reject();
                    }
                    ,scope: this
                }
            }
        });
    },
    getMenu: function() {
    return [{
            text: _('formdatamanager.formdatamanager_update')
            ,handler: this.update
        },'-',{
            text: _('formdatamanager.formdatamanager_remove')
            ,handler: this.remove
        }];
}
});
Ext.reg('formdatamanager-grid-formdatamanager',FormDataManager.grid.FormDataManager);