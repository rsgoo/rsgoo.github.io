var jack = {
  name: 'jack',
  score: 23
};

function game() {
    // var jack = {
    //     name: 'jack',
    //     score: 33
    // };
    jack.name = "jackson";
    jack.score= 43;
    return jack;
}
console.log(jack);      //{ name: 'jack', score: 23 }
console.log(game());    //{ name: 'jackson', score: 43 }