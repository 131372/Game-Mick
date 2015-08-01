
/**
 * Tel elementen in een object
 * @param {type} obj
 * @returns {Number}
 */
countObj = function (obj) {
    var cnt = 0;
    for (var prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            var t = typeof obj;
            if (t === 'object') {
                $.each(Object.keys(obj), function (i, v) {                   
                    cnt += countObj(v);
                });
            } else {
                cnt++;
            }
        }
    }
    return cnt;
};
