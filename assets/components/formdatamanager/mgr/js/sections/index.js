Ext.onReady(function() {
    MODx.load({ xtype: 'formdatamanager-page-home'});
});
FormDataManager.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'formdatamanager-panel-home'
            ,renderTo: 'formdatamanager-panel-home-div'
        }]
    });
    FormDataManager.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.page.Home,MODx.Component);
Ext.reg('formdatamanager-page-home',FormDataManager.page.Home);