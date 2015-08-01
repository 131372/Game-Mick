var bordAction = {       
    'jouwBeurt' : false, // je mag kiezen (als het je beurt is)
    
    deAnderKrijgtDeBeurt : function() {
        this.jouwBeurt = false;
    },
    jijKrijgtDeBeurt : function() {
        this.jouwBeurt = true;
    }
};