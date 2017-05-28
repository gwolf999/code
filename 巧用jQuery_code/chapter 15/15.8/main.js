$(
  function()
  {
      initTable();
      window.playerA=new Player(0,"A","playerA");
      window.playerB=new Player(0,"B","playerB");
      window.currentPlayer=  window.playerA; 
      showPlayerMessage();
      $("#rollButton").click(rollButton_click);
  }
 );
function rollButton_click()
{
    if(window.currentPlayer==window.playerA)
    {
        doRoll(window.currentPlayer);
        window.currentPlayer= window.playerB;
        return;
    }
    if(window.currentPlayer==window.playerB)
    {
        doRoll(window.currentPlayer);
        window.currentPlayer= window.playerA;
        return;
    }
}
function doRoll(player)
{
    var startPanel=player.currentPanel; 
    var diceNumber=rollDice();
    var endPanel=startPanel+diceNumber;
    player.currentPanel=endPanel; 
    
    showDiceNumber(diceNumber); 
    showPlayerMessage();
    goNextPanel(startPanel,endPanel,null,player);
}
function goNextPanel(startPanel,endPanel,hoveredPanel,player)
{
    if(hoveredPanel!=null)
    {
        $("#"+hoveredPanel).removeClass(player.cssClass);
        $("#"+hoveredPanel).removeClass("bothPlayer");
    }
    $("#"+startPanel).addClass(player.cssClass);
    if(startPanel<endPanel)
    {
        nextStartPanel=startPanel+1;
    }
    else if(startPanel>endPanel) 
    {
        nextStartPanel=startPanel-1;
    }
    else 
    {
        trap(endPanel,player); 
        reward(endPanel,player); 
        if(isABSame())
        {
            $("#"+endPanel).addClass("bothPlayer");
        }
        showPlayerMessage();
        return;
    }
    if(isWin(startPanel)) 
    {
        alert(player.name+" ,you win!");
        clearTimeout(t); 
        return;
    }
    t=window.setTimeout(function(){goNextPanel(nextStartPanel,endPanel,startPanel,player)},500);
}
function showPlayerMessage()
{
    $("#playerA").text("A当前点数:"+window.playerA.currentPanel);
    $("#playerB").text("B当前点数:"+window.playerB.currentPanel);
    $("#currentPlayer").text("当前玩家："+window.currentPlayer.name);
}
function trap(panel,player)
{
    if(panel==8)
    {
        doTrap(panel,player,2);
    }
    if(panel==4)
    {
        doTrap(panel,player,1);
    }
}
function doTrap(panel,player,count)
{
        alert("哈哈，被炸退"+count+"格！");
        var endPanel=panel-count;
        player.currentPanel=endPanel;
        
        showPlayerMessage();
        goNextPanel(panel,endPanel,null,player);
}
function reward(panel,player)
{
    if(panel==18)
    {
        doReward(panel,player,3);
    }
    if(panel==30)
    {
        doReward(panel,player,4);
    }
}
function doReward(panel,player,count)
{
        alert("恭喜，前进"+count+"格！");
        var endPanel=panel+count;
        player.currentPanel=endPanel;
        
        showPlayerMessage();
        goNextPanel(panel,endPanel,null,player);
        
}
function isWin(panel)
{
    if(panel==48)
    {
        return true;
    }
    else
    {
        return false;
    }
}
function showDiceNumber(number)
{
    $("#diceNumber").text(number);
}
function isABSame()
{
        if(window.playerA.currentPanel==window.playerB.currentPanel)
        {
            return true;
        }
        else
        {
            return false;
        }
}
function Player(startPanel,name,cssClass)
{
    this.currentPanel=startPanel;
    this.name=name;
    this.cssClass=cssClass;
}
function rollDice()
{
    return parseInt(Math.random()*6+1);
}
function getRandomColor()
{
    var str = "0123456789abcdef";
    var colorString = "#";
    for(j=0;j<6;j++)
    {
        colorString = colorString+ str.charAt(Math.random()*str.length);
    } 
    return colorString;
}
function initTable()
{
    $("td").each(
        function()
        {
            $(this).css("background-color",getRandomColor());
        }
    );
}
