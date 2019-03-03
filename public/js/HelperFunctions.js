/**
 * Created by chris on 01/12/2017.
 */

// Ascertain if an item exists in an array
var contains = function (needle) {
    // Per spec, the way to identify NaN is that it is not equal to itself
    var findNaN = needle !== needle;
    var indexOf;

    if(!findNaN && typeof Array.prototype.indexOf === 'function') {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                var item = this[i];

                if((findNaN && item !== item) || item === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOf.call(this, needle) > -1;

    // HOW TO USE
    // contains.call(haystack, needle) ==> will either return true or false
};


var collectiblesContain = function (haystack, needle) {
    var exists = false;
    for(var item in haystack) {
        if(item == needle){
            exists = true;
            break;
        }
    }

    return exists;
};


// Convert an object to array
// returns an array of object keys
var objectToArray = function (obj) {
    return Object.keys(obj).map(function (k) { return obj[k];})
};


// Count the number of occurrence of an item in an array
var count = function (haystack, item) {
    return haystack.reduce(function (n, val) {
        return n + (val == item);
    }, 0);
};


// Format a number from 15892 to 15,892.00
// Also allows fot adding currency where needed
// Only that it doesn't support the Naira symbol as of yet
var currencyFormat = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2
    //style: 'currency',
    //currency: 'USD',
    // the default value for minimumFractionDigits depends on the currency
    // and is usually already 2
});


// Clear the browser console
var clearConsole = function() {
    console.API;

    if (typeof console._commandLineAPI !== 'undefined') {
        console.API = console._commandLineAPI; //chrome
    } else if (typeof console._inspectorCommandLineAPI !== 'undefined') {
        console.API = console._inspectorCommandLineAPI; //Safari
    } else if (typeof console.clear !== 'undefined') {
        console.API = console;
    }

    console.API.clear();
};


// Returns an array without the rejected item
var without = function (list, rejectedItem) {
    return list.filter(function (item) {
        return item !== rejectedItem;
    }).map(function (item) {
        return item;
    });
};

// Returns a camel-case string
// abc-def_ghi -> abcDefGhi
var camelize = function (str) {
    return str.replace(/[\-_+](\w)/g, function (match) {
        return match.charAt(1).toUpperCase();
    });
};
