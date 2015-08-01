
$(document).ready(function () {
    // start the game javascript on client
    var $field = $('#field');


    $("#field div").click(function (e) {

        console.log("on Click:", mp.beurt, mp.spelers, zet.keuzevakje, zet.selection, this.id)
        if (mp.beurt != mp.me) { //Niet aan de beurt is stoppen maar.
            return;
        }
        if (zet.allow == false) {   // wel aan de beurt, maar (wat voor reden ook), mag ik (even?) geen zet doen.. 
            // lijkt me dubbel op.
            return;
        }


        // na controle hebben we de beurt en hebben we de actie op het bord gezet, registreer

        var cmd = {action: 'plaats', "v1": zet.selection};
        mp.req.send(cmd);	// in de mp zit een methode req die op zijn beurt send uitvoert.

    });

});
