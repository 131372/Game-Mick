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