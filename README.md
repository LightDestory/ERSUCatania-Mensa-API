# ERSUCatania-Mensa-API

A simple API to get the weekly/daily ERSUCatania Mensa menu!

---

###How it works?
Via a regex matching it looks for the current week PDF menu directly from ERSUCatania's website.

After obtaining the link, the file is downloaded and processed with a service called 'Ilovepdf' that extracts the text from the PDF using OCR technologies.

Got the raw text I clean it using regex and applying some replacements to fix problems that may occur due to typos. After that I get a line per line list of the planned dishes for the week.

Finally I can sort the dishes for each day and time.

__This process will be performed every 15 minutes when the current week menu is missing on the server.__

---

###Requirements
- PHP 7.2 >=
- Composer
- Cron
- Nginx* or Apache

*Nginx requires a particular block server to make Lumen works.

---

##Installation
1) Clone this repository on your www folder 
2) Run composer to install all the dependencies: __composer install__
3) Rename .env.example into .env and fill it with your data
4) Add this cronjob to run every minute the Lumen scheduling: __* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1__
5) You are ready to go.

---

##Usage

- The API's documentation is available inside the [documentation](./documentation) folder






