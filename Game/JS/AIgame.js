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
information = new Object();
game = new Object();
AI = new Object();
		
information.pieces=["",""];
information.selected="";
information.gameStage="setup";
information.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};		//the number of available pieces per type
information.gameState=[];		
information.playerNumber="1";
information.turn=true;		//setting up some variables

function getRandom(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

for(i=0;i<10;i++){
	value=[];
	for(i1=0;i1<10;i1++){
		value2={owner:0,content:"",revealed:"no"};
		value.push(value2);
	}
	information.gameState.push(value);
}			//create a blank game board			
		
$(function(){
	$('#x3y5,#x4y5,#x7y5,#x8y5,#x3y6,#x4y6,#x7y6,#x8y6').css('background-color','black');
	for(i=4;i<=5;i++){
		for(i2=2;i2<=3;i2++){
			information.gameState[i2][i]={owner:3,content:"wall",revealed:"no"};
			information.gameState[i2+4][i]={owner:3,content:"wall",revealed:"no"};
		}
	}				//both colour the tiles that are impassable and fill in their data on the game board	
});						

function stringify(obj){
	arr="";
	for(var el of obj){
		for(var el2 of el){
			for(var ind in el2){
				arr+=el2[ind];
			}
		}
	}
	return arr;
}		//turn game board into string as to easily check if it is equal to the memory of the game board
		
AI.setup = function(){
	AI.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};
	for(x=0;x<10;x++){
		for(y=0;y<4;y++){
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
	}
	examine(information.gameState);
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

AI.turn = function(){
	AI.threat=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({threat:0,"bestAbility":"","abilityLocation":{"x":"","y":""}});
		}
		AI.threat.push(value);
	}
	threats=[];
	information.turn=true;
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
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
					AI.threat[x][y]['threat']-=AI.findFlagDistance(x,y,"yes");
					AI.threat[x][y]['threat']-=0.5*AI.findFlagDistance(x,y,"semi");
					AI.threat[x][y]['threat']-=0.1*AI.findFlagDistance(x,y,"no");
					if(information.gameState[x][y]['content']!="b"){
						AI.threat[x][y]['threat']+=AI.surroundingPieces(x,y);
					}
					abilities=[];
					for(x2=0;x2<10;x2++){
						for(y2=0;y2<10;y2++){
							if(information.gameState[x2][y2]['owner']==2){
								if(information.gameState[x2][y2]['content']!="b" && information.gameState[x2][y2]['content']!="f"){
									ability=10;
									ability-=AI.findDistance(x2,y2,x,y,"yes");
									ability-=0.5*AI.findDistance(x2,y2,x,y,"semi");
									ability-=0.1*AI.findDistance(x2,y2,x,y,"no");
									abilities.push({"ability":ability,"location":{"x":x2,"y":y2}});
								}
							}
						}
					}
					test=[];
					for(i=0;i<=abilities.length-1;i++){
						test.push(abilities[i]["ability"]);
					}
					console.log(Math.max.apply(null,test));
					AI.threat[x][y]["threat"]*=test.max;
					for(i=0;i<abilities.length;i++){
						if(abilities[i]["ability"]==test.max){
							AI.threat[x][y]["abilityLocation"]=ability[i]["location"];
							break;
						}
					}
					threats.push(AI.threat[x][y]["threat"]);
				}
			}
		}
	}
	for(x=0;x<10;x++){
		for(y=0;y<10;y++){
			if(AI.threat[x][y]["threat"]==threats.max){
				console.log("hallo");
			}
		}
	}
}

