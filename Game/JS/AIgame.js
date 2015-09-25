/*
if the AI were to work it would do so as follows: it set up its side of the board completely randomly and then whenever it has to make a move it
would assign each of the player's pieces a threat value which depends on the following factor:
	- distance to the AI's flag
	- rank
	- nearby AI's pieces defeatable by this piece
Then, for every player's piece, the AI would look at all its own pieces and determine their ability to deal with the threat the player's piece poses.
This would be determined by the following factors:
	- AI's piece's ability to actually defeat that player's piece
	- distance to player's piece
Then it would combine the highest ability with the threat and look at all those combinations to determine which move is best taken.
Now the AI would determine the path the (AI's) piece with the highest ability score (for that particular player's piece) has to take to reach 
the (player's) piece with the highest combined threat and ability.  It would then execute the move it found.
*/
AI = new Object();
		
information.playerNumber="1";		//make the player player number 1
information.turn=true;				//give the player the first turn
information.AIgame=true;			//state that this is a game against the AI

function getRandom(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}			//a simple function for getting a random number between two values					
		
AI.setup = function(){
	AI.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};		//the pieces the AI has to place
	for(x=0;x<10;x++){
		for(y=0;y<4;y++){
			AI.placePiece(x,y);		//make the AI place a piece
		}
	}
	game.examine(information.gameState);		//update the game board visually
}		

game.testSetup = function(){
	AI.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};
	for(x=0;x<10;x++){
		for(y=6;y<10;y++){
			finished=false;
			while(!finished){
				select = getRandom(1,12);
				if(select==11){
					select="b";
				}
				if(select==12){
					select="f";
				}
				if(AI.stock[select]>0){
					if(select=="f"){
						AI.flagLocation={x:x,y:y};
					}
					AI.stock[select]--;
					information.gameState[x][y]={owner:1,content:select,revealed:"no"};
					finished=true;
				}
			}
		}
	}
	game.examine(information.gameState);
}					//previously used for testing. It set up the players side of the board randomly

AI.placePiece = function(x,y){
	finished=false;			//used to keep trying to place a random piece until one is finally selected which can be placed (because the AI still has pieces of this type left to place)
	while(!finished){
		select = getRandom(1,12);
		if(select==11){
			select="b";
		}
		if(select==12){
			select="f";
		}			//select a random piece type
		if(AI.stock[select]>0){		//if the AI has pieces left of this type
			if(select=="f"){
				AI.flagLocation={x:x,y:y};
			}		//store the location of the AI's flag
			AI.stock[select]--;			//reduce the remaining amount of pieces of this type
			information.gameState[x][y]={owner:2,content:select,revealed:"no"};		//place the piece on the board
			finished=true;			//end the while loop, since a piece has been placed in this position
		}
	}
}

AI.turn = function(){
	information.createThreat();		//create the threat object
	information.turn=true;			//grant the player his turn
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
			AI.determineAction(x,y);
		}
	}								//gather information later used to determine which piece should move in the direction of which other piece
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
			AI.processMove(x,y);
		}
	}								//establish which piece should move in the direction of which other piece then execute that move
}

information.createThreat = function(){
	AI.threat=[];			//create the actual threat object
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({threat:0,"bestAbility":"","abilityLocation":{"x":"","y":""}});
		}
		AI.threat.push(value);
	}			//fill the threat object with empty data	
	AI.threats=[];			//create the threats object used to determine the maximum threat value across all the opponents pieces
}

AI.processMove = function(x,y){
	if(AI.threat[x][y]["threat"]==Math.max.apply(null,AI.threats)){			//if the current tile contains (one of) the best target(s) to attack...
		move=AI.findPath(AI.threat[x][y]['abilityLocation']['x'],AI.threat[x][y]['abilityLocation']['y'],x,y);		//find the direction in which the piece that is best suited to make the attack must move
		information.gameState[move['x']][move['y']]=information.gameState[AI.threat[x][y]['abilityLocation']['x']][AI.threat[x][y]['abilityLocation']['y']];		//move the AI's piece to the target tile
		information.gameState[AI.threat[x][y]['abilityLocation']['x']][AI.threat[x][y]['abilityLocation']['y']]={'content':"",'revealed':"no",'owner':0};			//empty the AI's previously occupied tile
		game.examine(information.gameState);			//visually update the game board
	}
}					//unfinished!!!

