information.setupMemory="1";  //used to keep track of whether the setup interface should be displayed
information.obtain=true;		//make sure the server is regularly called upon to update the game
information.AIgame=false;		//state that this is a multiplayer game

$(function(){
	information.gameStateMemory=information.gameState;		//make a copy of the game board
	information.playerName=$("#name").html();
	information.owner=$("#owner").html();
	if(information.playerName==information.owner){
		information.playerNumber="1";
        information.turn=true;
        $("#si").css("display","block");
	}
	else{
		information.playerNumber="2";
        information.turn=false;
	}			//obtain the player name and the name of the owner which, among other things, is used to determine which side of the board belongs to this player. Also make the setup interface visible for the starting player and grant him the first turn
	if(information.obtain){
		$.post( "gameState.php", {waarde:"update"}, function( data ) {
			if(data=="end"){		//if the game has already ended...
				$(".all").css("display","none");		//hide almost everything
				$("#win").html("this game has ended");		//show end of game message
			}			
			else if(data!="empty"){			//if there is information to be obtained
				data=$.parseJSON(data);		//convert the obtained information into an object	
				information=data;		//update the information with what was stored in the server
				game.examine(information.gameState);	//visually apply any changes to the game board
				if(information.gameStage!="setup"){
					$("#si").css("display","none");
				}			//if the setup phase is over hide the setup interface
			}
		});
	}
});			//the first time this should establish who is player one and who is player two, then every time the page reloads check if the game ended and if not update the information object with information from the server 

send = function(){
	if(information.obtain){
		$.post( "gameState.php", {waarde:information}, function( data ) {});
	}
}				//send information to the server

storage = setInterval(function() {
	if(information.obtain){
		$.post( "gameState.php", {waarde:"obtain"}, function( data ) {
			if(data!="empty"){			//only attempt to update anything if the server had any information to obtain and the information there is a move made by opponent
				data=$.parseJSON(data);		//convert the obtained information into an object
				if(data[0][0]=="end"){
					console.log("hallo");
					clearInterval(storage);
					game.endGame(data[0][1]);
					$.post( "gameState.php", {waarde:"destroy"}, function() {});
				}			//if the game has ended stop regularly trying to update
				else{
					turn=data[1];
					if(turn!=information.playerName){
						information.turn=true;
					}			//give this player the next turn
					else{
						information.turn=false;
					}			//this part is redundant I believe, but it can't hurt
					data2=data[0]['gameState'];
					if(information.gameStateMemory!=stringify(data2)){		//don't attempt to update the game board if a new move hasn't been made since we last obtained a move
						information.gameState=data2;			//update the local game-state and make a copy
						information.gameStateMemory=stringify(information.gameState);		//update the memory
						information.pieces=data[0]['pieces'];		//update the pieces beside the board
						game.examine(information.gameState);		//update the game board visually
						if(information.playerNumber==2 && information.setupMemory==1){
							$("#si").css("display","block");
							information.setupMemory=0;
						}			//make the setup interface visible if necessary
					}
				}
			}
		});
	}
},1000)					//regularly obtain the game-state