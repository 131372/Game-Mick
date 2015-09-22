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
			
information.pieces=["",""];			//keeps track of the defeated pieces that are kept beside the board
information.selected="";			//keeps track of the currently selected piece (which piece the player uses to execute a move)
information.gameStage="setup";		//keeps track of the game stage (setup or main)
information.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};		//the number of available pieces per type
information.gameState=[];		//the entire game board

for(i=0;i<10;i++){
	value=[];
	for(i1=0;i1<10;i1++){
		value2={owner:0,content:"",revealed:"no"};
		value.push(value2);
	}
	information.gameState.push(value);
}			//create a blank game board			

function castBoolean(bool){
	if(bool===true){
		return true;
	}
	else if(bool==="true"){
		return true;
	}
	else{
		return false;
	}
}		//simple function that casts strings to bools (necessary because one bool apparently changes to a string, most likely due to storing it in the database in a suboptimal way)

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
		
$(function(){
	$('#x3y5,#x4y5,#x7y5,#x8y5,#x3y6,#x4y6,#x7y6,#x8y6').css('background-color','black');
	for(i=4;i<=5;i++){
		for(i2=2;i2<=3;i2++){
			information.gameState[i2][i]={owner:3,content:"wall",revealed:"no"};
			information.gameState[i2+4][i]={owner:3,content:"wall",revealed:"no"};
		}
	}				//both colour the tiles that are impassable and fill in their data on the game board
});					
			
game.typeChange= function(thing){			//change the type of piece you place when clicking a tile
	information.type=thing;
};						

			
$(function(){
	$(".tile1").click(function(){
		game.click()
	})
});			//make all game board tiles clickable
			
game.click = function() {				//process clicks on tiles
    id2="#"+event.target.id;		//obtain id
	test=id2.split("");
	if(test[test.length-1]!="b"){
		id2=id2+"b";
	}		//make sure the span inside of the clicked div is targeted, if it isn't already
	game.click2(id2);
}
			
game.click2 = function(id2) {
	proc=id2.split("y");
	x=proc[0].replace(/\D/g,'');
	y=proc[1].replace(/\D/g,'');			//obtain x and y positional values
	if(information.gameStage =="setup"){
		game.setup(x,y);
	}
    else if(information.gameStage=="main"){
		game.main(x,y);
    }
}

game.main = function(x,y){
    if(information.turn){		//if it is this player's turn
        x2=information.selected[0];
        y2=information.selected[1];				//obtain positional values of the selected tile
        if(tests.ownedMoveable(x,y)){		//if clicking on an owned tile that is neither a flag nor a bomb
            game.select(x,y);			//select that tile
        }
        else if(tests.allowedMovement(x,y,x2,y2)){		//if the clicked tile is adjacent to the selected tile or it is an allowed move made by a scout
			if(information.gameState[x-1][y-1]['owner']==0 && information.selected!=""){		//if the target tile is empty and this player has selected a tile
                game.move(x,y,x2,y2);			//move the selected piece to the new location
			}
			else if(tests.canAttack(x,y)){		//if the target tile is occupied by an opponent and this player has selected a tile
				game.attack(x,y,x2,y2);			//attack target piece
				tests.detectWinByDefault();
			}
			if(castBoolean(information.AIgame)){		//if the game being played is against the AI
				setTimeout(function(){AI.turn()},1000);		//let the AI make its move (after a small delay, as to make sure the player saw what happened)
			}
		}
	}
}

game.move = function(x,y,x2,y2){
	information.turn=false;			//end this player's turn
    information.gameState[x-1][y-1]=information.gameState[x2][y2];		//occupy the target tile
    information.gameState[x2][y2]={owner:0,content:"",revealed:"no"};			//empty the selected tile
    game.examine(information.gameState);			//update the game board visually
    information.selected="";			//remove the selection
	if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
		send();				//send the move to the server
	}
}

