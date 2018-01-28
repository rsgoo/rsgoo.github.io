function Point(x, y) {
    this.x = x;
    this.y = y;
}

Point.prototype.move = function (stepX, stepY) {
    this.x += stepX;
    this.y += stepY;
};
