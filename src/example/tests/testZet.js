describe('zet', function () {
    it("should be part of game", function () {
        var b = game;
        var v = b.zet;
        expect(b).hasOwnProperty('zet');
        expect(v).not.toBeUndefined();
    });
    it("should have set x,y", function () {
        var z = game.zet;
        
        z.setXY(0, 1);
        
        expect(z.x).toEqual(0);        
        expect(z.y).toEqual(1);
        expect(z.nr).toEqual(1);
    });
    it ("should set XY from nr", function() {
        var z = game.zet;
        z.nr = 0;
        z.setXYFromNr()
        expect(z.x).toEqual(0); 
        expect(z.y).toEqual(0); 
    })
});