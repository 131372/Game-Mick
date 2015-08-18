information.obtain=false;

describe("boardboard setup",function(){
	it("make sure the stock has been set",function(){
		expect(information.stock['2']).toEqual(8);
	})
	it("make sure the function that changes piece type works",function(){
		information.type="a";
		game.typeChange("b");
		expect(information.type).toEqual("b");
	})
	it("make sure the impassable terrain is set up",function(){
		expect(information.gameState[3][5]['content']).toEqual("wall");
	})
	it("make sure the game/board state has been created",function(){
		expect(information.gameState[9][9]).toEqual({owner:0,content:"",revealed:"no"});
	})
	it("make sure the function that ends the setup works",function(){
		information.gameStage="setup";
		information.stock={f:0,b:1,1:0,2:0};
		game.endSetup();
		expect(information.gameStage).toEqual("setup");
		
		information.stock={f:0,b:0,1:0,2:0};
		game.endSetup();
		expect(information.gameStage).toEqual("main");
	})
});

describe("internal processing", function(){
	it("make sure the stringify function works",function(){
		obj=[[{a:"hoi",b:10},{a:"abc",b:5}],[{a:"xyz",b:0},{a:"hallo",b:100}]];
		expect(stringify(obj)).toEqual("hoi10abc5xyz0hallo100");
	})
});

