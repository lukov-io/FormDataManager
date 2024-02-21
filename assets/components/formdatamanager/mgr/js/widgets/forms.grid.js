FormDataManager.grid.Forms = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: FormDataManager.config.connectorUrl
        ,baseParams: { action: 'forms/getList' }
        ,fields: ['id']
        ,paging: true
        ,class: ""
        ,disableContextMenuAction: true
        ,remoteSort: true
        ,columns: [{
            header: '№'
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        }],
        tbar:[
            {
                xtype: 'label',
                html: 'from',
            },
            {
                xtype: 'xdatetime'
                ,name: 'from'
                ,id: 'formdatamanager-date-from-' + config.baseParams.class
                ,allowBlank: true
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,startDay: parseInt(MODx.config.manager_week_start)
                ,timeWidth: 150
                ,offset_time: MODx.config.server_offset_time
                ,listeners: {
                    'change': {
                        fn:this.search,
                        scope:this
                    },
                    'render': {
                        fn: function(cmp) {
                            new Ext.KeyMap(cmp.getEl(), {
                                key: Ext.EventObject.ENTER
                                ,fn: function() {
                                    this.fireEvent('change',this);
                                    this.blur();
                                    return true;
                                }
                                ,scope: cmp
                            });
                        },
                        scope: this
                    }
                }
            },
            {
                xtype       : 'label',
                html        : 'To',
            },
            {
                xtype: 'xdatetime'
                ,name: 'to'
                ,id: 'formdatamanager-date-to-' + config.baseParams.class
                ,allowBlank: true
                ,dateFormat: MODx.config.manager_date_format
                ,timeFormat: MODx.config.manager_time_format
                ,startDay: parseInt(MODx.config.manager_week_start)
                ,timeWidth: 150
                ,offset_time: MODx.config.server_offset_time
                ,listeners: {
                    'change': {
                        fn:this.search,
                        scope:this
                    },
                    'render': {
                        fn: function(cmp) {
                            new Ext.KeyMap(cmp.getEl(), {
                                key: Ext.EventObject.ENTER
                                ,fn: function() {
                                    this.fireEvent('change',this);
                                    this.blur();
                                    return true;
                                }
                                ,scope: cmp
                            });
                        },
                        scope: this
                    }
                }
            },
            {
                xtype: 'button',
                text : 'Reset',
                handler: function () {
                    Ext.getCmp('formdatamanager-date-from-' + config.baseParams.class).reset();
                    Ext.getCmp('formdatamanager-date-to-' + config.baseParams.class).reset();
                    this.dateData = {};
                    this.search();
                }
            }
        ]
    });
    FormDataManager.grid.Forms.superclass.constructor.call(this,config)
};
Ext.extend(FormDataManager.grid.Forms,FormDataManager.grid.FormDataManager,{
    showForm: function (btn,e) {
        let updateFormDataManagerWindow = MODx.load({
            xtype: 'formdatamanager-window-formdatamanager-show',
            data: this.menu.record,
            closeAction: "close"
        });

        updateFormDataManagerWindow.show(e.target);
    },
    getMenu: function() {
        return [
            {
                text: _('formdatamanager.formdatamanager_update'),
                handler: this.showForm
            },
        ];
    }
});
Ext.reg('formdatamanager-grid-forms',FormDataManager.grid.Forms);