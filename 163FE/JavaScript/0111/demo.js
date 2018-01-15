var car = {
    color: "red",
    run: function () {
        console.log('aaaa')
    }
};
car.color = 'blue';
console.log(car.color);
car.run();
console.log(car.constructor);


