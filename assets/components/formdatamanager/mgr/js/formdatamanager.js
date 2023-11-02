let FormDataManager = function(config) {
    config = config || {};
    FormDataManager.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},form:{},config: {}, record: {}
});
Ext.reg('FormDataManager',FormDataManager);
FormDataManager = new FormDataManager();