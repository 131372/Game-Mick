tests = new Object();

tests.boardSide = function(y){
	return (y>6 && information.playerNumber==1 ) || (y<5 && information.playerNumber==2);
}

tests.ownedMoveable = function(x,y){
	return information.gameState[x-1][y-1]['owner']==information.playerNumber && information.gameState[x-1][y-1]['content']!="b" && information.gameState[x-1][y-1]['content']!="f";
}

tests.allowedScoutMovement = function(x,y,x2,y2){
	test=true;
    if(y2==y-1 && x2<x-1){
        for(i=x2+1;i<x-1;i++){
            if(information.gameState[i][y2]['content']!=""){
                test=false;
            }
        }
    }
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
}

tests.allowedMovement = function(x,y,x2,y2){
	return (x2==x || x2+2==x) && y2+1==y || x2+1==x && (y2==y || y2+2==y) || (x2==x-1 || y2==y-1) && information.gameState[x2][y2]['content']==2 && tests.allowedScoutMovement(x,y,x2,y2);
}

tests.canAttack = function(x,y){
	return ((information.gameState[x-1][y-1]['owner']==1 && information.playerNumber!=1) || (information.gameState[x-1][y-1]['owner']==2 && information.playerNumber!=2)) && information.selected!="";
}

tests.moveable = function(x,y){
	return information.gameState[x - 1][y - 1]['content'] != "b" && information.gameState[x - 1][y - 1]['content'] != "f";
}

tests.endOfGameCheck = function(){
	end=false;
	$.post( "gameState.php", {waarde:"update"},function(data) {
		if(data=="end"){
			end=true;
			clearInterval(storage);
		}
	});		//check if the game has ended
	return end;
}

tests.emptyStock = function(){
	sum=0;
	i=1;
	while(typeof information.stock[i] !== 'undefined'){
		sum+=information.stock[i];
		i++;
	}
	sum+=information.stock['f']+information.stock['b'];
	if(sum==0){
		return true;
	}
	else{
		return false
	}
}

tests.trueMoveable = function(x,y){
	test=0;
	if(typeof information.gameState[x-1]!== "undefined"){
		if(information.gameState[x-1][y]['content']!=""){
			test++;
		}
	}
	if(typeof information.gameState[x+1]!== "undefined"){
		if(information.gameState[x+1][y]['content']!=""){
			test++;
		}
	}
	if(typeof information.gameState[x][y-1]!== "undefined"){
		if(information.gameState[x][y-1]['content']!=""){
			test++;
		}
	}
	if(typeof information.gameState[x][y+1]!== "undefined"){
		if(information.gameState[x][y+1]['content']!=""){
			test++;
		}
	}
	if(test==4  || !information.moveable(x,y)){
		return false;
	}
	else{
		return true;
	}
}

tests.maxAbility = function(){
	test=[];
	for(i=0;i<=AI.abilities.length-1;i++){
		test.push(AI.abilities[i]["ability"]);
	}
	return Math.max.apply(null,test);
}

tests.unownedMoveable = function(x3,y3){
	return information.gameState[x3][y3]['content']!="b" && information.gameState[x3][y3]['content']!="f" && information.gameState[x3][y3]['owner']!=2;
}

tests.defeatableOrEmpty = function(x2,y2,x3,y3){
	return parseInt(information.gameState[x2][y2]['content'])>parseInt(information.gameState[x3][y3]['content']) || information.gameState[x3][y3]['content']==0;
}

tests.checkRemainingDistance = function(){
	if(Math.min.apply(null,information.distances)==1){
		return false;
	}
	else{
		return true;
	}
}