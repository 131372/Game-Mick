var game = {
    bord: bord, // het bord
    zet: zet, // de zetten
    logic: logic        // de logica
};

// voeg wat functionaliteit toe.
$.extend(game, {
    init: function () {
        this.bord.gameBordInit();
        this.zet.init(this.bord.vGrootte);
    },
    reset: function () {
        this.init();
    }
});



$.extend(game, {
    // ajax request was succesfull, returning data (object))
    onSuccess: function (data) {
        //console.log("onSuccess", data, response)
        var time = Math.round((new Date()).getTime() / 1000);
        if (data == undefined) {
            console.log(time, 'Server is silent.');
        }
        else if (data.status != undefined) {
            var status = data.status;

            switch (status) {
                //Wachten tot volgende order 
                case 'wachtenOpAnder':
                    //break;
                case 'wachtenOpInput':
                    //Speciale actie :D?
                    if (data.info != undefined) {
                        switch (data.info.action) {
                            case "plaats":
                                game.zet.setXYFromNr(data.info.v1)
                                //Registreer de zetten
                                game.zet.plaats(data.info.v1, data.info.v2);

                                zet.allow = true;   // zet is geplaats in veld, je mag weer
                                break; // leave switch
                            // case "JAWAT";
                        }
                    }

                    //Speler is ondertussen gewisseld
                    if (data.beurt != mp.beurt) {
                        mp.beurt = data.beurt;
                        mp.setPlayer(mp.beurt);
                    }
                    break; // status == wachten
                    //Ongeldige request
                case 'leave':
                    alert("Ongeldige bewerking!");
                    break;
                    //Initialize
                case 'initialize':
                    console.log("initialize", data)
                    if (data.naam1.length == 0 || data.naam2.length == 0) {                            
                        //Een van de namen is niet correct, opnieuw vragen dus...

                        mp.req.send({'action': 'initialize'});
                        return;
                    }

                    mp.setSpeler(data.naam1);
                    mp.setSpeler(data.naam2);
                    mp.setPlayer(data.beurt);
                    break;

                case 'serverError': //Serversided-errorcodes weergeven
                case 'serverFout':
                    console.log(time, 'SERVERFOUT: ' + data.string);
                    break;
                case 'gameInitialize': //Game nog niet gestart
                    mp.retries++;
                    if (mp.retries >= 10) {
                        //alert("Time-out bij verbinden naar speler, is hij nog actief?\n\n(Poging " + mp.retries + ")");
                    } else {
                        setTimeout(function () {
                            mp.req.send({action: 'initialize'});
                        }, 500);
                    }
                    break;
            }
        } else if (data.error != undefined) {
            var error = data.error;
            switch (error) {
                case 'tegenstanderDood':
                    console.log(time, 'Server zegt tegenstander dood.');
                    alert("De tegenstander heeft het spel verlaten.");
                    window.location.href = "../index.php";
                    break;
            }
        }
        else {
            console.log(time, 'Server is (still) boring.');
        }
    }
});

$(document).ready(function () {
    game.init();    // only when all JS have been loaded (// found via jasmine BDD tests
})

