# crypto-notification-telegram-bot
## What is this?
![cryptonotificationbot2](https://github.com/user-attachments/assets/98d56538-eed2-4d9e-b6e1-bcde9a6cdbd7)

The scope of this Telegram bot is to **be updated about the prices of the cryptocurrencies of your interest periodically** (every 30 minutes, for example). Every user can set a personal interval time and can set the cryptocurrencies to be updated on from those which are into the database (into the table `cryn_cryptocurrencies`). <br>
You can also choose to get the silent notifications and you can get, into the bot, the latest update about your selected cryptocurrencies (so as not to wait for the automatic notification).
<br><br>
You can try this bot hosted by me: [@CryptosNotification_bot](https://t.me/CryptosNotification_bot)

---
## Libraries and services used
I've used [composer](https://getcomposer.org/) to get the following PHP libraries:
- [telegram-bot-sdk](https://github.com/irazasyed/telegram-bot-sdk) : to have an interface for the Telegram bot API calls
- [meekrodb](https://github.com/SergeyTsalkov/meekrodb) : to have a set of function to make simple and secure database calls
<br>
To get info about cryptocurrencies state i've used the [API of Coinmarket](https://coinmarketcap.com/api/) .

---
## How to adapt this to your own server
1. You need your [Telegram bot API token](https://core.telegram.org/bots#how-do-i-create-a-bot), your [CoinmarketAPI token](https://coinmarketcap.com/api/documentation/v1/), info of the database (username, password, hostname and database name).
2. You have to upload into your server's database the file `crypto_notification_db.sql` in `config/` folder. There is also the `readme.md` file about the config info and the database tables.
3. After this you have to set the webhook of the bot ([by the `setWebhook` Telegram bot API call](https://core.telegram.org/bots/api#setwebhook)) and (eventually) insert a record into the `cryn_users` table with your data, so you can use it. But if, into the `config.json` file you set `OPEN_ACCESS_TO_BOT` to 1 you should not need this.
4. You need to set a cronjob on the file `cronjob_update.php` every 5 minutes (there are a lot of services to set your cronjobs online), to get the updates.

---
