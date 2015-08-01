var bord = {
    name: 'bord',
    'initialized' : false,
    'hGrootte': 3, // aantal kolommen
    'vGrootte': 3, // aantal rijen
    'velden': [],    
    'selection': 0, // wat is gekozen (id)
    registered : [],

    'reset': function () {
        this.selection = 0;        
        this.init();
    },
    init: function () {      
        for (var x = 0; x < this.vGrootte; x++) {
            this.velden[x] = [];
            this.registered[x] = []
            for (var y = 0; y < this.hGrootte; y++) {
                var v = veld(x, y, 3*x + y)     // new one
                this.velden[x][y] = v;
                this.registered[x][y] = undefined;
            }
        }
        this.initialized = true;
    }
};
