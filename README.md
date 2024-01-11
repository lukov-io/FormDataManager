How to use
=====================
1: Install package
-----------------------------------
***
2: Create form
-----------------------------------
  1. in package page click to Forms list tab.
  2. click to create form button.
  3. fill in the fields.

        Field types correspond to the field type of the database table.
  
        TINYTEXT - short text with a maximum number of characters of 255. Suitable for name, email address and other short information.
  
        TEXT - suitable for long text, such as a description of a problem or a comment on a form.

        INT is a numeric field, suitable only for numeric information.

        DATETIME - stores the date.
***
3: Setup your handlers.
-----------------------------------

  By default there are 2 handlers available.

  To configure the functionality of the telegram handler, you need a bot token and a chat to receive notifications.
  [learn more](https://core.telegram.org/bots/features#botfather)


  To use Email you need setup SMTP on your site[learn more](https://support.modx.com/hc/en-us/articles/216947987-Configure-MODX-to-Use-an-Email-Service-Provider)

***
4: Setup CronManager.
-----------------------------------
  [setup CronManager](https://jako.github.io/CronManager/usage/)
  
  Create new cronjob in CronManager. Choose send-undelivered-requests snippet. set checked time and set active.

***
4: Usage Manager.
-----------------------------------
  Send form to /assets/components/formdatamanager/request.php. method - POST. You should send field (name="className" value="name you created form")  