game.select = function(x,y){
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

game.setup = function(){
	if(tests.boardSide(y)){		//make sure pieces can only be manipulated on the right side of the board
		if(information.stock[information.type]>0 && information.gameState[x-1][y-1]['content']==""){	//if tile is empty and you still have pieces available of the desired type...
			game.placePiece(x,y);		//place a new piece
		}
		else{
			for(i2=1;i2<=10;i2++){
				game.removePiece(x,y,i2);
			}
			game.removePiece(x,y,"f");
			game.removePiece(x,y,"b");
		}			//remove the piece that was previously there
	}
}

game.removePiece = function(x,y,piece){
	if(information.gameState[x-1][y-1]['content']==piece){		//if the tile is already occupied...
        information.gameState[x-1][y-1]={owner:0,content:"",revealed:"no"};		//remove the piece from the game board
        game.examine(information.gameState);		//update the game board visually
		information.stock[piece]++;						//increase the amount of remaining pieces
		$("#remaining"+piece).html("remaining:"+information.stock[piece]);		//update the remaining pieces counter visually
	}	
}

game.placePiece = function(x,y){
	information.gameState[x-1][y-1]={owner:information.playerNumber,content:information.type,revealed:"no"};		//introduce the new piece on the game board
    game.examine(information.gameState);			//update the game board visually
	information.stock[information.type]--;			//reduce amount of remaining pieces
	$("#remaining"+information.type).html("remaining:"+information.stock[information.type]);	//update remaining pieces counter visually
}
	
game.attack = function(x,y,x2,y2){
    strengtha = parseInt(information.gameState[x2][y2]['content']);		//determine the strength of the attacker
    information.selected = "";				//clear the selection
    information.turn = false;				//end this player's turn
    if(tests.moveable(x,y)){
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
		return;
    }				//if the flag is captured end the game
    if(strengtha==1 && strengthd==10){
        strengtha=11;
    }			//if a spy attacks the marshal make the spy stronger
	if (strengtha < strengthd){			//if the attacker's strength is lower than the defender's
        game.attackerLoss(x,y,x2,y2);		//process the loss of the attacker
    }
    else if (strengtha > strengthd){				//if the attacker's strength exceeds the defender's
		game.attackerWin(x,y,x2,y2);			//process the victory of the attacker
    }
    else{			//if both strength's equal each other
		game.attackTie(x,y,x2,y2);			//process the tie
    }
}

game.attackTie = function(x,y,x2,y2){
	information.pieces[information.playerNumber-1]+=information.gameState[x2][y2]['content'];		//place the attackers piece beside the board
	game.defenderPiece(x,y);			//place the defenders piece beside the board
    information.gameState[x - 1][y - 1] = {owner:0, content:"", revealed:"no"};	
    information.gameState[x2][y2] = {owner:0, content:"", revealed:"no"};		//empty both tiles
    game.examine(information.gameState);		//update the game board visually
    if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
		send();				//send the move to the server
	}
}

game.defenderPiece = function(x,y){
	if(information.playerNumber==1){
		information.pieces[1]+=information.gameState[x-1][y-1]['content'];
	}
	else{
		information.pieces[0]+=information.gameState[x-1][y-1]['content'];
	}
}			//place a piece beside the board

game.attackerWin = function(x,y,x2,y2){
	game.defenderPiece(x,y);			//place the defender's piece in the correct place beside the board
    information.gameState[x - 1][y - 1] = information.gameState[x2][y2];		//occupy the defender's tile with the attacker's piece
    information.gameState[x - 1][y - 1]['revealed']="yes";				//reveal the attacker's piece
    information.gameState[x2][y2] = {owner:0, content:"",revealed:"no"};		//empty the attacker's tile
    game.examine(information.gameState);			//update the gameboard visually
    if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
		send();				//send the move to the server
	}
}

game.attackerLoss = function(x,y,x2,y2){
	information.pieces[information.playerNumber-1]+=information.gameState[x2][y2]['content'];			//place the attacker's piece beside the board
    information.gameState[x2][y2] = {owner:0, content:"",revealed:"no"};		//empty the attacking tile
    information.gameState[x-1][y-1]['revealed']="yes";			//reveal the defender
    game.examine(information.gameState);				//update the game board visually
	if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
		send();				//send the move to the server
	}
}
		
game.endSetup = function(){
	//if(tests.emptyStock()){			//make sure there aren't any pieces left to place
        information.gameStage="main";		//change the current game stage
        $("#si").css("display","none")		//hide the setup interface
		if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
			send();				//send the setup to the server
		}
		else{
			AI.setup();			//otherwise make the AI set up its side of the board
		}
	//}
}
		
game.endGame= function(winner){
	if(winner=="tie"){
		$("#win").html("this game ended in a tie")
	}
	else{
		$("#win").html("player "+winner+" has won");			
	}				//display victory message
    $(".all").css("display","none");			//hide almost everything
	if(!castBoolean(information.AIgame)){		//if it is a multiplayer game
		$.post( "gameState.php", {waarde:"end"+winner}, function( data ) {	});		//process the end of the game on the server side
	}
}

game.examineTile = function(vari,i,i2){
	id="#x"+(i+1)+"y"+(i1+1)+"b";
    id2="#x"+(i+1)+"y"+(i1+1);			//obtain the id of the div and the span inside it
    if(vari[i][i1]['owner']!=information.playerNumber && vari[i][i1]['owner']!=0 && vari[i][i1]['revealed']=="no"){		//if the contents of a tile are supposed to be hidden
        $(id).html("?");				//display a question mark
    }
    else{
        $(id).html(vari[i][i1]['content']);		//else display the contents of that tile
    }
    if(vari[i][i1]['owner']==1){
        $(id2).css("background-color","blue");
    }
    else if(vari[i][i1]['owner']==2){
        $(id2).css("background-color","red");
    }
    else if(vari[i][i1]['owner']==0){
        $(id2).css("background-color","white");
    }			//colour the tile depending on the owner
}
	
game.examine = function(vari){
    $("#pieces1").html(information.pieces[0]);
    $("#pieces2").html(information.pieces[1]);		//update the pieces beside the board
	for(i=0;i<10;i++){
		for(i1=0;i1<10;i1++){
			game.examineTile(vari,i,i2);
		}
	}
}			//visually update the game board with information from the array supplied to the function
                
                
                
                
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                //the jumping of tiles after reloading the page is the result of loading the last move made by THAT player and then obtaining a move by the other player
                //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!