describe('game Veld', function () {
    it("should be part of game.bord", function() {
        var b = game.bord;
        var v = b.velden;
        expect(b).hasOwnProperty('velden');
        
        expect(v[0][0]).toBeDefined();
        for(var x = 0; x < b.vGrootte; x++) {
            for(var y = 0; y < b.hGrootte; y++) {
                expect(v[x][y]).toBeDefined();                
                var val = v[x][y];                
                expect(val.nr).toEqual(val.calcNr());
                expect(val.nr).toEqual(x* b.hGrootte + y);
                expect(val.x).toEqual(x);
            }     
        };    
        
    });
});