# Debating Contest Software

## Project Description
The "Debating Contest Software" is an application designed for managing and organizing debating competitions. It includes features for participant management, room assignments, certificates, and more.

## Project Contributors
- **Hadi Chokr**: Maintainer, Current Lead Developer, Backend Development, Bug Fixes, Database Management, and the main point of contact for the project.

- **Henrik Staschen**: Frontend Development, UI Design, Integration of Backend and Frontend & Former Lead Developer

**Logo in vorlage.odt belongs to Muhammad Adnan.
(https://www.vecteezy.com/members/flatart)**

## License

This project is licensed under the **GNU General Public License Version 3 (GPL-3.0)**. You are free to use, modify, and distribute the software, provided you comply with the terms of the license.

### Terms of the GPL-3.0:

- You may modify and redistribute the source code as long as you keep the same license.
- When distributing the software or modifications, the license information and the complete license document must be included.
- If you modify and release the project, you must ensure the source code remains accessible.

The full license is available in the project directory under `LICENSE`.


# Installation and Setup Guide

## Install

### 1. Git clone this repository.

### 2. Database-Credentials

Open the `config/config_pdo.php` file and update the database credentials (host, username, password, database name) to match your setup.

### 3. Create the Database.

Use the `debating.sql` file to set up the database structure. You can do this with a tool like phpMyAdmin or using the command line:

```bash
mysql -u username -p database_name < path/to/debating.sql
```

## Setup

### 1. Logging in with the Root Account
Please perform these steps **locally** until the guide specifies otherwise. Using a universal root account in a production environment is a bad practice.

Username: root
<br>
Password: root

- Open your browser and navigate to `index.php`.
- Click on the **Login** button.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/index.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/index.jpeg" width="60%"></a>
  </p>
</p>

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/login.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/login.jpeg" width="60%"></a>
  </p>
</p>

- You will see two buttons: one for starting a marksheet and another for accessing the Admin area.
- Click the **Admin** button and log in with the temporary root credentials like earlier.


<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/home.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/home.jpeg" width="60%"></a>
  </p>
</p>


### 2. Admin Panel Overview
Once logged into the Admin panel, you will have multiple options. The most important features are available in the navigation/top bar.


<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/adminpanel.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/adminpanel.jpeg" width="60%"></a>
  </p>
</p>


### 3. Setting Up the Admin Tool
- Click on **Admintool**.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/admintool.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/admintool.jpeg" width="60%"></a>
  </p>
</p>


- Set the **School Name** for the start page and certificates. This should be the name of the school hosting the contest.
- Set the **date**, too.
- Add the **rooms** for the contest.

### 4. Creating an Admin User
- Click **Home** in the downbar to return to the main menu.
- In the navigation bar, click **Signup** to create a new Admin user.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/signup.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/signup.jpeg" width="60%"></a>
  </p>
</p>

- Make an Admin account (and additional accounts if needed).
- **Important**: Once you have at least one other Admin account, remove the root user from the `users` and `users_admin` tables. If you have done that, you can go online.

### 5. Adding Teams and Players
- To add teams, click **Home** and select **New Team** from the **Admin Navbar**. Then, return to the **Admin Navbar** and press **New Speaker** to add players. You can always add more teams or players later, but for now, your setup is complete.


<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/newteam.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/newteam.jpeg" width="60%"></a>
  </p>
</p>
<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/newspeaker.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/newspeaker.jpeg" width="60%"></a>
  </p>
</p>

---

### Using the Marksheet
- Regular users should click the **Marksheet** button on the home page.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/home.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/home.jpeg" width="60%"></a>
  </p>
</p>


- Fill out the initial information on the marksheet.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/startmarksheet.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/startmarksheet.jpeg" width="60%"></a>
  </p>
</p>


- Then, fill in the results. Note that the **winner team** and **best speaker** fields only matter if there is a tie but still enter them for a reminder.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/marksheet.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/marksheet.jpeg" width="60%"></a>
  </p>
</p>


### 6. Confirming the Results
- You will be taken to a confirmation screen displaying the results. If everything is correct, submit it.
- **Note**: Remember to write down the verification number, including the slashes, so admins can check and edit the result if needed.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/confirmmarksheet.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/confirmmarksheet.jpeg" width="60%"></a>
  </p>
</p>

- After submitting, you can either return to the Home page or create another marksheet.


<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/endmarksheet.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/endmarksheet.jpeg" width="60%"></a>
  </p>
</p>


---

### 7. Generating Certificates
- At the end of the contest, go to the Admin panel and click on **Certificates**.
- Certificates will be generated for the top 3 teams (per player per league) and the top 3 best speakers (per league). Simply click the download link.

<p align="center">
  <!-- Really ugly workaround, but the image isn't working in crates.io without this -->
  <a href="https://github.com/silverhadch/Debating-Opensource/blob/master/images/certificates.jpeg"><img alt="index" src="https://github.com/silverhadch/Debating-Opensource/blob/master/images/certificates.jpeg" width="60%"></a>
  </p>
</p>



## Disclaimer

THE SOFTWARE IS PROVIDED "AS IS," WITHOUT ANY WARRANTY, WHETHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. REFER TO THE GPL-3.0 LICENSE FOR MORE DETAILS.