AI.determineAction = function(x,y){
	if(information.gameState[x][y]['owner']==1){		//for every piece belonging to the opponent
		if(information.gameState[x][y]['content']=="f"){
			AI.threat[x][y]['threat']=100;
		}			//if it is a flag give it a high "threat" level
		else{
			if(information.gameState[x][y]['content']=="b"){
				AI.threat[x][y]['threat']=5;
			}				//if it is a bomb give it a middling threat level
			else{				//otherwise...
				AI.threat[x][y]['threat']=parseInt(information.gameState[x][y]['content']);			//give it a threat level equal to its rank
				AI.threat[x][y]['threat']+= AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"yes");		//if a clear path from this piece to the AI's flag can be found increase its threat level equal to the distance to the flag
				AI.threat[x][y]['threat']+=0.5*AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"semi");	//if this piece can fight its way to the AI's flag increase its threat level proportional to the distance to the flag
				AI.threat[x][y]['threat']+=0.1*AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"no");		//if no path can be found to the AI's flag increase this piece's threat level proportional to the distance to the flag
				AI.threat[x][y]['threat']+=AI.surroundingPieces(x,y);		//increase this piece's threat level depending on the AI's pieces in its vicinity (at most two moves away) which it can defeat
			}
			AI.abilities=[];
			for(x2=0;x2<10;x2++){
				for(y2=0;y2<10;y2++){
					if(information.gameState[x2][y2]['owner']==2){
						if(tests.trueMoveable(x2,y2)){
							AI.determineAbility(x,y,x2,y2);
						}
					}
				}
			}				//for every piece the AI controls determine its ability to deal with the current piece being examined
			maxAbility=tests.maxAbility();
			AI.threat[x][y]["threat"]=AI.threat[x][y]["threat"]*maxAbility;		//increase this piece's threat level by how wel it can be dealt with
			for(i=0;i<AI.abilities.length;i++){
				if(AI.abilities[i]["ability"]==maxAbility){
					AI.threat[x][y]["abilityLocation"]=AI.abilities[i]["location"];
					break;
				}
			}				//store the location of the AI's piece best suited to deal with the current piece
			AI.threats.push(AI.threat[x][y]["threat"]);			//add the threat level to a seperate array used to determine which possible move to execute (the move associated with the highest threat level)
		}
	}	
}

AI.determineAbility = function(x,y,x2,y2){
	if(AI.findDistance(x2,y2,x,y,"semi")!=0){			//if this piece (x2,y2) can fight its way to the target piece (x,y)...
		abillity=0;
		if(AI.findDistance(x2,y2,x,y,"yes")!=0){
			ability-=0.1*AI.findDistance(x2,y2,x,y,"yes");
		}
		else{
			if(AI.findDistance(x2,y2,x,y,"semi")!=0){
				ability-=0.5*AI.findDistance(x2,y2,x,y,"semi");
			}						//!!!!!!!!!!!!!!!!!improve!!!!!!!!!!!!!!! right now a clear path to the target piece can lead to a lower ability than if no clear path existed
		}			//decrease this piece's ability depending on the distance to the target piece and whether the path to this piece is clear or if it can only fight its way there
		if(information.gameState[x2][y2]['content']>information.gameState[x][y]['content']){
			ability+=10;
			AI.abilities.push({"ability":ability,"location":{"x":x2,"y":y2}});
		}
		else if(information.gameState[x2][y2]['content']==information.gameState[x][y]['content']){
			ability+=5;
			AI.abilities.push({"ability":ability,"location":{"x":x2,"y":y2}});
		}
		else{
			ability+=0;
			AI.abilities.push({"ability":ability,"location":{"x":x2,"y":y2}});
		}			//depending on whether this piece can defeat, tie with or lose to the target piece increase its ability accordingly (and store both its ability and location)
	}
	else{
		ability=-100;
		AI.abilities.push({"ability":ability,"location":{"x":x2,"y":y2}});
	}			//if this piece cannot find a clear path to the target piece decrease its ability dramaitcally and store both its ability and location
}

