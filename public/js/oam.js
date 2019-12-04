// javascript
// var elem = document.querySelector('.grid');
// if (elem) {
//   var msnry = new Masonry( elem, {
//     // options
//     columnWidth: '.grid-sizer',
//     itemSelector: '.grid-item',
//     percentPosition: true,
//   });
// }

window.addEventListener('load', function () {
  var elem = document.querySelector('.grid');
  if (elem) {
    var msnry = new Masonry( elem, {
      // options
      columnWidth: '.grid-sizer',
      itemSelector: '.grid-item',
      percentPosition: true,
    });
  }
}, false);
