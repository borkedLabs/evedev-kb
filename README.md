# EVE Development Killboard (EDK)

EDK is a killboard application to showcase alliance, corporation and individual character killmails. 

## borkedLabs fork
This is an fork of EDK. For various reasons this fork aims to clean house of garbage in EDK. Changes from the master will be merged every once in awhile.

### Install
This fork of EDK requires usage of composer to deploy.

https://getcomposer.org


After uploading the folder to your web server.

Run
```sh
php composer.phar install --no-dev
```
in the folder.
After composer is done fetching dependencies visit the /install folder to complete setup.



### Cron Jobs
The cron jobs have been restructured to operate through a central cron.php caller instead of the original individual file mess.

You now execute them via command line by calling cron.php and with the argument as the desired command to run.

i.e.

```sh
php cron.php feed
```


You will want to setup cron jobs to run the commands so this is a suggested crontab configuration:

```sh
#hourly
1,31 * * * * php /SOMEDIRECTORY/cron.php api >/dev/null 2>&1
0,30 * * * * php /SOMEDIRECTORY/cron.php feed >/dev/null 2>&1
*/10 * * * * php /SOMEDIRECTORY/cron.php zkb >/dev/null 2>&1

#daily
0 23 * * * php /SOMEDIRECTORY/cron.php clearup

#weekly
0 23 * * 0 php /SOMEDIRECTORY/cron.php value
```