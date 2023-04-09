
const crypto = require('crypto');

// console.log(crypto.randomBytes(8).toString("ASCII"))
// const compare = require('tsscmp')
// supported hashes
// console.log(crypto.getHashes());
// console.log(crypto.getCiphers());

var ObjectId = () => {
    const h = 16;
    const d = Date;
    const m = Math;
    const s = s => m.floor(s).toString(h);

    const secondsInHex = s(d.now() / 1000);
    const randomHex = ' '.repeat(h).replace(/./g, () => s(m.random() * h))
    const id = secondsInHex + randomHex;
    return id;
}


var replacedBy = () => Math.floor(Math.random() * 16).toString(16);
var randomHex = " ".repeat(16).replace(/\s+/g, replacedBy);
console.log(randomHex)

const mongoDbId = () => {
    var secondsInHex = Math.floor(Date.now()).toString(16);
    var replacedBy = () => Math.floor(Math.random() * 16).toString(16);
    var randomHex = " ".repeat(16).replace(/./g, replacedBy);
    console.log(randomHex)
    var id = secondsInHex + randomHex;
    console.log(id);
}

var EQUAL_GLOBAL_REGEXP = /=/g
var PLUS_GLOBAL_REGEXP = /\+/g
var SLASH_GLOBAL_REGEXP = /\//g

const str = "password";
const secret = "server secret";

const hmac = crypto
    .createHmac('md5', secret)
    .update(str)
    .digest('base64')
    .replace(PLUS_GLOBAL_REGEXP, '-')
    .replace(SLASH_GLOBAL_REGEXP, '_')
    .replace(EQUAL_GLOBAL_REGEXP, '');

// md5
// (22Chars) base64
// 0wSEOHfIUpr3yGNZNp4WInvgJgI
// (32Chars) hex
// 9802ea813f8d05928fd3da97ff5882bd

// Sha1
// (27Chars) base64
// 0wSEOHfIUpr3yGNZNp4WInvgJgI
// (40Chars) hex
// d304843877c8529af7c86359369e16227be02602

// Sha256
// (43Chars) base64
// o3wVI8SdMzSe-t1UCBnfpjVuzpujtTIc3KkejC4lkfw
// (64Chars) hex
// a37c1523c49d33349efadd540819dfa6356ece9ba3b5321cdca91e8c2e2591fc
// console.log("hmac", hmac)

const hash = crypto
    .createHash('md5')
    .update(str)
    .digest('hex')
    .replace(PLUS_GLOBAL_REGEXP, '-')
    .replace(SLASH_GLOBAL_REGEXP, '_')
    .replace(EQUAL_GLOBAL_REGEXP, '')

console.log("hash", hash)

// md5
// (22Chars) base64
// X03MO1qnZdYdgyfeuILPmQ
// (32Chars) hex
// 5f4dcc3b5aa765d61d8327deb882cf99

// Sha1
// (27Chars) base64
// W6ph5Mm5Pz8GgiULbPgzG37mj9g
// (40Chars) hex
// 5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8

// Sha256
// (43Chars) base64
// XohImNooBHFR0OVvjcYpJ3NgPQ1qq73WKhHvch0VQtg
// (64Chars) hex
// 5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8
// console.log("hmac", hmac)

/*
// csrf
var EQUAL_GLOBAL_REGEXP = /=/g
var PLUS_GLOBAL_REGEXP = /\+/g
var SLASH_GLOBAL_REGEXP = /\//g

function csrf(salt, secret) {
    const str = salt + "-" + secret;
    const token = crypto
        .createHash("sha1")
        .update(str)
        .digest("base64")
        .replace(PLUS_GLOBAL_REGEXP, "-")
        .replace(SLASH_GLOBAL_REGEXP, "_")
        .replace(EQUAL_GLOBAL_REGEXP, "")
    const csrfToken = salt + "-" + token;
    console.log("csrf", csrfToken)
    return csrfToken
}

csrf("uGsGPsad", "yZkArEVx414IKVoEK9nAem8g")
// output
// uGsGPsad-3O4wdAWCh6BvZN3SOUf1IBXbPlU

function verifyCsrf(csrfToken, secret) {
    var salt = csrfToken.substr(0, csrfToken.indexOf('-'))
    console.log("salt", salt)
    var expected = csrf(salt, secret)
    console.log("expected", expected)


    const isValidCsrfToken = csrfToken == expected ? true : false;
    console.log(isValidCsrfToken);
    return isValidCsrfToken;
}

verifyCsrf("uGsGPsad-3O4wdAWCh6BvZN3SOUf1IBXbPlU", "yZkArEVx414IKVoEK9nAem8g")
*/



/*
// cookies
const sessionId = "Nt_hdDEMqqYU6tj8rUX7FF4GKmhVzV6i";
const secret = "this secret is set in app.js";

function sign(val, secret) {
    const value = val;
    const token = crypto
        .createHmac('sha256', secret)
        .update(val)
        .digest('base64')
        .replace(/\=+$/, '');

    const cookie = value + "." + token;
    console.log(cookie)
    return cookie;
};

sign("Nt_hdDEMqqYU6tj8rUX7FF4GKmhVzV6i", "this secret is set in app.js")
//  output
// Nt_hdDEMqqYU6tj8rUX7FF4GKmhVzV6i.r3qovM0hDI/NZpxYj64e1uTRFuZ7QFfq6BSMYhRmh5g

function unsign(val, secret) {
    console.log("val", val)
    console.log("secret", secret)
    var str = val.slice(0, val.lastIndexOf('.'))
    console.log("str ", str)
    var mac = sign(str, secret);
    console.log("mac ", mac)
    const sha1OfMac = sha1(mac);
    console.log("sha1OfMac ", sha1OfMac)
    const sha1OfVal = sha1(val);
    console.log("sha1OfVal ", sha1OfVal)

    const isValidCookie = sha1OfMac == sha1OfVal ? true : false;
    console.log(isValidCookie);
    return isValidCookie
};

unsign("Nt_hdDEMqqYU6tj8rUX7FF4GKmhVzV6i.r3qovM0hDI/NZpxYj64e1uTRFuZ7QFfq6BSMYhRmh5g", "this secret is set in app.js")


function sha1(str) {
    const hashed = crypto.createHash('sha1').update(str).digest('hex');
    return hashed;
}

*/



// const y = [" ", "\\", "\n", "\r", "\e", "\t", "\v", "\f", "\377"];
// console.log(y)
// var x = "marko\\\\\\'conner";
// var regex = /\\/gm;
// console.log(x.replace(regex, ""))