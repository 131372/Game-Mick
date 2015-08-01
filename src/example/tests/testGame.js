describe("board setup",function(){
	it("make sure the stock has been set",function(){
		boardSetup.clickTile();
		expect(boardSetup.stock['2']).toEqual(8);
	})
	it("make sure the function that processes clicks works",function(){
		boardSetup.stock['2']=2;
		boardSetup.type="2";
		boardSetup.gameState[0][0]['content']="";
		boardSetup.click("#x1y1");
		expect(boardSetup.stock['2']).toEqual(1);
		expect(boardSetup.gameState[0][0]['content']).toEqual("2");		//check if the stock changes correctly and the contents of the tile changes
		
		boardSetup.stock['6']=3;
		boardSetup.gameState[2][3]['content']="6";
		boardSetup.click("#x3y4");
		expect(boardSetup.gameState[2][3]['content']).toEqual("");
		expect(boardSetup.stock['6']).toEqual(4);			//make sure that if a tile is occupied by the same type it puts that piece back instead of reducing stock
		
		boardSetup.stock['b']=3;
		boardSetup.type="b";
		boardSetup.gameState[4][5]['content']="f";
		boardSetup.click("#x5y6");
		expect(boardSetup.stock['b']).toEqual(3);
		expect(boardSetup.gameState[4][5]['content']).toEqual("f");		//make sure that if a tile is already occupied by a different piece type it puts that piece back instead of replacing it
	})
	it("make sure the function that changes piece type works",function(){
		boardSetup.type="a";
		boardSetup.typeChange("b");
		expect(boardSetup.type).toEqual("b");
	})
	it("make sure the impassable terrain is set up",function(){
		expect(boardSetup.gameState[4][6]['content']).toEqual("wall");
	})
	it("make sure the game/board state has been created",function(){
		expect(boardSetup.gameState[9][9]).toEqual({owner:0,content:""});
	})
});