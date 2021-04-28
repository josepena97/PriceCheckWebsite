/*
*This file scrapes information of webpages using
*headless browser called puppeteer
*/
const puppeteer = require('puppeteer'),
    url = 'https://shop.saveonfoods.com/store/A8931118/?_ga=2.134607602.2014665139.1585878612-229336869.1585197564/#/category/576,654,882/broth/1?queries=fq%3Dbrand%253ACampbell%2527s%26sort%3DBrand';
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
                '#content > div > div.main > ul > li > div.product__itemContent ' +
                '> div.productInfo.productDetails > hgroup > h3'))
                .map(partner => partner.innerText.trim())
        )
    }

    //fs.writeFileSync('inputFile/productSaveOns.txt', products.join("\n"));
    console.log(products);

    /*
    This function will scraper the product prices of the campbell soups
    */
    var prices = new Array();
    while(prices.length == 0) {
        prices = await page.evaluate(() =>
            Array.from(document.querySelectorAll(
                '#content > div > div.main > ul > li > div.product__itemContent ' +
                '> div.priceInfo > span.priceInfo__price.priceInfo__price--current'))
                .map(partner => partner.innerText.trim())
        )
    }

    //fs.writeFileSync('inputFile/priceSaveOns.txt', prices.join("\n"));
    console.log(prices);

    await browser.close();
})();