AI.findDistance = function(x2,y2,x,y,clear){
	reach=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({distance:"none",reachable:false});
		}
		reach.push(value);
	}
	reach[x2][y2]={distance:0,reachable:true};
	memory="";
	while(memory!=stringify(reach)){
		memory=stringify(reach);
		for(x3=0;x3<10;x3++){
			for(y3=0;y3<10;y3++){
				if(!reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']==0 && clear=="yes" || !reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']!="wall" && clear=="no"){
					distances=[];
					if(typeof reach[x3-1]!=="undefined"){
						if(reach[x3-1][y3]["reachable"]){
							distances.push(reach[x3-1][y3]["distance"]);
						}
					}
					if(typeof reach[x3+1]!=="undefined"){
						if(reach[x3+1][y3]["reachable"]){
							distances.push(reach[x3+1][y3]["distance"]);
						}
					}
					if(typeof reach[x3][y3-1]!=="undefined"){
						if(reach[x3][y3-1]["reachable"]){
							distances.push(reach[x3][y3-1]["distance"]);
						}
					}
					if(typeof reach[x3][y3+1]!=="undefined"){
						if(reach[x3][y3+1]["reachable"]){
							distances.push(reach[x3][y3+1]["distance"]);
						}
					}
					if(distances.length!=0){
						reach[x3][y3]={distance:Math.min.apply(Math,distances)+1,reachable:true};
					}
				}
				else if(clear=="semi" && information.gameState[x3][y3]['content']!="b" && information.gameState[x3][y3]['content']!="f"){
					if(parseInt(information.gameState[x2][y2]['content'])>parseInt(information.gameState[x3][y3]['content']) || information.gameState[x3][y3]['content']==0){
						distances=[];
						if(typeof reach[x3-1]!=="undefined"){
							if(reach[x3-1][y3]["reachable"]){
								distances.push(reach[x3-1][y3]["distance"]);
							}
						}
						if(typeof reach[x3+1]!=="undefined"){
							if(reach[x3+1][y3]["reachable"]){
								distances.push(reach[x3+1][y3]["distance"]);
							}
						}
						if(typeof reach[x3][y3-1]!=="undefined"){
							if(reach[x3][y3-1]["reachable"]){
								distances.push(reach[x3][y3-1]["distance"]);
							}
						}
						if(typeof reach[x3][y3+1]!=="undefined"){
							if(reach[x3][y3+1]["reachable"]){
								distances.push(reach[x3][y3+1]["distance"]);
							}
						}
						if(distances.length!=0){
							reach[x3][y3]={distance:Math.min.apply(Math,distances)+1,reachable:true};
						}
					}
				}
			}
		}
	}
	distances=[];
	if(typeof reach[x-1] !== "undefined"){
		if(reach[x-1][y]["reachable"]){
			distances.push(reach[x-1][y]["distance"]);
		}
	}
	if(typeof reach[x+1] !== "undefined"){
		if(reach[x+1][y]["reachable"]){
			distances.push(reach[x+1][y]["distance"]);
		}
	}
	if(typeof reach[x][y-1] !== "undefined"){
		if(reach[x][y-1]["reachable"]){
			distances.push(reach[x][y-1]["distance"]);
		}
	}
	if(typeof reach[x][y+1] !== "undefined"){
		if(reach[x][y+1]["reachable"]){
			distances.push(reach[x][y+1]["distance"]);
		}
	}
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
	reach=[];
	for(i=0;i<10;i++){
		value=[];
		for(i2=0;i2<10;i2++){
			value.push({distance:"none",reachable:false});
		}
		reach.push(value);
	}
	reach[x][y]={distance:0,reachable:true};
	memory="";
	while(memory!=stringify(reach)){
		memory=stringify(reach);
		for(x2=0;x2<10;x2++){
			for(y2=0;y2<10;y2++){
				if(!reach[x2][y2]["reachable"] && information.gameState[x2][y2]['content']==0 && clear=="yes" || !reach[x2][y2]["reachable"] && information.gameState[x2][y2]['content']!="wall" && clear=="no"){
					distances=[];
					if(typeof reach[x2-1]!=="undefined"){
						if(reach[x2-1][y2]["reachable"]){
							distances.push(reach[x2-1][y2]["distance"]);
						}
					}
					if(typeof reach[x2+1]!=="undefined"){
						if(reach[x2+1][y2]["reachable"]){
							distances.push(reach[x2+1][y2]["distance"]);
						}
					}
					if(typeof reach[x2][y2-1]!=="undefined"){
						if(reach[x2][y2-1]["reachable"]){
							distances.push(reach[x2][y2-1]["distance"]);
						}
					}
					if(typeof reach[x2][y2+1]!=="undefined"){
						if(reach[x2][y2+1]["reachable"]){
							distances.push(reach[x2][y2+1]["distance"]);
						}
					}
					if(distances.length!=0){
						reach[x2][y2]={distance:Math.min.apply(Math,distances)+1,reachable:true};
					}
				}
				else if(clear=="semi" && information.gameState[x2][y2]['content']!="b" && information.gameState[x2][y2]['content']!="f"){
					if(parseInt(information.gameState[x][y]['content'])>parseInt(information.gameState[x2][y2]['content']) || information.gameState[x2][y2]['content']==0){
						distances=[];
						if(typeof reach[x2-1]!=="undefined"){
							if(reach[x2-1][y2]["reachable"]){
								distances.push(reach[x2-1][y2]["distance"]);
							}
						}
						if(typeof reach[x2+1]!=="undefined"){
							if(reach[x2+1][y2]["reachable"]){
								distances.push(reach[x2+1][y2]["distance"]);
							}
						}
						if(typeof reach[x2][y2-1]!=="undefined"){
							if(reach[x2][y2-1]["reachable"]){
								distances.push(reach[x2][y2-1]["distance"]);
							}
						}
						if(typeof reach[x2][y2+1]!=="undefined"){
							if(reach[x2][y2+1]["reachable"]){
								distances.push(reach[x2][y2+1]["distance"]);
							}
						}
						if(distances.length!=0){
							reach[x2][y2]={distance:Math.min.apply(Math,distances)+1,reachable:true};
						}
					}
				}
			}
		}
	}
	distances=[];
	if(typeof reach[AI.flagLocation["x"]-1] !== "undefined"){
		if(reach[AI.flagLocation["x"]-1][AI.flagLocation["y"]]["reachable"]){
			distances.push(reach[AI.flagLocation["x"]-1][AI.flagLocation["y"]]["distance"]);
		}
	}
	if(typeof reach[AI.flagLocation["x"]+1] !== "undefined"){
		if(reach[AI.flagLocation["x"]+1][AI.flagLocation["y"]]["reachable"]){
			distances.push(reach[AI.flagLocation["x"]+1][AI.flagLocation["y"]]["distance"]);
		}
	}
	if(typeof reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1] !== "undefined"){
		if(reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1]["reachable"]){
			distances.push(reach[AI.flagLocation["x"]][AI.flagLocation["y"]-1]["distance"]);
		}
	}
	if(typeof reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1] !== "undefined"){
		if(reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1]["reachable"]){
			distances.push(reach[AI.flagLocation["x"]][AI.flagLocation["y"]+1]["distance"]);
		}
	}
	if(distances.length==0){
		return 0;
	}
	else{
		return	Math.min.apply(Math,distances);
	}
}
	
