/*
*This file scrapes information of webpages using
*headless browser called puppeteer
*/
const puppeteer = require('puppeteer'),
    url = 'https://www.realcanadiansuperstore.ca/Food/Pantry/Canned-%26-Jarred/Broth/plp/RCSS001008003013?sort=title-asc&productBrand=Campbell%27s';
var fs = require('fs');

(async() => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(url, {waitUntil: 'networkidle2'});

    /*
    This function will scraper the product names of the campbell soups
    */
    var products = new Array();
    while(products.length == 0) {
        products = await page.evaluate(() =>
            Array.from(document.querySelectorAll(
                'ul > li > div > div > div.product-tile__details > div.product-tile__details__info ' +
                '> h3 > a > span > span.product-name__item.product-name__item--name'))
                .map(partner => partner.innerText.trim())
        )
    }
    console.log(products);
    //fs.writeFileSync('inputFile/productSuper.txt', products.join("\n"));


    /*
    This function will scraper the product prices of the campbell soups
    */
    var prices = new Array();
    while(prices.length == 0) {
        prices = await page.evaluate(() =>
            Array.from(document.querySelectorAll(
                'ul > li > div > div > div.product-tile__details > div.product-prices.product-prices--product-tile ' +
                '> ul.selling-price-list.selling-price-list--product-tile\\,product-tile > li > span > span.price__value.selling-price-list__item__price.selling-price-list__item__price--now-price__value'))
                .map(partner => partner.innerText.trim())
        )
    }
    console.log(prices);
    //fs.writeFileSync('inputFile/priceSuper.txt', prices.join("\n"));

    /*
    This function will scraper the product images of the campbell soups
    */
    var images = new Array();
    while(images.length == 0) {
        images = await page.evaluate(() =>
            Array.from(document.querySelectorAll(
                '#site-content > div > div > div:nth-child(4) > div > div.product-grid > div.product-grid__results > div.product-grid__results__products > ul ' +
                '> li > div > div > div.product-tile__thumbnail > div.product-tile__thumbnail__image > img'))
                .map(partner => partner.src)
        )
    }
    //fs.writeFileSync('inputFile/productImages.txt', images.join("\n"));
    console.log(images);
    await browser.close();
})();

//ul > li > div > div > div.product-tile__thumbnail > div.product-tile__thumbnail__image > a > img
//ul > li > div > div > div.product-tile__thumbnail > div.product-tile__thumbnail__image > img
//#site-content > div > div > div:nth-child(4) > div > div.product-grid > div.product-grid__results > div.product-grid__results__products > ul > li:nth-child(12) > div > div > div.product-tile__thumbnail > div.product-tile__thumbnail__image > img