AI.findPath = function(x2,y2,x,y){
	information.createReach(x2,y2);		//create a reach object
	memory="";
	while(memory!=stringify(information.reach)){		//continue whilst progress is being made (things change every loop)
		memory=stringify(information.reach);
		for(x3=0;x3<10;x3++){
			for(y3=0;y3<10;y3++){
				if(tests.unownedMoveableOrEmpty(x3,y3)){
					if(tests.defeatableOrEmpty(x2,y2,x3,y3)){		//if this piece (x3,y3) doesn't belong to the AI's and it can be defeated (by the AI's piece x2,y2) or the tile is empty...
						information.determineReach(x3,y3);			//determine whether this tile (x3,y3) is reachable and if so what distance this tile is to the current tile (x2,y2)
					}
				}
			}
		}
	}			//determines which tile the AI's piece (x2,y2) can reach and what distance it has to travel to do so
	path=[];
	dist=true;
	i2=0;
	while(dist && i2<10000){		//while a path hasn't been found and the loop hasn't run an exceedingly large amount of times (should only be necessary whilst debugging)
		i2++;
		information.distances=[];
		information.action=[];
		information.determineDistance(x-1,y,1);
		information.determineDistance(x+1,y,2);
		information.determineDistance(x,y-1,3);
		information.determineDistance(x,y+1,4);		//in each direction around x and y (which originally is the location of the target piece) check how far the AI's piece (x2,y2) has to travel to reach that tile (if it can)
		dist=tests.checkRemainingDistance();		//if the to be examined square (x,y) is now directly adjacent to the AI's piece stop the loop
		bool=true;			//makes sure only one tile with minimum distance is chosen
		for(i=0;i<4;i++){
			if(typeof information.distances[i]!=="undefined"){
				if(information.distances[i]==Math.min.apply(null,information.distances) && bool){	//if the distance in this direction is minimal...
					switch(information.action[i]){
						case 1:
							x--;
							break;
						case 2:
							x++;
							break;
						case 3:
							y--;
							break;
						case 4:
							y++;
							break;
					}			//change the to be examined tile (x,y) to the tile that lies in this direction
					console.log("x"+x+"y"+y);
					path.push({"x":x,"y":y});		//add this tile to the path
					bool=false;				//make sure only one tile is added per loop
				}
			}
		}
	}
	return path[path.length-1];		//return the last tile of the path
}			//this function determines which direction a piece (x2,y2) should move in order to make progress toward a target tile (x,y) it does this by first determining which tiles are reachable (by x2,y2) and how far this tile has to move in order to reach these tiles and then working its way backwards from the target tile (x,y) choosing the tiles which are the closest to the original tile (x2,y2)

information.determineDistance = function(x,y,action){
	if(typeof information.reach[x] !== "undefined"){
		if(typeof information.reach[x][y] !== "undefined"){
			if(information.reach[x][y]["reachable"]){			//if this tile is reachable (and exists)
				information.distances.push(information.reach[x][y]["distance"]);		//store	the distance from this tile (x,y) to the original tile
				information.action.push(action);			//store the direction in which must be traveled in order to move to this tile
			}
		}
	}
}				//used to find the shortest path between two tiles

information.determineReach = function(x3,y3){
	information.distances=[];
	information.findDistance(x3-1,y3);
	information.findDistance(x3+1,y3);
	information.findDistance(x3,y3-1);
	information.findDistance(x3,y3+1);				//check each direction if that tile is reachable
	if(information.distances.length!=0){			//if at least one of the tiles is reachable...
		information.reach[x3][y3]={distance:Math.min.apply(Math,information.distances)+1,reachable:true};		//make the distance to this tile equal to the shortest distance to one of its neighboring tiles plus one
	}
}			//used to determine whether this tile (x3,y3) is reachable by a piece

information.findDistance = function(x3,y3){
	if(typeof information.reach[x3]!=="undefined"){
		if(typeof information.reach[x3][y3]!=="undefined"){
			if(information.reach[x3][y3]["reachable"]){
				information.distances.push(information.reach[x3][y3]["distance"]);
			}
		}
	}
}			//check whether this tile is reachable, if so store its distance

information.createReach = function(x2,y2){
	information.reach=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({distance:"none",reachable:false});
		}
		information.reach.push(value);
	}
	information.reach[x2][y2]={distance:0,reachable:true};		//make a certain tile reachable (the tile of which the reach must be determined)
}				//create a reach object

