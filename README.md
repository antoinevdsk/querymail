Query Mail
==========

## Description

Query Mail is a simple and powerful tool that generate fancy emails for reporting, statistics or monitoring your data.

You can define your own SQL queries to generate KPI you need and then encapsulate them into a single email.

An example of email with 2 KPIs and a sample layout is provided into the project

Query Mail is written in PHP based on a popular framework called [FuelPHP](http://fuelphp.com/)

The project use a SQLite preconfigured database so there is minimal dependencies.

## Installation with docker

In order to run this project properly, the easiest way is to use the provided docker installation.

Edit the `docker/ssmtp.conf` file and set your mail server credentials.
Then simply run the `docker.sh` file at the root path of the project. This assume that you have docker installed on your machine.
This script will create a docker container for PHP and run a nginx webserver.
Add the following line in your `/etc/hosts` file : 
```
127.0.0.1 querymail
```

Then you can directly access Query Mail via http://querymail

## Other installation

If you prefer you can setup your own server infrastructure.
You just need to know that this code will work only for PHP5.3+

## Scheduling

Query mail doesn't provide any scheduling feature. In order to call Query Mail webservice at specific interval, I recommend to use a simple linux **crontab**

Query mail will show for each of your email the URL you can call using Curl

## Configuration

### Databases

Configure the list of your databases into the file `fuel/app/config/db.php`.
Add the following line of code for each database you need to add :
```
'myconnection' => array(
    'type' => 'pdo',
    'connection'  => array(
        'dsn'        => 'mysql:host=myhost;dbname=mydb',
        'username'   => 'user',
        'password'   => 'password',
    ),
    'identifier' => '`',
    'table_prefix' => '',
    'charset' => 'utf8',
    'enable_cache' => true,
    'profiling' => false,
),
```
Each connection must have its own alias and will automaticly appear in the web interface when creating a new KPI

### Email template layout

A sample html layout is provided into the project. You can create your own layout and put them into `fuel/app/views/emails/` 
Keep in mind that the template must be written with the old HTML4 style because of popular webmail restriction like Gmail, Yahoo, etc.
 
### Project

To organize your emails, you can setup your own project list. Projects list is saved into SQLite in the QMAIL_PROJECT table.
As you will see, in the basic configuration, you have only one project called _My Awesome Project_

### Email driver

If you use a specific driver to send email, you can configure it into the FuelPHP framework.
You will find more information in the [official docummentation](http://fuelphp.com/docs/packages/email/introduction.html)
