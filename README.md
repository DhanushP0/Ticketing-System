# CodeIgniter 4 Application Starter
📞 Call Center Management System
A robust and efficient system for managing call center operations, built with CodeIgniter 4.

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).
🚀 Features
✅ Manage calls, tickets, and customer interactions
✅ Role-based authentication (Admin, Agent, Customer)
✅ Real-time call tracking & analytics
✅ Automated ticket creation and assignment
✅ Multi-language support
✅ Reports & data export

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).
🛠️ Technologies Used
Backend: CodeIgniter 4, PHP

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.
Database: MySQL

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.
Frontend: Bootstrap, jQuery

## Installation & updates
Server: XAMPP (Local Development)

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.
🎬 Demo Video
<a href="media/1.mp4">User Page</a></br>
<a href="media/2.mp4">Admin page </a></br>
<a href="media/3.mp4">Super Admin Page</a></br>

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup
📄 License
This project is licensed under the MIT License.

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.
📧 Need Help? Contact Me!
📌 Email: dhanushp3542@gmail.com
📌 GitHub:DhanushP92922

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
