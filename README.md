# Courts Based Sports Center Booking System

This package comes preconfigured as it was served for demo purposes. 

## Tested System Setup
### Computer (Development and Testing)
- Windows 10 Home Single Language 21H2 (OS Build 19044.1415)
- XAMPP 8.0.12 for Windows (64-bit)
- Cloudflare WARP for Windows (64-bit, DNS only mode)
- Firefox 95.0.2 (64-bit)
- Brave 1.33.106 (64-bit)

### Mobile
- Android 11
- Android's Private DNS provider hostname set to `one.one.one.one`
- Brave 1.33.106 (Arm64 Android 11)

## How Setup The Demo
1. Install XAMPP (or alternative of choice) that supports PHP 8.x, Apache Server, and MariaDB
2. Go and find your instance of `php.ini` file, and uncomment the line `extension=gd` by removing the `;` in front. If using XAMPP, the file will be in `C:\xampp\php\` on Windows. 
3. Open the database management panel (phpMyAdmin if using XAMPP) and create a new table called **cbscbs**
4. Download and unzip the code
5. Use your preffered code editor, and open up the `.env` file in the root directory of the folder. Change the value of the `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` if using Gmail, else also change `MAIL_HOST`, `MAIL_PORT`, `MAIL_ENCRYPTION` as needed. This is used to setup the mail server for sending the password reset email. I'd removed my details there because it was my personal mail box. 
6. Start terminal for the folder and start the server by typing `php artisan serve`. The server should start, with its address displayed in the terminal. 
7. Open the web application from the address shown in the terminal to start exploring. 

## Demo Accounts
- Manager Account `ID: demo@nescm, password: password`
- Admin Account `ID: demo@nesc, password: password`
- You may create the customer account yourself at the register page