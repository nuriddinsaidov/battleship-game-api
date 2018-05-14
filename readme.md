## battleship Game

battleship game api made as a personal project to improve my knowledge regarding laravel and lucid architecture
at this version this game available just with bot

##### how to run application 
- Download composer https://getcomposer.org/download/
- Pull project from git repository.
- Open the console and cd your project root directory
- Run composer install or php composer.phar install


##### List of available commands
1. starting game with empty grid
2. filling empty grid with ships
3. starting game with randomly placed ships
4. shooting
5. receiving shot
6. list of the shots from both player and bot

1. to create empty grid send post request to api/v1/game/grid/create
2. to place ship send post request to `/api/v1/game/{gameId}/place-ship`and get request with param x = number from 1 to 10 and y = letter from A to J
3. to start the game send post request to the url api/v1/game/start 
4. to shoot send post request to /api/v1/game/{gameId}/shot `/api/v1/game/{gameId}/place-ship`and get request with param x = number from 1 to 10 and y = letter from A to J
5. to receive shot api/v1/game/{gameId}/receive-shot
6. to get list of the shots from both side /api/v1/game/{gameId}/shotList