AI.findDistance = function(x2,y2,x,y,clear){
	information.createReach(x2,y2);			//create a reach object
	memory="";
	while(memory!=stringify(information.reach)){		//whilst progress is being made
		memory=stringify(information.reach);
		for(x3=0;x3<10;x3++){
			for(y3=0;y3<10;y3++){
				if(tests.viablePath(clear)){		//if this tile fits the restrictions placed by the clear variable (yes=no pieces or walls in the way, no=no walls in the way
					information.determineReach(x3,y3);		//check if this tile is reachable	
				}
				else if(tests.unownedMoveableOrEmpty(x3,y3 )){
					if(tests.defeatableOrEmpty(x2,y2,x3,y3)){		//if this tile fits the restrictions of a semi clear path (no friendly pieces, unbeatable opponents pieces or walls in the way)
						information.determineReach(x3,y3);		//check if this tile is reachable
					}
				}
			}
		}
	}			//determine reachable tiles and their distance based on the restrictions placed by the clear variable (it is a loop because each time only tiles adjacent to already reachable tiles will be converted to reachable)
	information.distances=[];
	information.findDistance(x-1,y);
	information.findDistance(x+1,y);
	information.findDistance(x,y-1);
	information.findDistance(x,y+1);		//check the distance the original tile (x2,y2) must travel when approaching from any direction to reach the target tile (x,y) if it can
	if(information.distances.length==0){		//if it is unreachable
		return 0;
	}
	else{
		return	Math.min.apply(Math,information.distances);		//return the minimum distance
	}
}				//find the distance between two tile and return it (make a distinctions between a clear path a semi clear (if the piece can fight its way to the other tile) path or a blocked path)

AI.surroundingPieces= function(x,y){
	threat=0;
	threat+=toRepeat(x,y,x,y+2);
	threat+=toRepeat(x,y,x-1,y+1);
	threat+=toRepeat(x,y,x,y+1);
	threat+=toRepeat(x,y,x+1,y+1);
	threat+=toRepeat(x,y,x-2,y);
	threat+=toRepeat(x,y,x-1,y);
	threat+=toRepeat(x,y,x+1,y);
	threat+=toRepeat(x,y,x+2,y);
	threat+=toRepeat(x,y,x-1,y-1);
	threat+=toRepeat(x,y,x,y-1);
	threat+=toRepeat(x,y,x+1,y-1);
	threat+=toRepeat(x,y,x,y-2);
	/*threat+=information.adjustThreat(x,y,x-1,y);
	threat+=information.adjustThreat(x,y,x+1,y);
	threat+=information.adjustThreat(x,y,x,y-1);
	threat+=information.adjustThreat(x,y,x,y+1);*/
	return threat;
}		//for each tile in a diamond shape around the original tile (x,y) add a threat value
/*
information.adjustThreat = function(x,y,x2,y2){
	if(typeof information.gameState[x2]!=="undefined"){
		if(typeof information.gameState[x2][y2]!=="undefined"){
			threat+=toRepeat(x,y,x2,y2);
		}
	}
	return threat;
}

function repeat(xo,yo,x,y){
	threat=0;
	threat+=toRepeat(xo,yo,x-1,y);
	threat+=toRepeat(xo,yo,x,y+1);
	threat+=toRepeat(xo,yo,x,y-1);
	threat+=toRepeat(xo,yo,x,y+1);
	return threat;
}
*/
function toRepeat(xo,yo,x2,y2){
	if(typeof information.gameState[x2]!=="undefined"){
		if(typeof information.gameState[x2][y2]!=="undefined"){
			if(tests.defeatableOpponent(xo,yo,x2,y2)){
				return parseInt(information.gameState[x2][y2]['content']);
			}
			else if(tests.tieableOpponent(xo,yo,x2,y2)){
				return 0.5*parseInt(information.gameState[x2][y2]['content']);
			}
		}
	}
	return 0;
}				//if the tile (x2,y2) exists and the original piece (xo,yo) is able the defeat the AI's piece occupying this tile (x2,y2) add a threat value equal to the rank of the piece that could be defeated (unless its a tie in that case halve the added threat value)
				
                
                
                
                
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                //the jumping of tiles after reloading the page is the result of loading the last move made by THAT player and then obtaining a move by the other player
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!