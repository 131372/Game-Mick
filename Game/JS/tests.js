// various tests for use in if statements

tests = new Object();

tests.boardSide = function(y){
	return (y>6 && information.playerNumber==1 ) || (y<5 && information.playerNumber==2);
}		//check if where you're trying to place a new piece on the board is actually on you side of the board

tests.ownedMoveable = function(x,y){
	return information.gameState[x-1][y-1]['owner']==information.playerNumber && information.gameState[x-1][y-1]['content']!="b" && information.gameState[x-1][y-1]['content']!="f";
}			//check if the tile you clicked contains a piece you can select (i.e. it's yours and neither a flag nor a bomb)

tests.allowedScoutMovement = function(x,y,x2,y2){
	test=true;
    if(y2==y-1 && x2<x-1){		//if moving in a straight line in a particular direction
        for(i=x2+1;i<x-1;i++){		//for all tiles along the path being followed
            if(information.gameState[i][y2]['content']!=""){		//test if the tile is unobstructed
                test=false;
            }
        }
    }			//repeat for all other directions
    else if(y2==y-1 && x-1<x2){
		for(i=x;i<x2;i++){
			if(information.gameState[i][y2]['content']!=""){
                test=false;
            }
        }
    }
    else if(x2==x-1 && y-1>y2){
        for(i=y2+1;i<y-1;i++){
            if(information.gameState[x2][i]['content']!=""){
                test=false;
            }
        }
    }
    else if(x2==x-1 && y-1<y2){
        for(i=y;i<y2;i++){
            if(information.gameState[x2][i]['content']!=""){
                test=false;
            }
        }
    }
	return test;
}		//check if the move you're trying to make would be valid if executed by a scout (actually it only checks if the scout's path is unobstructed were it a valid move)

tests.allowedMovement = function(x,y,x2,y2){
	return (x2==x || x2+2==x) && y2+1==y || x2+1==x && (y2==y || y2+2==y) || (x2==x-1 || y2==y-1) && information.gameState[x2][y2]['content']==2 && tests.allowedScoutMovement(x,y,x2,y2);
}		//check if the move you're trying to make is valid

tests.canAttack = function(x,y){
	return ((information.gameState[x-1][y-1]['owner']==1 && information.playerNumber!=1) || (information.gameState[x-1][y-1]['owner']==2 && information.playerNumber!=2)) && information.selected!="";
}		//check if you have a piece selected and are targetting an opponents piece

tests.moveable = function(x,y){
	return information.gameState[x - 1][y - 1]['content'] != "b" && information.gameState[x - 1][y - 1]['content'] != "f";
}		//check if the piece being attacked is a moveable piece

tests.endOfGameCheck = function(){
	end=false;
	$.post( "gameState.php", {waarde:"update"},function(data) {
		if(data=="end"){
			end=true;
			clearInterval(storage);
		}
	});		
	return end;
}		//check if the game has ended

tests.emptyStock = function(){
	sum=0;
	i=1;
	while(typeof information.stock[i] !== 'undefined'){
		sum+=information.stock[i];
		i++;
	}
	sum+=information.stock['f']+information.stock['b'];
	if(sum==0){		//after adding up the amount of remaining pieces in each category check that this total is zero
		return true;
	}
	else{
		return false
	}
}			//check that there are no pieces remaining that should be placed

tests.trueMoveable = function(x,y){
	test=0;
	test+=emptyCheck(x-1,y);
	test+=emptyCheck(x+1,y);
	test+=emptyCheck(x,y-1);
	test+=emptyCheck(x,y+1);			//check all direction for empty tiles
	if(test==4  || !tests.moveable(x+1,y+1)){
		return false;
	}
	else{
		return true;
	}
}		//check that the piece is moveable and has a free tile to move to

function emptyCheck(x,y){
	if(typeof information.gameState[x]!== "undefined"){
		if(typeof information.gameState[x][y]!== "undefined"){
			if(information.gameState[x][y]['content']!=""){
				return 1
			}
		}
	}
	return 0
}		//check if tile is empty

tests.maxAbility = function(){
	test=[];
	for(i=0;i<=AI.abilities.length-1;i++){
		test.push(AI.abilities[i]["ability"]);
	}
	return Math.max.apply(null,test);
}		//check what the highest ability score is

tests.unownedMoveableOrEmpty = function(x3,y3){
	return information.gameState[x3][y3]['content']!="b" && information.gameState[x3][y3]['content']!="f" && information.gameState[x3][y3]['content']!="wall" && information.gameState[x3][y3]['owner']!=2;
}		//check that the tile is either empty or contains an opponents moveable

tests.defeatableOrEmpty = function(x2,y2,x3,y3){
	return parseInt(information.gameState[x2][y2]['content'])>parseInt(information.gameState[x3][y3]['content']) || information.gameState[x3][y3]['content']==0;
}		//check if the tile is either empty or can be defeated

tests.checkRemainingDistance = function(){
	if(Math.min.apply(null,information.distances)==1){
		return false;
	}
	else{
		return true;
	}
}		//check if the path has been completed

tests.viablePath = function(clear){
	return !information.reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']==0 && clear=="yes" || !information.reach[x3][y3]["reachable"] && information.gameState[x3][y3]['content']!="wall" && clear=="no";
}