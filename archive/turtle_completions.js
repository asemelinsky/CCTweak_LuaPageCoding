// turtle_completions.js
ace.require("ace/ext/language_tools");

const turtleCompletions = [
  "turtle.forward()",
  "turtle.back()",
  "turtle.up()",
  "turtle.down()",
  "turtle.turnLeft()",
  "turtle.turnRight()",
  "turtle.dig()",
  "turtle.digUp()",
  "turtle.digDown()",
  "turtle.place()",
  "turtle.placeUp()",
  "turtle.placeDown()",
  "turtle.select(slot)",
  "turtle.refuel()",
  "turtle.attack()",
  "turtle.attackUp()",
  "turtle.attackDown()",
  "turtle.drop()",
  "turtle.dropUp()",
  "turtle.dropDown()",
  "turtle.getFuelLevel()",
  "turtle.detect()",
  "turtle.detectUp()",
  "turtle.detectDown()",
  "turtle.suck()",
  "turtle.suckUp()",
  "turtle.suckDown()",
  "turtle.compare()",
  "turtle.compareUp()",
  "turtle.compareDown()",
  "turtle.inspect()",
  "turtle.inspectUp()",
  "turtle.inspectDown()",
  "turtle.getItemCount(slot)",
  "turtle.getItemDetail(slot)"
];

ace.require("ace/ext/language_tools").addCompleter({
  getCompletions: function(editor, session, pos, prefix, callback) {
    if (prefix.length === 0) { callback(null, []); return; }
    callback(null, turtleCompletions.map(word => ({
      caption: word,
      value: word,
      meta: "turtle API"
    })));
  }
});
