<h2 align="center">Sniffagrammers</h2>
<p align="center">
<img src="https://user-images.githubusercontent.com/6490641/232155875-ce2ea2ec-eeb5-4bcc-9af7-8c8d82887420.svg" alt="logo" style="display: block; max-width: 640px"/>
</p>
<h3 align="center">In this repo you can find Node.js and PHP files to automatically downloading pictures from instagram by <a href="https://orsi.me/sniffagram">https://orsi.me/sniffagram</a></h3>

<hr/>

## Prerequisites

Just a **web space** where to execute a Node.js script.. or PHP script; alternatively you can run the script on your computer.

_If you want to run the script automatically you need_ **Crontab installed** where you execute the code **or a free account to [crontaboo.com](https://crontaboo.com/)**.


## Open the script

Open `node/sniffagrammer.js` or `php/sniffagrammer.php`.

Edit the code according to your needs:

```js
// type can be 'tag' or 'user'
const type = 'user'

// if type is 'tag', the script will search for #orsifrancesco
// if type is 'user', the script will search for @orsifrancesco
const value = 'orsifrancesco'

// only for sniffagrammer.js
// you can change the port of the server
const port = 8080
```

## Upload the script on your web space.

If you use Node.js just run `node sniffagrammer.js` (and open the browser).

If you use PHP you can just upload `sniffagrammer.php` to your web space.


## Open the browser

You should be able to see the output on `http://yourWebSpace:8080` (or the port you set, or `http://localhost:8080` if you are running the Node.js script on your computer) or `http://yourWebSpace/sniffagrammer.php` (or `http://localhost` if you are running the PHP script on the `htdocs/` folder of your computer).

Every time you open the URL, the script will call [Sniffagram](https://orsi.me/sniffagram/) endpoint checking for new images and saving them on `images/` folder.

<img src="https://dev-to-uploads.s3.amazonaws.com/uploads/articles/wxm0we5rpaz75bqtfgzh.png" alt="JSON output" style="display: block; max-width: 640px"/>


## Check the result

On `images/{type}/` (where _type_ can be `tag` or `user`) you will see all the downloaded images.

Every time you download a new image your will find the hash ([wikipedia](https://en.wikipedia.org/wiki/Hash_function)) of the image path on `temp/{type}/`.

These files are 0 bytes, they are necessary to prevent to download the same images (_if you delete these files the images will be downloaded again_).

<img src="https://dev-to-uploads.s3.amazonaws.com/uploads/articles/swyuwdylj66wtkz8zn72.png" alt="folders" style="display: block; max-width: 640px"/>


## Download images automatically every hour

To call the URL automatically, you need to run a crontab ([wikipedia](https://en.wikipedia.org/wiki/Cron)) script. If you don't have crontab installed on your machine you can register to [crontaboo.com](https://crontaboo.com/) (_the free account gives you the possibility to call URLs every hour_).

<img src="https://dev-to-uploads.s3.amazonaws.com/uploads/articles/4yz5jnwk003fpcehif2g.png" alt="crontaboo.com" style="display: block; max-width: 640px"/>


## Advanced way to do it

[Sniffagram](https://orsi.me/sniffagram/) is an independent project gives you 30 calls a day (_enough for a call every hour_). It caches the output for 5 minutes.

_Due to the high number of requests, sometimes the token used for the project expires prematurely making the service unavailable (usually the expired token is replaced after a few minutes)._

For unlimited requests (no cache, every minute) you need to follow the tutorial from the official repo [Instagram without API](https://orsi.me/instagram-without-api/) for [Node.js](https://github.com/orsifrancesco/instagram-without-api-node) or [PHP](https://github.com/orsifrancesco/instagram-without-api).


## Resource Links

- [Sniffagram](https://orsi.me/sniffagram/)
- [GitHub Sniffagrammers](https://github.com/orsifrancesco/sniffagrammers) scripts for Node.js and PHP
- [Crontaboo](https://crontaboo.com/), simple way to call URLs without crontab
- [Instagram without API](https://github.com/orsifrancesco/instagram-without-api-node) Node.js
- [Instagram without API](https://www.npmjs.com/package/instagram-without-api-node) npm
- [Instagram without API](https://github.com/orsifrancesco/instagram-without-api) PHP
- [Instagram without API](https://orsi.me/instagram-without-api/) tutorial


## ⚖️ License

Licensed under MIT


## ☕ About

Any feedback to [@orsifrancesco](https://twitter.com/orsifrancesco) and [coffees](https://www.paypal.com/donate/?business=5EL4L2LDYVH96) are welcome :) 