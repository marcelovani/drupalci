// This file is used to test that the hub is working.
// See https://testingbot.com/support/getting-started/nodejs.html
// Usage:
//   HUB_URL='http://localhost:9515' BROWSER='chrome' node test.js

const webdriver = require('selenium-webdriver');

let browser = null;
let forBrowser = null;
switch (process.env.BROWSER) {
    case 'chrome':
        browser = require('selenium-webdriver/chrome');
        forBrowser = webdriver.Browser.CHROME;
        break;

    case 'firefox':
        browser = require('selenium-webdriver/firefox');
        forBrowser = webdriver.Browser.FIREFOX;
        break;

    default:
        throw "You need to specify the browser i.e. BROWSER='chrome' node hub.js";
}

const {Options} = browser;
console.log(browser);

var chromeOptions = new Options();
chromeOptions.addArguments("disable-infobars");
chromeOptions.addArguments("start-maximized");
chromeOptions.addArguments("--disable-notifications");
chromeOptions.addArguments("--auto-open-devtools-for-tabs");
chromeOptions.addArguments("--disable-gpu");
chromeOptions.addArguments("--disable-extesions");
chromeOptions.addArguments("--disable-dev-shm-usage");
chromeOptions.addArguments("--headless");
chromeOptions.addArguments('--remote-debugging-pipe');

var firefoxOptions = new Options();
firefoxOptions.addArguments("--headless");

async function runTest () {
    let driver = new webdriver.Builder()
        .usingServer(process.env.HUB_URL)
        .forBrowser(forBrowser)
        .setChromeOptions(chromeOptions)
        .setFirefoxOptions(firefoxOptions)
        .build();
    // .setChromeOptions(chromeOptions)

    await driver.get("https://www.bing.com/search");
    const inputField = await driver.findElement(webdriver.By.name("q"));
    await inputField.sendKeys("TestingBot", webdriver.Key.ENTER);
    try {
        console.log('Step 1');
        await driver.wait(webdriver.until.titleMatches(/TestingBot/i), 1000);
    } catch (e) {
        await inputField.submit();
    }
    try {
        console.log('Step 2');
        await driver.wait(webdriver.until.titleMatches(/TestingBot/i), 1000);
        console.log('Result', await driver.getTitle());
    } catch (e) {
        console.error(e);
    }
    await driver.quit();
}

runTest();