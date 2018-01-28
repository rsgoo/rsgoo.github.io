var book = {
    name : 'JavaScript权威指南',
    price: '59.99',
    relate: {
        date: '2011.11.11',
        author : 'Administrator'
    }
};

with (book.relate) {
    console.log(date);
    console.log(author);
}

try {

} catch(exception) {

} finally {

}