function Point(x, y) {
    this.x = x;
    this.y = y;
    this.move = function (stepX, stepY) {
        this.x += stepX;
        this.y += stepY;
    }
}

var point1 = new Point(1,1);
var point2 = new Point(2,2);
var point3 = new Point(3,3);
console.log(point1);
console.log(point2);
console.log(point3);