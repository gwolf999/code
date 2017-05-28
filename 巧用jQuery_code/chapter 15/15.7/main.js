svg.describe('Example radgrad01 - fill a 
    rectangle by ' + 
'referencing a radial gradient paint server'); 
var g = svg.group(); 
var defs = svg.defs(g); 
svg.radialGradient(defs, 'MyGradient', 
[['0%', 'red'], ['50%', 'blue'], ['100%', 'red']], 
200, 100, 150, 200, 100, {gradientUnits: 
    'userSpaceOnUse'}); 
svg.rect(g, 50, 50, 300, 100, 
{fill: 'url(#MyGradient)', stroke: 'black', 
    'stroke-width': 5}); 
g = svg.group(); 
defs = svg.defs(g); 
var ptn = svg.pattern(defs, 'TrianglePattern', 0, 
    0, 100, 100, 
0, 0, 10, 10, {patternUnits: 'userSpaceOnUse'}); 
var path = svg.createPath(); 
svg.path(ptn, path.moveTo(0, 0).lineTo([[7, 0], 
    [3.5, 7]]).close(), 
{fill: 'red', stroke: 'blue'}); 
svg.ellipse(g, 550, 100, 175, 75, 
{fill: 'url(#TrianglePattern)', stroke: 'black', 
    'stroke-width': 5});
