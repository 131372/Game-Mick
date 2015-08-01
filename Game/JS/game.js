/*			var pressed=0;
            function worker() {
          //      $("body").load("game.php");
            }

            setTimeout(worker, 1000);
			board=[];
			for(i=0;i<10;i++){
				board.push([]);
			}
			for(i=0;i<10;i++){
				for(i1=0;i1<10;i1++)
					board[i1].push({owner:0,type:0});
			}
			
            function increase() {
				if(pressed==0){
					$("#dummy").load("increase.php");
					pressed=1;
				}
            }

            function decrease() {
				if(pressed==0){
					$("#dummy").load("decrease.php");
					pressed=1;
				}
            }
			*/
			boardSetup= new Object();
			
			boardSetup.typeChange= function(thing){			//change te type of piece you place when clicking a tile
				boardSetup.type=thing;
			};						
			boardSetup.stock={ f:1 , b:6 , 1:1 , 2:8 , 3:5 , 4:4 , 5:4 , 6:4 , 7:3 , 8:2 , 9:1 , 10:1};		//the number of available pieces per type
			
			$(function(){
				$(".tile1").click(function(){
					boardSetup.click()
				})
			});			//make all gameboard tiles clickable
			
			boardSetup.click = 	function() {				//process clicks on tiles
									boardSetup.id2="#"+event.target.id+"b";		//obtain id for span within div
									proc=boardSetup.id2.split("y");
									x=proc[0].replace(/\D/g,'');
									y=proc[1].replace(/\D/g,'');			//obtain x and y positional values
									console.log("x="+x+"y="+y);
									if(boardSetup.stock[boardSetup.type]>0 && boardSetup.gameState[x-1][y-1]['content']==""){	//if tile is empty and you still have pieces available of the desired type...
										$(boardSetup.id2).html(boardSetup.type);		//place piece on board visually
										boardSetup.stock[boardSetup.type]--;			//reduce amount of remaining pieces
										$("#remaining"+boardSetup.type).html("remaining:"+boardSetup.stock[boardSetup.type]);	//update remaining pieces counter visually
									}
									//else if(boardSetup.gameState[x-1][y-1]['content']!="wall"){
									//	$(boardSetup.id).html("");
									//	boardSetup.stock[boardSetup.gameState[x-1][y-1]['content']]++;
									//	$("#remaining"+boardSetup.gameState[x-1][y-1]['content']).html("remaining:"+boardSetup.gameState[x-1][y-1]['content']);
									//}
								}
			
			boardSetup.gameState=[];
			
			value=[];
			for(i=0;i<10;i++){
				value2={owner:0,content:""};
				value.push(value2);
			}
			for(i=0;i<10;i++){
				boardSetup.gameState.push(value);
			}			//create a blank gameboard
			
            function end() {
                $("#dummy").load("endgame.php");
                window.location.href = 'lobby.php';
            }
			$(document).ready(function(){
				$('#x3y5,#x4y5,#x7y5,#x8y5,#x3y6,#x4y6,#x7y6,#x8y6').css('background-color','black');
				for(i=4;i<=5;i++){
					for(i2=2;i2<=3;i2++){
						boardSetup.gameState[i2][i]={owner:0,content:"wall"};
						boardSetup.gameState[i2+4][i]={owner:0,content:"wall"};
					}
				}
			});				//both colour the tiles that are impassable and fill in their data on the gameboard

		//	$.post( "gameState.php", {waarde:"2"}, function( data ) {
				//alert( "Data Loaded: " + data );
		//	});