game.typeChange= function(thing){			//change the type of piece you place when clicking a tile
	information.type=thing;
};						

			
$(function(){
	$(".tile1").click(function(){
		game.click()
	})
});			//make all game board tiles clickable
			
game.click = 	function() {				//process clicks on tiles
    id2="#"+event.target.id;		//obtain id
	test=id2.split("");
	if(test[test.length-1]!="b"){
		id2=id2+"b";
	}		//make sure the span inside of the clicked div is targeted, if it isn't already
	game.click2(id2);
}
			
game.click2= function(id2) {
	information.id2=id2;
    information.id=id2.substring(0,id2.length-1);
	proc=information.id2.split("y");
	x=proc[0].replace(/\D/g,'');
	y=proc[1].replace(/\D/g,'');			//obtain x and y positional values
	if(information.gameStage =="setup"){
		if((y>6 && information.playerNumber==1 )|| (y<5 && information.playerNumber==2)){		//make sure pieces can only be manipulated on the right side of the board
			if(information.stock[information.type]>0 && information.gameState[x-1][y-1]['content']==""){	//if tile is empty and you still have pieces available of the desired type...
				information.gameState[x-1][y-1]={owner:information.playerNumber,content:information.type,revealed:"no"};		//introduce the new piece on the game board
                examine(information.gameState);			//update the game board visually
				information.stock[information.type]--;			//reduce amount of remaining pieces
				$("#remaining"+information.type).html("remaining:"+information.stock[information.type]);	//update remaining pieces counter visually
			}
			else{
				for(i2=1;i2<=10;i2++){
					if(information.gameState[x-1][y-1]['content']==i2){		//if the tile is already occupied remove that piece
                        information.gameState[x-1][y-1]={owner:0,content:"",revealed:"no"};		//remove the piece from the game board
                        examine(information.gameState);		//update the game board visually
						information.stock[i2]++;						//increase the amount of remaining pieces
						$("#remaining"+i2).html("remaining:"+information.stock[i2]);		//update the remaining pieces counter visually
					}
				}
				if(information.gameState[x-1][y-1]['content']=="f"){			//same as the previous except for flags
                    information.gameState[x-1][y-1]={owner:0,content:"",revealed:"no"};
                    examine(information.gameState);
					information.stock['f']++;
					$("#remainingf").html("remaining:"+information.stock['f']);
				}
				if(information.gameState[x-1][y-1]['content']=="b"){			//same as the previous except for bombs
                    information.gameState[x-1][y-1]={owner:0,content:"",revealed:"no"};
                    examine(information.gameState);
					information.stock['b']++;
					$("#remainingb").html("remaining:"+information.stock['b']);
				}
			}
		}
	}
    else if(information.gameStage=="main"){
        if(information.turn){		//if it is this player's turn
            x2=information.selected[0];
            y2=information.selected[1];				//obtain positional values of the selected tile
            if(information.gameState[x-1][y-1]['owner']==information.playerNumber && information.gameState[x-1][y-1]['content']!="b" && information.gameState[x-1][y-1]['content']!="f"){		//if clicking on a owned tile that is neither a flag nor a bomb
                $("#x"+x+"y"+y).css("background-color","green");			//colour the newly selected tile
                if(information.selected!=""){		//if a previous tile was already selected
                    if(information.selected[0]!=x-1 || information.selected[1]!=y-1){
                        if(information.playerNumber==1){
                            $("#x"+(information.selected[0]+1)+"y"+(information.selected[1]+1)).css("background-color","blue");
                        }
                        else{
                            $("#x"+(information.selected[0]+1)+"y"+(information.selected[1]+1)).css("background-color","red")
                        }
                    }				//change that tiles colour back
                }
                information.selected=[x-1,y-1];		//select the new tile
            }
            test="yes";
            if(y2==y-1 && x2<x-1){
                for(i=x2+1;i<x-1;i++){
                    if(information.gameState[i][y2]['content']!=""){
                        test="no";
                    }
                }
            }
            else if(y2==y-1 && x-1<x2){
				for(i=x;i<x2;i++){
					if(information.gameState[i][y2]['content']!=""){
                        test="no";
                    }
                }
            }
            else if(x2==x-1 && y-1>y2){
                for(i=y2+1;i<y-1;i++){
                    if(information.gameState[x2][i]['content']!=""){
                        test="no";
                    }
                }
            }
            else if(x2==x-1 && y-1<y2){
                for(i=y;i<y2;i++){
                    if(information.gameState[x2][i]['content']!=""){
                        test="no";
                    }
                }
            }			//only applicable if trying to move a scout. The path from the scout to its destination must be unobstructed.
            if((x2==x || x2+2==x) && y2+1==y || x2+1==x && (y2==y || y2+2==y) || (x2==x-1 || y2==y-1) && information.gameState[x2][y2]['content']==2 && test=="yes"){		//if the clicked tile is adjacent to the selected tile or it is an allowed move made by a scout
				if(information.gameState[x-1][y-1]['owner']==0 && information.selected!=""){		//if the target tile is empty and this player has selected a tile
                    information.turn=false;			//end this player's turn
                    information.gameState[x-1][y-1]=information.gameState[x2][y2];		//occupy the target tile
                    information.gameState[x2][y2]={owner:0,content:"",revealed:"no"};			//empty the selected tile
                    examine(information.gameState);			//update the game board visually
                    information.selected="";			//remove the selection
				}
				else if(((information.gameState[x-1][y-1]['owner']==1 && information.playerNumber!=1) || (information.gameState[x-1][y-1]['owner']==2 && information.playerNumber!=2)) && information.selected!=""){		//if the target tile is occupied by an opponent and this player has selected a tile
					game.attack();
				}
				setTimeout(function(){AI.turn()},1000);
			}
		}
    }
}
			
