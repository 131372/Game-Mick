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

			boardSetup= new Object();
			information= new Object();
			
			$(function(){
				information.playerName=$("#name").html();
				information.owner=$("#owner").html();
				information.gameStage="setup";
				if(information.playerName==information.owner){
					information.playerNumber="1";
				}
				else{
					information.playerNumber="2";
				}
				console.log("player:"+information.playerNumber);
			});			//obtain the player name and the name of the owner which for now is used to determine which side of the board belongs to this player
			
			
			
			
			
			boardSetup.typeChange= function(thing){			//change the type of piece you place when clicking a tile
				boardSetup.type=thing;
			};						
			boardSetup.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};		//the number of available pieces per type
			
			$(function(){
				$(".tile1").click(function(){
					boardSetup.click()
				})
			});			//make all game board tiles clickable
			
			boardSetup.click = 	function() {				//process clicks on tiles
									id2="#"+event.target.id;		//obtain id for span within div
									test=id2.split("");
									if(test[test.length-1]!="b"){
										id2=id2+"b";
									}		//make sure the span inside of the clicked div is targeted, if it isn't already
									boardSetup.click2(id2);
								}
			
			boardSetup.click2= function(id2) {
				boardSetup.id2=id2;
				proc=boardSetup.id2.split("y");
				x=proc[0].replace(/\D/g,'');
				y=proc[1].replace(/\D/g,'');			//obtain x and y positional values
				if((y>6 && information.playerNumber==1 )|| (y<5 && information.playerNumber==2)){		//make sure pieces can only be manipulated on the right side of the board
					if(boardSetup.stock[boardSetup.type]>0 && information.gameState[x-1][y-1]['content']==""){	//if tile is empty and you still have pieces available of the desired type...
						information.gameState[x-1][y-1]['content']=boardSetup.type;		//introduce the new piece on the game board
						$(boardSetup.id2).html(boardSetup.type);		//place piece on board visually
						boardSetup.stock[boardSetup.type]--;			//reduce amount of remaining pieces
						$("#remaining"+boardSetup.type).html("remaining:"+boardSetup.stock[boardSetup.type]);	//update remaining pieces counter visually
					}
					else{
						for(i=1;i<=10;i++){
							if(information.gameState[x-1][y-1]['content']==i){		//if the tile is already occupied remove that piece
								$(boardSetup.id2).html("");					//visually remove the piece
								boardSetup.stock[i]++;						//increase the amount of remaining pieces
								$("#remaining"+i).html("remaining:"+boardSetup.stock[i]);		//update the remaining pieces counter visually
								information.gameState[x-1][y-1]['content']="";				//remove the piece from the game board
							}
						}
						if(information.gameState[x-1][y-1]['content']=="f"){			//same as the previous except for flags
							$(boardSetup.id2).html("");
							boardSetup.stock['f']++;
							$("#remainingf").html("remaining:"+boardSetup.stock['f']);
							information.gameState[x-1][y-1]['content']="";
						}
						if(information.gameState[x-1][y-1]['content']=="b"){			//same as the previous except for bombs
							$(boardSetup.id2).html("");
							boardSetup.stock['b']++;
							$("#remainingb").html("remaining:"+boardSetup.stock['b']);
							information.gameState[x-1][y-1]['content']="";
						}
					}
				}
			}
			
			information.gameState=[];
			

			for(i=0;i<10;i++){
							value=[];
			for(i1=0;i1<10;i1++){
				value2={owner:0,content:""};
				value.push(value2);
			}
				information.gameState.push(value);
			}			//create a blank game board
			information.gameStateMemory=information.gameState;	//make a copy of said game board
			
			
            function end() {
                $("#dummy").load("endgame.php");
                window.location.href = 'lobby.php';
            }
			$(document).ready(function(){
				$('#x3y5,#x4y5,#x7y5,#x8y5,#x3y6,#x4y6,#x7y6,#x8y6').css('background-color','black');
				for(i=4;i<=5;i++){
					for(i2=2;i2<=3;i2++){
						information.gameState[i2][i]={owner:0,content:"wall"};
						information.gameState[i2+4][i]={owner:0,content:"wall"};
					}
				}
			});				//both colour the tiles that are impassable and fill in their data on the game board
			
			function send(){
				$.post( "gameState.php", {waarde:information.gameState}, function( data ) {
					console.log(data);
				});
			}				//send the game-state to the server
			
			function obtain(){
				$.post( "gameState.php", {waarde:"obtain"}, function( data ) {
					data=$.parseJSON(data);			
					console.log(data);
					information.gameState=data;		
					examine(information.gameState);		
					console.log(information.gameState[0][0]);
				});
			}				//obtain the game-state from the server
			
			information.setupMemory="1";
			information.obatin=true;
			
			setInterval(function() {
				if(information.obatin){
					$.post( "gameState.php", {waarde:"obtain"}, function( data ) {
						if(data!="empty"){			//only attempt to update anything if the server had any information to obtain and the information there is a move made by opponent
							data=$.parseJSON(data);		//convert the obtained information into an array
							if(information.gameStateMemory!=data){		//don't attempt to update the game board if a new move hasn't been made since we last obtained a move
								information.gameStateMemory=information.gameState=data;			//update the local game-state and make a copy
								examine(information.gameState);		//update the game board visually
								if(information.playerNumber==2 && information.setupMemory==1){
									$("#si").css("display","block");
									information.setupMemory=0;
								}
							}
						}
					});
				}
            },1000)					//regularly obtain the game-state
			
			boardSetup.endSetup = function(){
				sum=0;
				i=1;
				while(typeof boardSetup.stock[i] !== 'undefined'){
					sum+=boardSetup.stock[i];
					i++;
				}
				sum+=boardSetup.stock['f']+boardSetup.stock['b'];
				if(sum==0){			//make sure there aren't any pieces left to place
					send();
					$("#si").css("display","none");
					if(information.setupMemory==0){
						information.gameStage="main";
					}
				}
				else{
				}
			}
		
		
		function examine(vari){
			for(i=0;i<10;i++){
				for(i1=0;i1<10;i1++){
					id="#x"+(i+1)+"y"+(i1+1)+"b";
					$(id).html(vari[i][i1]['content']);		//visually update the game board with information from the array supplied to the function
				}
			}
		}