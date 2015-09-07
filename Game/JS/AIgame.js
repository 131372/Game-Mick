/*
this code is supposed to handle almost the entire game apart from the matchmaking system and the server interaction. 
It is supposed to be in conjunction with game.php where it allows for player interaction with the game. 
The game board itself is visually represented by a collection of divs with ids that this file makes use of. 
It is also important to note that these divs contain spans with similar ids. Besides this the code also interacts with
buttons present in game.php. For now all these buttons are related to the setup stage of the game where the player chooses which piece to place next 
and can end the setup phase when all pieces are used. 
This file also gathers a small amount of information from game.php through obtaining the content 
of divs which contain the player name and the name of the owner of the room.
For server interaction this file relies on gamestate.php which it uses to submit moves made by a player and retrieve moves made by the other player.
At the start only the owner is allowed to interact and has to place all of his pieces on his side of the board before ending his setup phase and passing
it onto the other player which then places his pieces. 
Pieces are placed by first selecting a piece to place and then clicking a tile to place said piece there. 
If the tile already contains another piece this piece is removed from the board to be placed again.
*/
AI = new Object();
		
information.playerNumber="1";
information.turn=true;		//setting up some variables
information.AIgame=true;

function getRandom(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}						
		
AI.setup = function(){
	AI.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};
	for(x=0;x<10;x++){
		for(y=0;y<4;y++){
			AI.placePiece(x,y);
		}
	}
	examine(information.gameState);
}		

AI.placePiece = function(x,y){
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
			information.gameState[x][y]={owner:2,content:select,revealed:"no"};
			finished=true;
		}
	}
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
	examine(information.gameState);
}

information.createThreat = function(){
	AI.threat=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({threat:0,"bestAbility":"","abilityLocation":{"x":"","y":""}});
		}
		AI.threat.push(value);
	}
	AI.threats=[];	
}

AI.turn = function(){
	information.createThreat;
	information.turn=true;
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
			AI.determineAction(x,y);
		}
	}
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
			AI.processMove(x,y);
		}
	}
}

AI.processMove = function(x,y){
	if(AI.threat[x][y]["threat"]==Math.max.apply(null,AI.threats)){
		move=AI.findPath(AI.threat[x][y]['abilityLocation']['x'],AI.threat[x][y]['abilityLocation']['y'],x,y);
		information.gameState[move['x']][move['y']]=information.gameState[AI.threat[x][y]['abilityLocation']['x']][AI.threat[x][y]['abilityLocation']['y']];
		information.gameState[AI.threat[x][y]['abilityLocation']['x']][AI.threat[x][y]['abilityLocation']['y']]={'content':"",'revealed':"no",'owner':0};
		examine(information.gameState);
	}
}

AI.determineAction = function(x,y){
	if(information.gameState[x][y]['owner']==1){
		if(information.gameState[x][y]['content']=="f"){
			AI.threat[x][y]['threat']=100;
		}
		else{
			if(information.gameState[x][y]['content']=="b"){
				AI.threat[x][y]['threat']=5;
			}
			else{
				AI.threat[x][y]['threat']=parseInt(information.gameState[x][y]['content']);
			}
			AI.threat[x][y]['threat']-=AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"yes");
			AI.threat[x][y]['threat']-=0.5*AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"semi");
			AI.threat[x][y]['threat']-=0.1*AI.findDistance(AI.flagLocation['x'],AI.flagLocation['y'],x,y,"no");
			if(information.gameState[x][y]['content']!="b"){
				AI.threat[x][y]['threat']+=AI.surroundingPieces(x,y);
			}
			AI.abilities=[];
			for(x2=0;x2<10;x2++){
				for(y2=0;y2<10;y2++){
					if(information.gameState[x2][y2]['owner']==2){
						if(information.trueMoveable(x2,y2)){
							AI.determineAbility(x,y,x2,y2);
						}
					}
				}
			}
			maxAbility=AI.maxAbility();
			AI.threat[x][y]["threat"]=AI.threat[x][y]["threat"]*maxAbility;
			for(i=0;i<AI.abilities.length;i++){
				if(AI.abilities[i]["ability"]==maxAbility){
					AI.threat[x][y]["abilityLocation"]=AI.abilities[i]["location"];
					break;
				}
			}
			AI.threats.push(AI.threat[x][y]["threat"]);
		}
	}	
}

AI.determineAbility = function(x,y,x2,y2){
	if(AI.findDistance(x2,y2,x,y,"semi")!=0){
		abillity=0;
		ability-=AI.findDistance(x2,y2,x,y,"yes");
		ability-=0.5*AI.findDistance(x2,y2,x,y,"semi");
		ability-=0.1*AI.findDistance(x2,y2,x,y,"no");
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
		}
	}
	else{
		ability=-100;
	}
}

