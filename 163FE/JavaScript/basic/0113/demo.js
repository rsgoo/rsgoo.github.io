function daysInOneMonth(year, month) {
    var date = new Date(year, month, 0);
    return date.getDate();
}
console.log(daysInOneMonth(2017,2));
console.log(daysInOneMonth(2017,3));
console.log(daysInOneMonth(2017,4));