game.attack = function(){
    strengtha = parseInt(information.gameState[x2][y2]['content']);		//determine the strength of the attacker
    information.selected = "";				//clear the selection
    information.turn = false;				//end this player's turn
    if (information.gameState[x - 1][y - 1]['content'] != "b" && information.gameState[x - 1][y - 1]['content'] != "f"){
        strengthd = parseInt(information.gameState[x - 1][y - 1]['content']);
    }				//in the case the targeted tile is neither a bomb nor a flag determine its strength
    if(information.gameState[x - 1][y - 1]['content'] == "b"){
        if(strengtha==3){
            strengthd=0;
        }
		else{
			strengthd=11;
		}
    }			//if the target tile is a bomb make sure it is stronger than all attackers, except the miner
    if(information.gameState[x - 1][y - 1]['content'] == "f"){
        game.endGame(information.playerNumber);
    }				//if the flag is captured end the game
    if(strengtha==1 && strengthd==10){
        strengtha=11;
    }			//if a spy attacks the marshal make the spy stronger
	if (strengtha < strengthd){			//if the attacker's strength is lower than the defender's
        information.pieces[information.playerNumber-1]+=information.gameState[x2][y2]['content'];			//place the attacker's piece beside the board
        information.gameState[x2][y2] = {owner:0, content:"",revealed:"no"};		//empty the attacking tile
        information.gameState[x-1][y-1]['revealed']="yes";			//reveal the defender
        examine(information.gameState);				//update the game board visually
    }
    else if (strengtha > strengthd){				//if the attacker's strength exceeds the defender's
		if(information.playerNumber==1){
			information.pieces[1]+=information.gameState[x-1][y-1]['content'];
		}
		else{
			information.pieces[0]+=information.gameState[x-1][y-1]['content'];
		}			//place the defender's piece in the correct place beside the board
        information.gameState[x - 1][y - 1] = information.gameState[x2][y2];		//occupy the defender's tile with the attacker's piece
        information.gameState[x - 1][y - 1]['revealed']="yes";				//reveal the attacker's piece
        information.gameState[x2][y2] = {owner:0, content:"",revealed:"no"};		//empty the attacker's tile
        examine(information.gameState);			//update the gameboard visually
    }
    else{			//if both strength's equal each other
		information.pieces[information.playerNumber-1]+=information.gameState[x2][y2]['content'];
		if(information.playerNumber==1){
			information.pieces[1]+=information.gameState[x-1][y-1]['content'];
		}
		else{
			information.pieces[0]+=information.gameState[x-1][y-1]['content'];
		}			//place both pieces beside the board
        information.gameState[x - 1][y - 1] = {owner:0, content:"", revealed:"no"};	
        information.gameState[x2][y2] = {owner:0, content:"", revealed:"no"};		//empty both tiles
        examine(information.gameState);		//update the game board visually
    }
}			//process an attack		
			