AI.findPath = function(x2,y2,x,y){
	information.createReach(x2,y2);
	memory="";
	while(memory!=stringify(information.reach)){
		memory=stringify(information.reach);
		for(x3=0;x3<10;x3++){
			for(y3=0;y3<10;y3++){
				if(information.unownedMoveable(x3,y3 )){
					if(information.defeatableOrEmpty(x2,y2,x3,y3)){
						information.determineReach(x3,y3);
					}
				}
			}
		}
	}
	path=[];
	dist=true;
	i2=0;
	while(dist && i2<10000){
		i2++;
		information.distances=[];
		information.action=[];
		information.determineDistance(x-1,y,1);
		information.determineDistance(x+1,y,2);
		information.determineDistance(x,y-1,3);
		information.determineDistance(x,y+1,4);
		dist=information.checkRemainingDistance();
		bool=true;
		for(i=0;i<4;i++){
			if(typeof distances[i]!=="undefined"){
				if(distances[i]==Math.min.apply(null,distances) && bool){
					switch(action[i]){
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
					}
					console.log("x"+x+"y"+y);
					path.push({"x":x,"y":y});
					bool=false;
				}
			}
		}
	}
	return path[path.length-1];
}

information.determineDistance = function(x,y,action){
	if(typeof information.reach[x] !== "undefined"){
		if(typeof information.reach[x][y] !== "undefined"){
			if(information.reach[x][y]["reachable"]){
				information.distances.push(information.reach[x][y]["distance"]);
				information.action.push(action);
			}
		}
	}
}

information.determineReach = function(x3,y3){
	information.distances=[];
	information.findDistance(x3-1,y3);
	information.findDistance(x3+1,y3);
	information.findDistance(x3,y3-1);
	information.findDistance(x3,y3+1);
	if(distances.length!=0){
		information.reach[x3][y3]={distance:Math.min.apply(Math,distances)+1,reachable:true};
	}
}

information.findDistance = function(x3,y3){
	if(typeof information.reach[x3]!=="undefined"){
		if(typeof information.reach[x3][y3]!=="undefined"){
			if(information.reach[x3][y3]["reachable"]){
				information.distances.push(information.reach[x3][y3]["distance"]);
			}
		}
	}
}

information.createReach = function(x2,y2){
	information.reach=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({distance:"none",reachable:false});
		}
		information.reach.push(value);
	}
	information.reach[x2][y2]={distance:0,reachable:true};
}

AI.findDistance = function(x2,y2,x,y,clear){
	information.createReach(x2,y2);
	memory="";
	while(memory!=stringify(information.reach)){
		memory=stringify(information.reach);
		for(x3=0;x3<10;x3++){
			for(y3=0;y3<10;y3++){
				if(!information.reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']==0 && clear=="yes" || !information.reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']!="wall" && clear=="no"){
					information.determineReach(x3,y3);
				}
				else if(information.unownedMoveable(x3,y3 )){
					if(information.defeatableOrEmpty(x2,y2,x3,y3)){
						information.determineReach(x3,y3);
					}
				}
			}
		}
	}
	information.distances=[];
	information.findDistance(x-1,y);
	information.findDistance(x+1,y);
	information.findDistance(x,y-1);
	information.findDistance(x,y+1);
	if(distances.length==0){
		return 0;
	}
	else{
		return	Math.min.apply(Math,distances);
	}
}

AI.surroundingPieces= function(x,y){
	threat=0;
	x2=x-1;
	y2=y;
	if(typeof information.gameState[x2]!=="undefined"){
		threat+=repeat(x,y,x2,y2);
	}
	x2=x+1;
	y2=y;
	if(typeof information.gameState[x2]!=="undefined"){
		threat+=repeat(x,y,x2,y2);
	}
	x2=x;
	y2=y-1;
	if(typeof information.gameState[x2][y2]!=="undefined"){
		threat+=repeat(x,y,x2,y2);
	}
	x2=x;
	y2=y+1;
	if(typeof information.gameState[x2][y2]!=="undefined"){
		threat+=repeat(x,y,x2,y2);
	}
	return threat;
}

function repeat(xo,yo,x,y){
	threat=0;
	x2=x-1;
	y2=y;
	threat+=toRepeat(xo,yo,x2,y2);
	x2=x+1;
	y2=y;
	threat+=toRepeat(xo,yo,x2,y2);
	x2=x;
	y2=y-1;
	threat+=toRepeat(xo,yo,x2,y2);
	x2=x;
	y2=y+1;
	threat+=toRepeat(xo,yo,x2,y2);
	return threat;
}

