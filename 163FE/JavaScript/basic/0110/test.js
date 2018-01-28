//使用for循环写一个
function delFirstChar(str) {
    var tmp = '';
    for (var i=1; i<str.length; i++){
        tmp += String(str[i]);
    }
    return tmp;
}

console.log(delFirstChar('雨醉风尘'));

"micromajor163".match(/[0-9]/);   // ["1"]

console.log("micromajor163".match(/[0-9]/g));// ["1","6","3"]