game.endSetup = function(){
	sum=0;
	i=1;
	while(typeof information.stock[i] !== 'undefined'){
		sum+=information.stock[i];
		i++;
	}
	sum+=information.stock['f']+information.stock['b'];
	//if(sum==0){			//make sure there aren't any pieces left to place
        information.gameStage="main";
        $("#si").css("display","none");
	//}
	AI.setup();
}
		
game.endGame= function(winner){
    $("#win").html("player "+winner+" has won");
    $(".all").css("display","none");
}

function testExamine(reach){
	for(x3=0;x3<10;x3++){
		for(y3=0;y3<10;y3++){
			if(reach[x3][y3]["reachable"]){
				$("#x"+(x3+1)+"y"+(y3+1)).css("background-color","purple");
			}
		}
	}
}
	
function examine(vari){
    $("#pieces1").html(information.pieces[0]);
    $("#pieces2").html(information.pieces[1]);
	for(i=0;i<10;i++){
		for(i1=0;i1<10;i1++){
			id="#x"+(i+1)+"y"+(i1+1)+"b";
            id2="#x"+(i+1)+"y"+(i1+1);
            //if(vari[i][i1]['owner']!=information.playerNumber && vari[i][i1]['owner']!=0 && vari[i][i1]['revealed']=="no"){
			//	$(id).html("?");		
            //}
            //else{
                $(id).html(vari[i][i1]['content']);
            //}
            if(vari[i][i1]['owner']==1){
                $(id2).css("background-color","blue");
            }
            else if(vari[i][i1]['owner']==2){
                $(id2).css("background-color","red");
            }
            else if(vari[i][i1]['owner']==0){
                $(id2).css("background-color","white");
            }
		}
	}
}			//visually update the game board with information from the array supplied to the function
                
                
                
                
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                //the jumping of tiles after reloading the page is the result of loading the last move made by THAT player and then obtaining a move by the other player
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!