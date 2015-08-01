
describe('game', function () {
    it("should be defined", function () {
        expect(game).not.toBeUndefined();
    })
    it("should contain a object type bord", function () {
        expect(game.bord).not.toBeUndefined();
        expect(game).hasOwnProperty('gameBbord');
    });
});
describe('gameBord', function () {
    it("should be initialized", function () {
        var b = game.bord;
        expect(b).hasOwnProperty('grootte');
        expect(b.name).toEqual('gameBord');
        expect(b.gameBordInitialized).toBeTruthy();
        expect(b.initialized).toBeTruthy();
        expect(b.gameBordInitialized).toBeTruthy();

        expect(b).hasOwnProperty('registered');
        for (var x = 0; x < b.grootte; x++) {
            for (var y = 0; y < b.grootte; y++) {
                expect(b.registered[x][y]).toBeFalsy();
            }
        }
    });
    it("grootte^2 should be set", function () {
        console.log(game.bord.grootte);
        expect(game.bord.g2).not.toEqual(0);
        expect(game.bord.g2).toEqual(game.bord.grootte * game.bord.grootte);
    });
    it('grootte should be set', function () {
        console.log(game.bord.g2);
        expect(countObj(game.bord.velden)).toEqual(game.bord.g2);
    });
    //Kijk of de array opgehaald is
    it('should contain proper amount of keys', function () {
        expect(countObj(game.bord.velden)).toEqual(game.bord.g2);
    });

    //En alle hokjes?
    it('should contain the elements', function () {
        //Kijk hokje voor hokje
        for (i = 0; i < (game.bord.g2); i++) {
            var exists = $('v' + i);
            expect(exists).not.toBe(null);
        }
        expect(true).toBeTruthy();
    });

});

   