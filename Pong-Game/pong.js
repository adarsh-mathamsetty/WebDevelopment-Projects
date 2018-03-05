var interval;
var x =0;
var points=0;
var max = document.getElementById('score');
var speed = 0;
var side;

function initialize(){
document.getElementById('messages').innerHTML = "R u ready Champ? Play and challenge your friends!";
var ball = document.getElementById('ball');
ball.style.top = 2 + "px";
ball.style.left = 0 + "px";
   
}

function movePaddle(eve){

var paddle = document.getElementById('paddle');

if(eve.clientY < 380){paddle.style.top = eve.clientY + 'px';
}
}


function setSpeed(s)
{
if(s==0)
{speed = 50;}
else if(s==1)
{speed = 25;}
else
{speed =15;}
}



function resetGame()
{
clearInterval(interval);
initialize();
if(points>max)
{
max =points;
}
score.innerHTML = max;
points=0;
strikes.innerHTML = points;
}
	


var paddleHeight = 120;
var paddleWidth = 15;
var ballRadius = 20;


var positionOfPaddle = 0;

var yco = 2;
var xco = 0;
var dy = 5;
var dx = 5;

function startGame() {
document.getElementById('messages').innerHTML = "Game Started";	
var paddle = document.getElementById('paddle');
var ball = document.getElementById('ball');
var court = document.getElementById('court');
var strike = document.getElementById('strikes');
x++;
//document.getElementById("ball").style.visibility="visible";
yco = 2;
xco = 0;


if( x%2==0)
{ direction = 1; }
else
{ direction = -1;}
dy = direction *dy  ;
dx =  dx ;
	
	
interval = setInterval(function moveball() { 
var br = ball.getBoundingClientRect().right;
var pl = paddle.getBoundingClientRect().left;
var bt = ball.getBoundingClientRect().top;
var pt = paddle.getBoundingClientRect().top;
var bb = ball.getBoundingClientRect().bottom;
var bl = ball.getBoundingClientRect().left;
var cw = court.offsetWidth;
	
var angle = Math.floor(Math.random()*((Math.PI/4)/180));			
yco += dy;
xco += dx;
	
if (yco <= -104  || yco >= 380) 
{
dy = -dy
}



if (br >= cw - paddleWidth)
{
if(bt>pt&&bt<pt+paddleHeight)
{
			
dx = -dx
xco += 2*dx;
points++;
strikes.innerHTML=points;
}

else
{ 
  clearInterval(interval);
  alert("Game Over. Click Reset and Start Game Again");	
  
  

}
}		
if (xco <= 0)
{dx = math.sin(angle)*(dx)}

document.getElementById("ball").style.top = yco + "px";
document.getElementById("ball").style.left = xco + "px";



},speed);
}




