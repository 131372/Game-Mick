describe('bord', function () {
    beforeEach(function () {
        jasmine.addMatchers(myMatchers);
    });
    it('should contain an array of "velden"', function () {
        var v = bord.velden;

        var x = veld(0,0);
        expect(v[1][1]).toBeTypeOf(veld(1,1));
        expect(v[0][0]).toBeTypeOf(veld(0,0));
        expect(v[0][0]).toBeSimilarTo(x);

    })


});
