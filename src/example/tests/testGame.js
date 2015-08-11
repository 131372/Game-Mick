information.obatin=false;

describe("board setup",function(){
	it("make sure the stock has been set",function(){
		expect(boardSetup.stock['2']).toEqual(8);
	})
	it("make sure the function that processes clicks works",function(){
		information.playerNumber=1;
		boardSetup.stock['2']=2;
		boardSetup.type="2";
		information.gameState[0][0]['content']="";
		boardSetup.click2("#x1y1b");
		expect(boardSetup.stock['2']).toEqual(2);
		expect(information.gameState[0][0]['content']).toEqual("");				//test if the player can interact with part of the board they are not allowed to interact with
		
		information.playerNumber=2;
		boardSetup.stock['2']=2;
		boardSetup.type="2";
		information.gameState[0][0]['content']="";
		boardSetup.click2("#x1y1b");
		expect(boardSetup.stock['2']).toEqual(1);
		expect(information.gameState[0][0]['content']).toEqual("2");		//check if the stock changes correctly and the contents of the tile changes
		
		boardSetup.stock['6']=3;
		information.gameState[2][3]['content']="6";
		boardSetup.click2("#x3y4b");
		expect(information.gameState[2][3]['content']).toEqual("");
		expect(boardSetup.stock['6']).toEqual(4);			//make sure that if a tile is occupied by the same type it puts that piece back instead of reducing stock
		
		boardSetup.stock['b']=3;
		boardSetup.stock['f']=0;
		boardSetup.type="b";
		information.gameState[4][3]['content']="f";
		boardSetup.click2("#x5y4b");
		expect(boardSetup.stock['b']).toEqual(3);
		expect(boardSetup.stock['f']).toEqual(1);
		expect(information.gameState[4][3]['content']).toEqual("");		//make sure that if a tile is already occupied by a different piece type it puts that piece back instead of replacing it
	})
	it("make sure the function that changes piece type works",function(){
		boardSetup.type="a";
		boardSetup.typeChange("b");
		expect(boardSetup.type).toEqual("b");
	})
	it("make sure the impassable terrain is set up",function(){
		expect(information.gameState[3][5]['content']).toEqual("wall");
	})
	it("make sure the game/board state has been created",function(){
		expect(information.gameState[9][9]).toEqual({owner:0,content:""});
	})
});