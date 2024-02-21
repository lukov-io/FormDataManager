FormDataManager.panel.Home = function(config) {
    config = config || {};

    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [
            {
                html: '<h2>Form data manager</h2>'
                ,border: false
                ,cls: 'modx-page-header'
            },
            {
                xtype: 'modx-tabs'
                ,defaults: { border: false ,autoHeight: true }
                ,border: true
                ,items: [
                    {
                        title: 'Forms'
                        ,defaults: { autoHeight: true }
                        ,items: [
                            {
                                xtype: 'modx-vtabs',
                                id: "formdatamanager-vtabs-forms",
                                items: this.getForms(),
                            }
                        ]
                    },
                    {
                        title: 'Forms list'
                        ,defaults: { autoHeight: true }
                        ,items: [
                            {
                                xtype: 'formdatamanager-grid-formdatamanager'
                                ,cls: 'main-wrapper'
                                ,preventRender: true
                            }
                        ]
                    },
                    {
                        title: _('formdatamanager.settings')
                        ,defaults: { autoHeight: true }
                        ,items: [
                            {
                                xtype: 'modx-vtabs',
                                items: [
                                    {
                                        title: _('formdatamanager.general_settings'),
                                        xtype: 'formdatamanager-panel-settings'
                                        ,cls: 'main-wrapper'
                                        ,preventRender: true
                                    },
                                    /*{
                                        title: _('formdatamanager.email_settings'),
                                        xtype: 'crfplugin-panel-message'
                                        ,cls: 'main-wrapper'
                                        ,preventRender: true
                                    },*/
                                    {
                                        title: _('formdatamanager.tg_settings'),
                                        xtype: 'formdatamanager-panel-settings-tg'
                                        ,cls: 'main-wrapper'
                                        ,preventRender: true
                                    }
                                ]
                            }
                        ]
                    }
                ]
                ,listeners: {
                    'afterrender': function(tabPanel) {
                        tabPanel.doLayout();
                    }
                }
            }
        ]
    });
    FormDataManager.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.panel.Home, MODx.Panel, {
    getForms() {
        let output = [];

        if (Array.isArray(FormDataManager.config.classMap)) {
            output.push({
                title: "forms",
                id: "formdatamanager-emptyforms",
                html: '<h3>There are no forms. you can create them in the tab "Forms list"</h3>',
                border: false,
                cls: 'modx-page-header'
            });
            return output;
        }

        for (let i in FormDataManager.config.classMap) {
            let fields = ["id"];
            fields.push(...FormDataManager.config.classMap[i],'createdAt', 'status');

            output.push({
                title: i,
                id: 'formdatamanager-grid-' + i,
                xtype: 'formdatamanager-grid-forms',
                baseParams: {
                    action: 'forms/getList',
                    class: i,
                },
                fields: fields,
                columns: this.getColumns(i),
                cls: 'main-wrapper'
                ,preventRender: true
            });
        }

        return output;
    },
    getColumns(className) {
        let output = [
            {
                header: '№',
                dataIndex: 'id',
                sortable: true,
                width: 30
            }
        ]

        for (let i of FormDataManager.config.classMap[className]) {
            output.push({
                header: i.replace('_',' '),
                dataIndex: i,
                sortable: true,
            });
        }

        output.push(
            {
                header: _('formdatamanager.created_at'),
                dataIndex: 'createdAt',
                sortable: true,
            },
            {
                header: _('formdatamanager.status'),
                dataIndex: 'status',
                sortable: true,
                width: 30
            },
        );

        return output;
    }
});
Ext.reg('formdatamanager-panel-home',FormDataManager.panel.Home);