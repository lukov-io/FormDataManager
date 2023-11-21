FormDataManager.panel.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formdatamanager-panel-settings-tg'
        ,url: FormDataManager.config.connectorUrl
        ,baseParams: { action: 'settings/changeSettings' }
        ,items: this.getFormField(),
        bbar: [
            {
                xtype: "button",
                text : "Добавить чат",
                handler: function () {
                    let s = Ext.getCmp('formdatamanager-panel-settings-tg');
                    let i = s.items.items.length;

                    s.add(
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Id чата ' + i,
                            name: "form[formdatamanager.chat_id][]",
                            description: 'ID чата для отпраки сообщения.',
                            regex: /(-?\d+)+$/,
                            maskRe: /[-\d\s\,]/i,
                            msgTarget: "under",
                            invalidText: 'Not a valid.  Must be in the format "123456789".',
                            width: 500,
                        }
                    )

                    s.doLayout();
                }
            },
            {
                xtype: "button",
                text : "Обновить настройки",
                handler: function () {
                    let s = Ext.getCmp('formdatamanager-panel-settings-tg');

                    for(let i of s.items.items) {
                        if (i.getValue() === '') {
                            s.remove(i);
                        }
                    }

                    s.submit();
                }
            }
        ],
    });
    FormDataManager.panel.Settings.superclass.constructor.call(this,config);
};
Ext.extend(FormDataManager.panel.Settings,Ext.FormPanel,{
    submit: function(o) {
        let fm = this.getForm();
        if (fm.isValid()) {
            o = o || {};
            o.headers = {
                'Powered-By': 'MODx'
                ,'modAuth': MODx.siteId
            };
            if (this.fireEvent('beforeSubmit',{
                form: fm
                ,options: o
                ,config: this.config
            })) {
                fm.submit({
                    waitMsg: 'saving'
                    ,scope: this
                    ,headers: o.headers
                    ,clientValidation: (o.bypassValidCheck ? false : true),
                    success: function(form, action) {
                        action.result.results.forEach(el => {
                            let i = this.getField(el.key);
                            if (i) {
                                i.setValue(el.value);
                            }
                        });
                        Ext.Msg.alert('Success', "Сохранено");
                    }
                    ,failure: function(f,a) {
                        if (this.fireEvent('failure',{
                            form: f
                            ,result: a.result
                            ,options: o
                            ,config: this.config
                        })) {
                            MODx.form.Handler.errorExt(a.result,f);
                        }
                    }
                });
            }
        } else {
            return false;
        }
        return true;
    },
    getField: function(f) {
        let fld = false;
        if (typeof f == 'string') {
            fld = this.getForm().findField(f);

            if (!fld) {
                fld = Ext.getCmp(f);
            }
        }
        return fld;
    },
    getFormField: function () {
        let output = [
            {
                xtype: 'textfield',
                fieldLabel: 'Токен',
                description: 'Токен телеграм бота',
                name: 'form[formdatamanager.token_tg]',
                width: 500,
                value: FormDataManager.record['formdatamanager.token'] ?? FormDataManager.config.apiToken,

            },
            {
                xtype: 'textfield',
                fieldLabel: 'Id чата',
                description: 'ID чата для отпраки сообщения.',
                name: 'form[formdatamanager.chat_id][]',
                regex: /(-?\d+)+$/,
                maskRe: /[-\d\s\,]/i,
                msgTarget: "under",
                invalidText: 'Not a valid.  Must be in the format "123456789".',
                width: 500,
                value: FormDataManager.record['formdatamanager.chatId'] ?? FormDataManager.config.chatId[0]
            },
        ];

        for (let i = 1; i < FormDataManager.config.chatId.length; i++) {
            output.push({
                xtype: 'textfield',
                fieldLabel: 'Id чата ' + (i + 1),
                description: 'ID чата для отпраки сообщения.',
                name: "form[formdatamanager.chat_id][]",
                regex: /(-?\d+)+$/,
                maskRe: /[-\d\s\,]/i,
                msgTarget: "under",
                invalidText: 'Not a valid.  Must be in the format "123456789".',
                width: 500,
                value: FormDataManager.config.chatId[i]
            },);
        }

        return output;
    }

});
Ext.reg('formdatamanager-panel-settings-tg',FormDataManager.panel.Settings);