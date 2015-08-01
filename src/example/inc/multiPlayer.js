// multiplayer

var mp = {
    mode : "multiPlayer",
    selection: 0,   
    gameId: null,
    retries: 0,
    spelers: [],
    me: null,            // op deze PC ben ik de speler (nummer, gekoppeld aan beurt)
    beurt: null,
    
    setSpeler : function($naam) {
        // voeg aan spelers[] toe
    },
    setPlayer: function (turn) { // Wissel van speler
        this.beurt = turn;
        $('body').css('background-color', this.beurt == 1 ? '#fff' : '#000');
        $('#overlay').css('color', this.beurt == 1 ? '#000' : '#fff');
        $('#overlay').html((this.beurt == 1 ? this.speler['...'] : this.speler['...']) + '\'s beurt');  // adapt
    },
    /*Request-instance*/
    req: {
        json: function (cmd) {
            // hier de ajax call naar server
        },
        send: function (cmd) {
            mp.req.json(cmd)
        }
    },
};