function toRepeat(xo,yo,x2,y2){
	if(typeof information.gameState[x2]!=="undefined"){
		if(typeof information.gameState[x2][y2]!=="undefined"){
			if(parseInt(information.gameState[xo][yo]['content']) > parseInt(information.gameState[x2][y2]['content']) && information.gameState[x2][y2]['owner']==2){
				console.log("werkt");
				return parseInt(information.gameState[x2][y2]['content']);
			}
			else if(parseInt(information.gameState[xo][yo]['content'])==parseInt(information.gameState[x2][y2]['content']) && information.gameState[xo][yo]['owner']=="2"){
				return 0.5*parseInt(information.gameState[x2][y2]['content']);
			}
		}
	}
	return 0;
}

AI.findFlagDistance = function(x,y,clear){
	information.reach=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({distance:"none",reachable:false});
		}
		information.reach.push(value);
	}
	information.reach[x][y]={distance:0,reachable:true};
	memory="";
	while(memory!=stringify(information.reach)){
		memory=stringify(information.reach);
		for(x2=0;x2<10;x2++){
			for(y2=0;y2<10;y2++){
				if(!information.reach[x2][y2]["reachable"] && information.gameState[x2][y2]['content']==0 && clear=="yes" || !information.reach[x2][y2]["reachable"] && information.gameState[x2][y2]['content']!="wall" && clear=="no"){
					distances=[];
					if(typeof information.reach[x2-1]!=="undefined"){
						if(information.reach[x2-1][y2]["reachable"]){
							distances.push(information.reach[x2-1][y2]["distance"]);
						}
					}
					if(typeof information.reach[x2+1]!=="undefined"){
						if(information.reach[x2+1][y2]["reachable"]){
							distances.push(information.reach[x2+1][y2]["distance"]);
						}
					}
					if(typeof information.reach[x2][y2-1]!=="undefined"){
						if(information.reach[x2][y2-1]["reachable"]){
							distances.push(information.reach[x2][y2-1]["distance"]);
						}
					}
					if(typeof information.reach[x2][y2+1]!=="undefined"){
						if(information.reach[x2][y2+1]["reachable"]){
							distances.push(information.reach[x2][y2+1]["distance"]);
						}
					}
					if(distances.length!=0){
						information.reach[x2][y2]={distance:Math.min.apply(Math,distances)+1,reachable:true};
					}
				}
				else if(clear=="semi" && information.gameState[x2][y2]['content']!="b" && information.gameState[x2][y2]['content']!="f"){
					if(parseInt(information.gameState[x][y]['content'])>parseInt(information.gameState[x2][y2]['content']) || information.gameState[x2][y2]['content']==0){
						distances=[];
						if(typeof information.reach[x2-1]!=="undefined"){
							if(information.reach[x2-1][y2]["reachable"]){
								distances.push(information.reach[x2-1][y2]["distance"]);
							}
						}
						if(typeof information.reach[x2+1]!=="undefined"){
							if(information.reach[x2+1][y2]["reachable"]){
								distances.push(information.reach[x2+1][y2]["distance"]);
							}
						}
						if(typeof information.reach[x2][y2-1]!=="undefined"){
							if(information.reach[x2][y2-1]["reachable"]){
								distances.push(information.reach[x2][y2-1]["distance"]);
							}
						}
						if(typeof information.reach[x2][y2+1]!=="undefined"){
							if(information.reach[x2][y2+1]["reachable"]){
								distances.push(information.reach[x2][y2+1]["distance"]);
							}
						}
						if(distances.length!=0){
							information.reach[x2][y2]={distance:Math.min.apply(Math,distances)+1,reachable:true};
						}
					}
				}
			}
		}
	}
	distances=[];
	if(typeof information.reach[AI.flagLocation["x"]-1] !== "undefined"){
		if(information.reach[AI.flagLocation["x"]-1][AI.flagLocation["y"]]["reachable"]){
			distances.push(information.reach[AI.flagLocation["x"]-1][AI.flagLocation["y"]]["distance"]);
		}
	}
	if(typeof information.reach[AI.flagLocation["x"]+1] !== "undefined"){
		if(information.reach[AI.flagLocation["x"]+1][AI.flagLocation["y"]]["reachable"]){
			distances.push(information.reach[AI.flagLocation["x"]+1][AI.flagLocation["y"]]["distance"]);
		}
	}
	if(typeof information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1] !== "undefined"){
		if(information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1]["reachable"]){
			distances.push(information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1]["distance"]);
		}
	}
	if(typeof information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1] !== "undefined"){
		if(information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1]["reachable"]){
			distances.push(information.reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1]["distance"]);
		}
	}
	if(distances.length==0){
		return 0;
	}
	else{
		return	Math.min.apply(Math,distances);
	}
}							

function testExamine(information.reach){
	for(x3=0;x3<10;x3++){
		for(y3=0;y3<10;y3++){
			if(information.reach[x3][y3]["reachable"]){
				$("#x"+(x3+1)+"y"+(y3+1)).css("background-color","purple");
			}
		}
	}
}
                
                
                
                
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                //the jumping of tiles after reloading the page is the result of loading the last move made by THAT player and then obtaining a move by the other player
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!