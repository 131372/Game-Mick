In order to set everything up, you make sure the Game-Mick folder is placed in the htdocs folder of you xampp installation. 
Then you create a database according to the DB_setup file (make sure the max lengths of all collumns are sufficiently large). 
Now you can navigate to login.php (Game-Mick/Game/login.php) here you enter a username, if you're trying to play multiplayer game, 
make sure that the two usernames are different. 
In order to host a multiplayer game click create room and then, 
using another computer or browser instance (I recommend opening another chrome window in incognito mode), 
join this room to start the game (using another browser doesn't work as,
for some reason I don't know why, my game only works on google chrome).
 
Now the host can set up his side of the board by selecting a piece (one of the buttons) and clicking a tile (on your side of the board, 
which means the host has te bottom four rows and the guest the top 4) to place that piece there. 
A piece can be removed by simply clicking it again. 
Once all pieces have been placed click the end setup button to allow the guest to place his pieces.
After he also set up his pieces the host is allowed to make the first move by selecting a piece, 
clicking it, and then clicking on an empty tile or an opponent's piece. 
If this move is allowed according to the game rules (adjacent tile or scout in a straight line) the move will be executed and
the turn will be transferred to the opponent. 
Once a flag has been captured the game ends. 

A few things to note:

	- if a player loses all his miners and another player has his flag surrounded by bombs, 

	- if one player wins the other player won't notice untill he refreshes the page

	- using multiple instances of the same username will result in some problems (which should be contained to those usernames)



Technically one could choose to play an AI game, however the AI is unfinished and, though it will sometimes make a move, these moves will not be complete
(for example when the AI defeats or ties with a piece those pieces don't get placed beside the board) and it will eventually stop making moves
(at this point errors can be seen in the console log) it might even get stuck in a while loop (even though it really shouldn't, I have no idea
why it does this). Also the AI's moves will be very suboptimal (in many cases it might walk right by the flag without capturing it, even though
the AI knows where the flag is at all times)