describe("click events",function(){
	it("make sure the click function works",function(){
		information.gameStage="setup";
		information.playerNumber=1;
		information.stock['2']=2;
		information.type="2";
		information.gameState[0][0]['content']="";
		game.click2("#x1y1b");
		expect(information.stock['2']).toEqual(2);
		expect(information.gameState[0][0]['content']).toEqual("");				//test if the player can interact with part of the board they are not allowed to interact with
		
		information.playerNumber=2;
		information.stock['2']=2;
		information.type="2";
		information.gameState[0][0]['content']="";
		game.click2("#x1y1b");
		expect(information.stock['2']).toEqual(1);
		expect(information.gameState[0][0]['content']).toEqual("2");		//check if the stock changes correctly and the contents of the tile change
		
		information.stock['6']=3;
		information.gameState[2][3]['content']="6";
		game.click2("#x3y4b");
		expect(information.gameState[2][3]['content']).toEqual("");
		expect(information.stock['6']).toEqual(4);			//make sure that if a tile is occupied by the same type it puts that piece back instead of reducing stock
		
		information.stock['b']=3;
		information.stock['f']=0;
		information.type="b";
		information.gameState[4][3]['content']="f";
		game.click2("#x5y4b");
		expect(information.stock['b']).toEqual(3);
		expect(information.stock['f']).toEqual(1);
		expect(information.gameState[4][3]['content']).toEqual("");		//make sure that if a tile is already occupied by a different piece type it puts that piece back instead of replacing it
		
		information.gameStage="main";
		information.turn=true;
		information.selected="";
		information.gameState[1][9]={owner:2,content:4};
		game.click2("#x2y10b");
		expect(information.selected).toEqual([1,9]);		//make sure a tile can be selected
		
		information.turn=true;
		information.selected="";
		information.gameState[1][9]={owner:2,content:"b"};
		game.click2("#x2y10b");
		expect(information.selected).toEqual("");		//make sure bombs can't be selected
		
		information.turn=true;
		information.selected=[9,5];
		information.gameState[9][5]={owner:2,content:6};
		information.gameState[9][6]={owner:0,content:""};
		game.click2("#x10y7b");
		expect(information.gameState[9][5]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[9][6]).toEqual({owner:2,content:6});
		expect(information.turn).toEqual(false);
		expect(information.selected).toEqual("");		//make sure a piece can be moved
		
		information.turn=true;
		information.selected=[9,5];
		information.gameState[9][5]={owner:2,content:6};
		information.gameState[8][6]={owner:0,content:""};
		game.click2("#x9y7b");
		expect(information.gameState[9][5]).toEqual({owner:2,content:6});
		expect(information.gameState[8][6]).toEqual({owner:0,content:""});
		expect(information.turn).toEqual(true);
		expect(information.selected).toEqual([9,5]);		//make sure a piece can't be moved if the target tile is not adjacent
		
		information.turn=true;
		information.selected=[0,0];
		information.gameState[0][0]={owner:2,content:2};
		information.gameState[0][1]={owner:0,content:""};
		information.gameState[0][2]={owner:0,content:""};
		game.click2("#x1y3b");
		expect(information.gameState[0][2]).toEqual({owner:2,content:2});
		expect(information.gameState[0][1]).toEqual({owner:0,content:""});
		expect(information.gameState[0][0]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.turn).toEqual(false);
		expect(information.selected).toEqual("");				//make sure a scout can move multiple tiles
		
		information.turn=true;
		information.selected=[0,0];
		information.gameState[0][0]={owner:2,content:2};
		information.gameState[0][1]={owner:1,content:8};
		information.gameState[0][2]={owner:0,content:""};
		game.click2("#x1y3b");
		expect(information.gameState[0][0]).toEqual({owner:2,content:2});
		expect(information.gameState[0][1]).toEqual({owner:1,content:8});
		expect(information.gameState[0][2]).toEqual({owner:0,content:""});
		expect(information.turn).toEqual(true);
		expect(information.selected).toEqual([0,0]);				//make sure a scout can only move if its path is unobstructed
		
		information.turn=false;
		information.selected=[9,5];
		information.gameState[9][5]={owner:2,content:6};
		information.gameState[9][6]={owner:0,content:""};
		game.click2("#x10y7b");
		expect(information.gameState[9][6]).toEqual({owner:0,content:""});
		expect(information.gameState[9][5]).toEqual({owner:2,content:6});
		expect(information.turn).toEqual(false);
		expect(information.selected).toEqual([9,5]);		//make sure no action can be taken if it is not this player's turn
		
		information.turn=true;
		information.selected=[4,4];
		information.gameState[4][3]={owner:2,content:5};
		information.gameState[4][4]={owner:2,content:4};
		game.click2("#x5y4b");
		expect(information.gameState[4][3]).toEqual({owner:2,content:5});
		expect(information.gameState[4][4]).toEqual({owner:2,content:4});
		expect(information.turn).toEqual(true);
		expect(information.selected).toEqual([4,3]);		//make sure selection can be swapped over to friendly pieces
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:5, revealed:"no"};
		information.gameState[4][4]={owner:1,content:4};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:2,content:5,revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["4",""]);
		expect(information.selected).toEqual("");		//make sure successful aggression is processed properly

		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:4, revealed:"yes"};
		information.gameState[4][4]={owner:1,content:5, revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:1,content:5,revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["","4"]);
		expect(information.selected).toEqual("");		//make sure unsuccessful aggression is processed properly
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:4, revealed:"yes"};
		information.gameState[4][4]={owner:1,content:4, revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["4","4"]);
		expect(information.selected).toEqual("");		//make sure a draw is processed properly
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:1, revealed:"no"};
		information.gameState[4][4]={owner:1,content:10, revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:2,content:1,revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["10",""]);
		expect(information.selected).toEqual("");		//make sure a spy attack is processed properly
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:10, revealed:"no"};
		information.gameState[4][4]={owner:1,content:1, revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:2,content:10,revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["1",""]);
		expect(information.selected).toEqual("");		//make sure a spy defence is processed properly
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:4, revealed:"no"};
		information.gameState[4][4]={owner:1,content:"b", revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:1,content:"b",revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["","4"]);
		expect(information.selected).toEqual("");		//make sure a bomb defence is processed properly
		
		information.turn=true;
		information.selected=[4,3];
		information.gameState[4][3]={owner:2,content:3, revealed:"no"};
		information.gameState[4][4]={owner:1,content:"b", revealed:"no"};
		information.pieces=["",""];
		game.click2("#x5y5b");
		expect(information.gameState[4][3]).toEqual({owner:0,content:"",revealed:"no"});
		expect(information.gameState[4][4]).toEqual({owner:2,content:3,revealed:"yes"});
		expect(information.turn).toEqual(false);
		expect(information.pieces).toEqual(["b",""]);
		expect(information.selected).toEqual("");		//make sure a bomb defence against a miner is processed properly
	})
});