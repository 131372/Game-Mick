// https://gist.github.com/adamyanalunas/3925377
// https://gist.github.com/prantlf/8631669

function buildObject(object) {
// http://stackoverflow.com/questions/15487510/angularjs-jasmine-comparing-objects
    var built = {};
    for (var name in object) {
        if (object.hasOwnProperty(name)) {
            built[name] = name; // object[name];
        }
    }
    return built;
}
function buildObjectContents(object) {
// http://stackoverflow.com/questions/15487510/angularjs-jasmine-comparing-objects
    var built = {};
    for (var name in object) {
        if (object.hasOwnProperty(name)) {
            built[name] = object[name];
        }
    }
    return built;
}
var myMatchers = {
    /*
     hasProperty: function (util, customEqualityTesters) {
     //jasmine.Matchers.prototype.hasProperty = function (expected) {
     var actual, notText, objType;
     actual = this.actual;
     notText = this.isNot ? 'has not the' : '';
     this.message = function () {
     return 'Expected ' + actual + notText + ' property ' + expected;
     }
     return actual.hasOwnProperty(expected);
     },
     */
    toBeTypeOf: function (util, customEqualityTesters) {
        return {
            compare: function (actual, expected) {

                if (expected === undefined) {
                    expected = '';
                }
                var aObject = buildObject(actual);
                var eObject = buildObject(expected);
                //console.log(aObject, eObject);
                var result = {};
                result.pass = util.equals(aObject, eObject, customEqualityTesters);
                if (result.pass) {
                    result.message = "Expected has same fields as" + aObject.toString();
                }
                else {
                    var notText = ' not ';
                    result.message = "Expected " + actual.toString() + notText + " to be of type of " + expected;
                }
                return result;
            }
        }
    },
    toBeSimilarTo: function (util, customEqualityTesters) {
        return {
            compare: function (actual, expected) {
                if (expected === undefined) {
                    expected = '';
                }
                var aObject = buildObjectContents(actual);
                var eObject = buildObjectContents(expected);
                //console.log("===>", aObject, "****", eObject);
                var result = {};
                result.pass = util.equals(aObject, eObject, customEqualityTesters);
                if (result.pass) {
                    result.message = "Expected has same fields as" + aObject.toString();
                }
                else {
                    var notText = ' not ';
                    //console.log(actual);
                   // result.message = "Expected " + actual.toString() + notText + " to be similar ";
                   result.message = "Expected not similar to expected";
                }
                return result;
            }
        }
    }
};
