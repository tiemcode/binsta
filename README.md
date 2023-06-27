# Binsta

## Table of contents:

- [Table of contents:](#table-of-contents)
  - [Requirements:](#requirements)
  - [Getting started:](#getting-started)
  - [Set up a database:](#set-up-a-database)

## Requirements:

> **note** </br>
> redbean is a composer package but that doesnt work propely so you need to download the  `rb.php` from the documentation, klik [here](https://github.com/teppokoivula/RedBeanPHP/blob/master/RedBeanPHP/rb.php) and put it in the root of the project

- Windows (including WSL), macOS or Linux
- [xampp](https://www.apachefriends.org/)
- [composer](https://getcomposer.org/)
- [redbean](https://redbeanphp.com/index.php)

## Getting started:

To get started with the project you need to first clone the repository onto your machine.

```bash
$ git clone git@bitlab.bit-academy.nl:4761ea4a-8ae6-11eb-a3da-4213e7ee7fac/2f29fa61-0ef3-11ec-a32c-4213e7ee7fac/Binsta-0a54f264-d6461f2b.git
```

After the repository has been cloned onto your device you will need to install all of the dependencies using the following command.

```bash
$ composer install
```

## Set up a database:

First, you will need to install Xampp on your machine. You can find the installation instructions for your operating system [here](https://www.apachefriends.org/). After you have installed and set up camp on your machine.
Then go to where you can input the query with SQL.

```sql
CREATE DATABASE binsta
```

You need to still make a user that can interact with the PHP code. You can do that in Xampp at User accounts, giving it all permissions for the code to work. Make the username and password what you can remember and put it in `seeder.php` and `index.php`

```php
R::setup(
    'mysql:host=localhost;dbname=binsta',
    'YOUR USERNAME',
    'YOUR PASSWORD'
);
```
When all packages are installed use this command to run the seeder.

```bash
$ php seeder.php
```

The database should now be set up and you can continue with the [Getting ](#Getting-started)Started section.
