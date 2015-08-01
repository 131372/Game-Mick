var eenVeld = {
    id: "",
    x: -1,
    y: -1,
    nr: -1,
    calcNr: function () {
        return 3 * this.x + this.y; // ughly dependency on 3 (grootte [][])
    },
    init: function (x, y) {
        this.x = x;
        this.y = y;
        this.nr = this.calcNr();
        this.id = 'v' + this.nr;
    },
    toString: function () {
        var out = "";
        for (var name in object) {
            if (this.hasOwnProperty(name)) {
                out += name + ":" + this.name + ',';
            }
        }
        return out;

    }
}

var veld = function (x, y) {  // steeds aparte instances
    var o = Object.create(eenVeld);
    o.init(x, y);
    return o;
};
