information.setupMemory="1";
information.obtain=true;
information.AIgame=false;

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
	}			//obtain the player name and the name of the owner which, among other things, is used to determine which side of the board belongs to this player. Also hid
    if(information.obtain){
		$.post( "gameState.php", {waarde:"update"}, function( data ) {
			if(data=="end"){
				$(".all").css("display","none");
				$("#win").html("this game has ended");
			}			//check if the game has already ended
			else if(data!="empty"){
				data=$.parseJSON(data);			
				information=data;
				game.examine(information.gameState);	
				if(information.gameStage!="setup"){
					$("#si").css("display","none");
				}
			}			//retrieve information from the server, looking for the last move made by this player, and use this to update the information object.
		});
	}
});

send = function(){
	if(information.obtain){
		$.post( "gameState.php", {waarde:information}, function( data ) {});
	}
}				//send the information to the server

storage = setInterval(function() {
	if(information.obtain){
		$.post( "gameState.php", {waarde:"obtain"}, function( data ) {
			if(data[1]=="e" && data[2]=="n" && data[3]=="d"){
				clearInterval(storage);
			}
			else if(data!="empty"){			//only attempt to update anything if the server had any information to obtain and the information there is a move made by opponent
				data=$.parseJSON(data);		//convert the obtained information into an array
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
					information.pieces=data[0]['pieces'];		//update the piecse beside the board
					game.examine(information.gameState);		//update the game board visually
					if(information.playerNumber==2 && information.setupMemory==1){
						$("#si").css("display","block");
						information.setupMemory=0;
					}			//make the setup interface visible if necessary
				}
			}
		});
	}
},1000)					//regularly obtain the game-state