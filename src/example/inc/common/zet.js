var zet = {
    player: "",
    x: -1,
    y: -1,
    nr: -1,
    'keuzevakje': -1,
    allow: true,
    init: function (vGrootte) {
        this.vGrootte = vGrootte // ughly dependency
    },
    setXY: function (x, y) {
        this.x = x;
        this.y = y;
        this.calcNr();
    },
    calcNr: function () {
        var s = this;
        s.nr = s.x * this.vGrootte + s.y;
    },
    setXYFromNr: function () {
        this.x = this.nr % this.vGrootte;
        this.y = this.nr / this.vGrootte;
    },
    plaats: function () {
        var v1 = this.x
        var v2 = this.y
        var nr = this.nr

        console.log("plaatsZet", this)
        this.bord.velden[v1][v2] = this.player;


    }
}