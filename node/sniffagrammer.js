const https = require('https');
const fs = require('fs');

const username = 'orsifrancesco'; // <-- Change this to your Instagram username 

function checksumJs(str, seed = 0) {
  let h1 = 0xdeadbeef ^ seed, h2 = 0x41c6ce57 ^ seed;
  for (let i = 0, ch; i < str.length; i++) {
    ch = str.charCodeAt(i);
    h1 = Math.imul(h1 ^ ch, 2654435761);
    h2 = Math.imul(h2 ^ ch, 1597334677);
  }
  h1 = Math.imul(h1 ^ (h1 >>> 16), 2246822507);
  h1 ^= Math.imul(h2 ^ (h2 >>> 13), 3266489909);
  h2 = Math.imul(h2 ^ (h2 >>> 16), 2246822507);
  h2 ^= Math.imul(h1 ^ (h1 >>> 13), 3266489909);
  return 4294967296 * (2097151 & h2) + (h1 >>> 0);
}

async function downloadImg({ url, imagesFolder }) {
  return new Promise((resolve, reject) => {
    const checksum = `temp/${username}/${checksumJs(url)}`;
    const checksumExist = fs.existsSync(checksum);
    if (!checksumExist) {
      https.get(url, (res) => {
        let fileData = '';
        res.setEncoding('binary');
        res.on('data', (chunk) => {
          fileData += chunk;
        });
        res.on('end', () => {
          let fileName = url.split('?')[0];
          fileName = fileName.split('/');
          filename = fileName[fileName.length - 1];
          const uniqueName = filename;
          fs.writeFileSync(`${imagesFolder}/${Date.now()}_${uniqueName}`, fileData, 'binary');
          fs.writeFileSync(checksum, '');
          resolve({
            checksum,
            url,
            fileName: uniqueName,
          })
        });
        res.on('error', (err) => {
          console.log({ err })
          reject(null);
        });
      });
    }
  })
}

async function getImages() {

  const imagesFolder = `images/${username}`;

  const options = {
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
    },
  };

  return new Promise((resolve, reject) => {

    https.get(`https://orsi.me/sniffagram/?user=${username}`, options, (res) => {

      let inputUrl = '';
      res.on('data', (chunk) => {
        inputUrl += chunk;
      });
      res.on('end', async () => {
        const input = inputUrl ? JSON.parse(inputUrl) : { data: [] };
        const inputData = input && input.data ? input : { data: [] };

        if (!fs.existsSync('temp')) fs.mkdirSync('temp', { recursive: true });
        if (!fs.existsSync(`temp/${username}`)) fs.mkdirSync(`temp/${username}`, { recursive: true });
        if (!fs.existsSync('images')) fs.mkdirSync('images', { recursive: true });
        if (!fs.existsSync(imagesFolder)) fs.mkdirSync(imagesFolder, { recursive: true });

        const output = {
          username,
          http_response_header: {
            status: res.statusCode,
            ...res.headers
          },
          timestamp: Date.now()
        };
        if (inputData.remainingDailyRequests) output.remainingDailyRequests = inputData.remainingDailyRequests;

        const newImagesDownloadedMapped = await Promise.all([...inputData?.data]?.map(async (el) => {
          const url = el.imageUrl;
          const checksum = `temp/${username}/${checksumJs(url)}`;
          const checksumExist = fs.existsSync(checksum);
          if (!checksumExist) return await downloadImg({ url, imagesFolder })
          return null;
        }));

        const newImagesDownloaded = newImagesDownloadedMapped.filter(el => el !== null);
        output.newImagesDownloaded = newImagesDownloaded;

        fs.readdir(imagesFolder, (err, files) => {
          if (err) reject(err)
          output.totalImagesDownloaded = files.length
          const jsonOutput = JSON.stringify(output, null, 2);
          console.log(jsonOutput);
          resolve(jsonOutput)
        });

      });
      res.on('error', (err) => {
        console.log({ err })
        reject(err);
      });
    });

  });

}

var http = require('http');

http.createServer(async (req, res) => {
  if (req.url != '/favicon.ico') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    // res.write('Hello World!');
    const response = await getImages();
    res.end(response);
  }
}).listen(8080); 