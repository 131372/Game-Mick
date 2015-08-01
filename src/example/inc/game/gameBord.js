$.extend(bord, {
    name: 'gameBord', // rename
    'gameBordInitialized': false,
    'grootte': -1,
    g2: 0,
    gameBordInit: function () {
        this.init();
        this.grootte = this.vGrootte;
        this.g2 = this.grootte * this.grootte;
        this.gameBordInitialized = true;
    }
});


bord.gameBordInit();


