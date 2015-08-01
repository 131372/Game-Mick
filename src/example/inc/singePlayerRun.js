
$(document).ready(function () {
    //Houd ons levendig in alle tijden :D
    keepAlive = function () {
        mp.req.send({ action : 'keepalive'});
    };

    //OH YEAH elke seconde gewelige request naar de server paassen
    var timerId = setInterval(keepAlive, 3000);
    
    //Vraag de standaardgegevens op
    mp.req.send({ action : 'initialize' });
});